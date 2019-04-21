<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/23
 * Time: 14:11
 */

namespace App\Traits;

use App\Enums\CouponType;
use App\Enums\Currency;
use App\Enums\ExpiryMode;
use App\Enums\JoinType;
use App\Enums\OrderStatus;
use App\Enums\Payment;
use App\Enums\StudentStatus;
use App\Enums\StudentType;
use App\Models\ClassroomMember;
use App\Models\Order;
use App\Models\PlanMember;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Plan;
use Ramsey\Uuid\Uuid;

trait JoinTrait
{
    /**
     * 学员参加版本
     *
     * @param Order $order
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function joinPlan(Order $order)
    {
        // 参加过的学员不再再次添加
        if ($member = PlanMember::where('user_id', $order->user_id)->where('plan_id', $order->product->id)->first()) {
            return $member;
        }
        $member = new PlanMember();
        $member->course_id = $order->product->course_id;
        $member->plan_id = $order->product->id;
        $member->user_id = $order->user_id;
        $member->join_type = JoinType::PURCHASE;
        $member->deadline = $this->calculateDeadline($order);
        $member->order_id = $order->id;
        $member->save();
        return $member;
    }

    /**
     * 学员参加班级
     *
     * @param Order $order
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function joinClassroom(Order $order)
    {
        // 参加过的学员不再再次添加
        if ($member = ClassroomMember::where('user_id', $order->user_id)->where('classroom_id', $order->product->id)->first()) {
            return $member;
        }
        $member = new ClassroomMember();
        $member->classroom_id = $order->product->id;
        $member->user_id = $order->user_id;
        $member->deadline = $this->calculateDeadline($order);
        $member->type = $order->coupon_type === CouponType::AUDITION ? StudentType::AUDITION : StudentType::OFFICIAL;
        $member->status = StudentStatus::BEGINNING;
        $member->current_chap = $order->product->chaptersChildren()->count() ? $order->product->chaptersChildren()[0]['children'][0]->id : 0;
        $member->save();
    }

    /**
     * 根据有效时间参数计算截止日期
     *
     * @param Order $order
     * @return \Illuminate\Support\Carbon|null
     */
    public function calculateDeadline(Order $order)
    {
        // 试听券的试听截止天数
        if ($order->coupon_type === CouponType::AUDITION) {
            return now()->addDays($order->coupon->audition_days);
        }

        // 当为课程、或班级时
        $product = $order->product;

        if ($product->expiry_mode === ExpiryMode::PERIOD) {
            return $product->expiry_ended_at;
        } else if ($product->expiry_mode === ExpiryMode::VALID) {
            return now()->addDays($product->expiry_days);
        } else {
            return null;
        }
    }

    /**
     * 免费加入课程和班级
     *
     * @param $type
     * @param $productId
     * @param $userId
     * @param $style
     * @return bool
     * @author 王凯
     */
    public function freeOrInside($type, $productId, $userId, $style)
    {
        // 验证是否支持免费产品购买
        if (! in_array($type, ['classroom', 'plan'])) return false;

        // 验证是否支持免费产品购买
        if (! in_array($style, ['free', 'inside'])) return false;

        // 验证是否网站用户
        if (! $user = User::find($userId) ) return false;

        // 验证是否学生，用户是否锁定
        if (! $user->hasAnyRole(['student']) || $user->locked) return false;

        // 验证版本是否正常
        if ($type == 'plan') {
            if (! $product = Plan::find($productId)) return false;

            if ($product->status != 'published' || $product->locked || !$product->buy) return false;
            $title = $product->course_title . ' ' . $product->title;
        }

        // 验证班级是否正常
        if ($type == 'classroom') {
            if (! $product = Classroom::find($productId)) return false;

            if ($product->status != 'published') return false;
            $title = $product->title;
        }

        \DB::transaction(function () use ($title, $userId, $product, $type, $style) {
            // 创建订单
            $order = new Order();
            $order->title = $title;
            $order->paid_amount = 0;
            $order->paid_at = now();
            $order->payment = Payment::FREE;
            $order->finished_at = now();
            $order->refund_deadline = now();
            $order->price_amount = 0;
            $order->pay_amount = 0;
            $order->currency = Currency::FREE;
            $order->user_id = $userId;
            $order->status = OrderStatus::FINISHED;
            $order->trade_uuid = generate_only();
            $order->product_id = $product->id;
            $order->product_type = $type;

            $order->save();

            if ($type == 'plan') {
                if (!PlanMember::where('user_id', $userId)->where('plan_id', $product->id)->exists()) {
                    $member = new PlanMember();

                    $member->course_id = $product->course_id;
                    $member->plan_id = $product->id;
                    $member->user_id = $userId;
                    $member->join_type = $style;
                    $member->deadline = $this->calculateDeadline($order);
                    $member->order_id = $order->id;
                    $member->save();
                }
            }

            if ($type == 'classroom') {
                if (!$member = ClassroomMember::where('user_id', $userId)->where('classroom_id', $product->id)->exists()) {
                    $member = new ClassroomMember();
                    $member->classroom_id = $product->id;
                    $member->user_id = $userId;
                    $member->deadline = $this->calculateDeadline($order);
                    $member->type = $style == 'inside' ? $style : 'official';
                    $member->status = StudentStatus::BEGINNING;
                    $member->current_chap = $order->product->chaptersChildren()->count() ? $order->product->chaptersChildren()[0]['children'][0]->id : 0;
                    $member->save();
                }

            }

        });

        return true;
    }
    
}