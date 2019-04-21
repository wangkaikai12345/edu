<?php

namespace App\Jobs;

use App\Models\Audio;
use App\Models\Chapter;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Doc;
use App\Models\Favorite;
use App\Models\Follow;
use App\Models\Note;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\PlanTeacher;
use App\Models\Ppt;
use App\Models\Profile;
use App\Models\Refund;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\Task;
use App\Models\TaskResult;
use App\Models\Text;
use App\Models\Topic;
use App\Models\Trade;
use App\Models\User;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DataMigrate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tableName;
    private $oldData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($table, $oldData)
    {
        // 初始化数据
        $this->tableName = $table;
        $this->oldData = $oldData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 这里执行实际的数据迁移操作
        $table = $this->tableName;
//        // TODO 阻止事件
//        $model = resolve('App\\Models\\' . $table);
//        $model::unsetEventDispatcher();
//        // TODO 执行迁移

        // 放入队列
        $this->$table($this->oldData);
    }

    /**
     * 执行audio迁移
     */
    private function audio($oldData)
    {
        $newData = new Audio();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->media_uri = $oldData->media_uri;
        $newData->hash = $oldData->hash;
        $newData->length = $oldData->length;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行chapters迁移
     */
    private function chapters($oldData)
    {
        $newData = new Chapter();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->title = $oldData->title;
        $newData->seq = $oldData->seq;
        $newData->course_id = $oldData->course_id;
        $newData->plan_id = $oldData->plan_id;
        $newData->parent_id = $oldData->parent_id;
        $newData->user_id = $oldData->user_id;
        $newData->copy_id = $oldData->copy_id;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;
        $newData->deleted_at = $oldData->deleted_at;

        $newData->save();
    }

    /**
     * 执行coupons迁移
     */
    private function coupons($oldData)
    {
        $newData = new Coupon();
        $newData::unsetEventDispatcher();

        $newData->code = $oldData->code;
        $newData->batch = $oldData->batch;
        $newData->type = $oldData->type;
        $newData->value = $oldData->value;
        $newData->expired_at = $oldData->expired_at;
        $newData->user_id = $oldData->user_id;
        $newData->consumer_id = $oldData->consumer_id;
        $newData->consumed_at = $oldData->consumed_at;
        $newData->product_id = $oldData->product_id;
        $newData->product_type = $oldData->product_type;
        $newData->status = $oldData->status;
        $newData->remark = $oldData->remark;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行courses迁移
     */
    private function courses($oldData)
    {
        $newData = new Course();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->title = $oldData->title;
        $newData->subtitle = $oldData->subtitle;
        $newData->summary = $oldData->summary;
        $newData->category_id = $oldData->category_id;
        $newData->goals = $oldData->goals ? json_decode($oldData->goals, true) : null;
        $newData->audiences = $oldData->audiences ? json_decode($oldData->audiences, true) : null;
        $newData->cover = $oldData->cover;
        $newData->status = $oldData->status;
        $newData->serialize_mode = $oldData->serialize_mode;
        $newData->is_recommended = $oldData->is_recommended;
        $newData->recommended_seq = $oldData->recommended_seq;
        $newData->recommended_at = $oldData->recommended_at;
        $newData->hit_count = $oldData->hit_count;
        $newData->locked = $oldData->locked;
        $newData->min_course_price = $oldData->min_course_price;
        $newData->max_course_price = $oldData->max_course_price;
        $newData->default_plan_id = $oldData->default_plan_id;
        $newData->discount_id = $oldData->discount_id;
        $newData->discount = $oldData->discount;
        $newData->max_discount = $oldData->max_discount;
        $newData->materials_count = $oldData->materials_count;
        $newData->reviews_count = $oldData->reviews_count;
        $newData->rating = $oldData->rating;
        $newData->notes_count = $oldData->notes_count;
        $newData->students_count = $oldData->students_count;
        $newData->user_id = $oldData->user_id;
        $newData->category_first_level_id = $oldData->category_first_level_id;
        $newData->copy_id = $oldData->copy_id;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;
        $newData->deleted_at = $oldData->deleted_at;

        $newData->save();
    }

    /**
     * 执行docs迁移
     */
    private function docs($oldData)
    {
        $newData = new Doc();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->media_uri = $oldData->media_uri;
        $newData->hash = $oldData->hash;
        $newData->length = $oldData->length;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行favorites迁移
     */
    private function favorites($oldData)
    {
        $newData = new Favorite();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->user_id = $oldData->user_id;
        $newData->model_id = $oldData->model_id;
        $newData->model_type = $oldData->model_type;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行follows迁移
     */
    private function follows($oldData)
    {
        $newData = new Follow();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->user_id = $oldData->user_id;
        $newData->follow_id = $oldData->follow_id;
        $newData->is_pair = $oldData->is_pair;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行notes迁移
     */
    private function notes($oldData)
    {
        $newData = new Note();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->user_id = $oldData->user_id;
        $newData->course_id = $oldData->course_id;
        $newData->plan_id = $oldData->plan_id;
        $newData->task_id = $oldData->task_id;
        $newData->content = $oldData->content;
        $newData->is_public = $oldData->is_public;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;
        $newData->deleted_at = $oldData->deleted_at;

        $newData->save();
    }

    /**
     * 执行orders迁移
     */
    private function orders($oldData)
    {
        $newData = new Order();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->title = $oldData->title;
        $newData->price_amount = $oldData->price_amount;
        $newData->pay_amount = $oldData->pay_amount;
        $newData->currency = $oldData->currency;
        $newData->user_id = $oldData->user_id;
        $newData->seller_id = $oldData->seller_id;
        $newData->status = $oldData->status;
        $newData->trade_uuid = $oldData->trade_uuid;
        $newData->paid_amount = $oldData->paid_amount;
        $newData->paid_at = $oldData->paid_at;
        $newData->payment = $oldData->payment;
        $newData->finished_at = $oldData->finished_at;
        $newData->closed_user_id = $oldData->closed_user_id;
        $newData->closed_message = $oldData->closed_message;
        $newData->closed_at = $oldData->closed_at;
        $newData->refund_deadline = $oldData->refund_deadline;
        $newData->product_id = $oldData->product_id;
        $newData->product_type = $oldData->product_type;
        $newData->coupon_code = $oldData->coupon_code;
        $newData->coupon_type = $oldData->coupon_type;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;
        $newData->deleted_at = $oldData->deleted_at;

        $newData->save();
    }

    /**
     * 执行plan_members迁移
     */
    private function plan_members($oldData)
    {
        $newData = new PlanMember();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->course_id = $oldData->course_id;
        $newData->plan_id = $oldData->plan_id;
        $newData->user_id = $oldData->user_id;
        $newData->order_id = $oldData->order_id;
        $newData->deadline = $oldData->deadline;
        $newData->join_type = $oldData->join_type;
        $newData->learned_count = $oldData->learned_count;
        $newData->learned_compulsory_count = $oldData->learned_compulsory_count;
        $newData->credit = $oldData->credit;
        $newData->notes_count = $oldData->notes_count;
        $newData->note_last_updated_at = $oldData->note_last_updated_at;
        $newData->is_finished = $oldData->is_finished;
        $newData->finished_at = $oldData->finished_at;
        $newData->status = $oldData->status;
        $newData->remark = $oldData->remark;
        $newData->last_learned_at = $oldData->last_learned_at;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;
        $newData->deleted_at = $oldData->deleted_at;

        $newData->save();
    }

    /**
     * 执行plan_teachers迁移
     */
    private function plan_teachers($oldData)
    {
        // 如果课程被删除了, 那么不复制这条数据
        $check = Course::find($oldData->course_id);
        if (empty($check)) return false;

        $newData = new PlanTeacher();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->course_id = $oldData->course_id;
        $newData->plan_id = $oldData->plan_id;
        $newData->user_id = $oldData->user_id;
        $newData->seq = $oldData->seq;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行plans迁移
     */
    private function plans($oldData)
    {
        $newData = new Plan();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->course_id = $oldData->course_id;
        $newData->course_title = $oldData->course_title;
        $newData->title = $oldData->title;
        $newData->about = $oldData->about;
        $newData->learn_mode = $oldData->learn_mode;
        $newData->expiry_mode = $oldData->expiry_mode;
        $newData->expiry_started_at = $oldData->expiry_started_at;
        $newData->expiry_ended_at = $oldData->expiry_ended_at;
        $newData->expiry_days = $oldData->expiry_days;
        $newData->goals = $oldData->goals;
        $newData->audiences = $oldData->audiences;
        $newData->is_default = $oldData->is_default;
        $newData->max_students_count = $oldData->max_students_count;
        $newData->status = $oldData->status;
        $newData->is_free = $oldData->is_free;
        $newData->free_started_at = $oldData->free_started_at;
        $newData->free_ended_at = $oldData->free_ended_at;
        $newData->services = $oldData->services;
        $newData->show_services = $oldData->show_services;
        $newData->enable_finish = $oldData->enable_finish;
        $newData->income = $oldData->income;
        $newData->origin_price = $oldData->origin_price;
        $newData->price = $oldData->price;
        $newData->origin_coin_price = 0;
        $newData->coin_price = 0;
        $newData->locked = $oldData->locked;
        $newData->buy = $oldData->buy;
        $newData->serialize_mode = $oldData->serialize_mode;
        $newData->max_discount = $oldData->max_discount;
        $newData->deadline_notification = $oldData->deadline_notification;
        $newData->notify_before_days_of_deadline = $oldData->notify_before_days_of_deadline;
        $newData->rating = $oldData->rating;
        $newData->reviews_count = $oldData->reviews_count;
        $newData->tasks_count = $oldData->tasks_count;
        $newData->compulsory_tasks_count = $oldData->compulsory_tasks_count;
        $newData->students_count = $oldData->students_count;
        $newData->notes_count = $oldData->notes_count;
        $newData->hit_count = $oldData->hit_count;
        $newData->topics_count = $oldData->topics_count;
        $newData->user_id = $oldData->user_id;
        $newData->copy_id = $oldData->copy_id;
        $newData->deleted_at = $oldData->deleted_at;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行ppts迁移
     */
    private function ppts($oldData)
    {
        $newData = new Ppt();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->media_uri = $oldData->media_uri;
        $newData->hash = $oldData->hash;
        $newData->length = $oldData->length;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行profile迁移
     */
    private function profile($oldData)
    {
        $newData = new Profile();
        $newData::unsetEventDispatcher();

        $newData->user_id = $oldData->user_id;
        $newData->title = $oldData->title;
        $newData->name = $oldData->name;
        $newData->idcard = $oldData->idcard;
        $newData->gender = $oldData->gender;
        $newData->birthday = $oldData->birthday;
        $newData->city = $oldData->city;
        $newData->about = $oldData->about;
        $newData->company = $oldData->company;
        $newData->job = $oldData->job;
        $newData->school = $oldData->school;
        $newData->major = $oldData->major;
        $newData->qq = $oldData->qq;
        $newData->weibo = $oldData->weibo;
        $newData->weixin = $oldData->weixin;
        $newData->is_qq_public = $oldData->is_qq_public;
        $newData->is_weixin_public = $oldData->is_weixin_public;
        $newData->is_weibo_public = $oldData->is_weibo_public;
        $newData->site = $oldData->site;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行refunds迁移
     */
    private function refunds($oldData)
    {
        $newData = new Refund();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->title = $oldData->title;
        $newData->order_id = $oldData->order_id;
        $newData->status = $oldData->status;
        $newData->payment = $oldData->payment;
        $newData->payment_sn = $oldData->payment_sn;
        $newData->user_id = $oldData->user_id;
        $newData->reason = $oldData->reason;
        $newData->currency = $oldData->currency;
        $newData->applied_amount = $oldData->applied_amount;
        $newData->refunded_amount = $oldData->refunded_amount;
        $newData->payment_callback = $oldData->payment_callback;
        $newData->handled_at = $oldData->handled_at;
        $newData->handler_id = $oldData->handler_id;
        $newData->handled_reason = $oldData->handled_reason;
        $newData->deleted_at = $oldData->deleted_at;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行roles迁移
     */
    private function roles($oldData)
    {
        $newData = new Role();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->name = $oldData->name;
        $newData->guard_name = $oldData->guard_name;
        $newData->title = $oldData->title;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行slides迁移
     */
    private function slides($oldData)
    {
        $newData = new Slide();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->title = $oldData->title;
        $newData->seq = $oldData->seq;
        $newData->image = $oldData->image;
        $newData->link = $oldData->link;
        $newData->description = $oldData->description;
        $newData->user_id = $oldData->user_id;
        $newData->deleted_at = $oldData->deleted_at;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行task_results迁移
     */
    private function task_results($oldData)
    {
        $newData = new TaskResult();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->course_id = $oldData->course_id;
        $newData->plan_id = $oldData->plan_id;
        $newData->task_id = $oldData->task_id;
        $newData->user_id = $oldData->user_id;
        $newData->status = $oldData->status;
        $newData->time = $oldData->time;
        $newData->finished_at = $oldData->finished_at;
        $newData->deleted_at = $oldData->deleted_at;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行tasks迁移
     */
    private function tasks($oldData)
    {
        $newData = new Task();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->course_id = $oldData->course_id;
        $newData->plan_id = $oldData->plan_id;
        $newData->chapter_id = $oldData->chapter_id;
        $newData->title = $oldData->title;
        $newData->status = $oldData->status;
        $newData->is_free = $oldData->is_free;
        $newData->is_optional = $oldData->is_optional;
        $newData->type = $oldData->type == 'task' ? 'c-task' : $oldData->type;
        $newData->user_id = $oldData->user_id;
        $newData->seq = $oldData->seq;
        $newData->started_at = $oldData->started_at;
        $newData->ended_at = $oldData->ended_at;
        $newData->target_id = $oldData->target_id;
        $newData->target_type = $oldData->target_type;
        $newData->length = $oldData->length;
        $newData->finish_type = $oldData->finish_type;
        $newData->finish_detail = $oldData->finish_detail;
        $newData->copy_id = $oldData->copy_id;
        $newData->deleted_at = $oldData->deleted_at;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行texts迁移
     */
    private function texts($oldData)
    {
        $newData = new Text();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->body = $oldData->body;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行topics迁移
     */
    private function topics($oldData)
    {
        $newData = new Topic();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->type = $oldData->type;
        $newData->title = $oldData->title;
        $newData->content = $oldData->content;
        $newData->is_stick = $oldData->is_stick;
        $newData->is_elite = $oldData->is_elite;
        $newData->is_audited = $oldData->is_audited;
        $newData->user_id = $oldData->user_id;
        $newData->course_id = $oldData->course_id;
        $newData->plan_id = $oldData->plan_id;
        $newData->task_id = $oldData->task_id;
        $newData->replies_count = $oldData->replies_count;
        $newData->hit_count = $oldData->hit_count;
        $newData->latest_replier_id = $oldData->latest_replier_id;
        $newData->latest_replied_at = $oldData->latest_replied_at;
        $newData->status = $oldData->status;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行trades迁移
     */
    private function trades($oldData)
    {
        $newData = new Trade();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->title = $oldData->title;
        $newData->order_id = $oldData->order_id;
        $newData->trade_uuid = $oldData->trade_uuid;
        $newData->status = $oldData->status;
        $newData->currency = $oldData->currency;
        $newData->paid_amount = $oldData->paid_amount;
        $newData->seller_id = $oldData->seller_id;
        $newData->user_id = $oldData->user_id;
        $newData->type = $oldData->type;
        $newData->payment = $oldData->payment;
        $newData->payment_sn = $oldData->payment_sn ? $oldData->payment_sn : 'default';
        $newData->payment_callback = $oldData->payment_callback;
        $newData->paid_at = $oldData->paid_at;
        $newData->deleted_at = $oldData->deleted_at;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行users迁移
     */
    private function users($oldData)
    {
        $newData = new User();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->username = $oldData->username;
        $newData->password = $oldData->password;
        $newData->email = $oldData->email;
        $newData->phone = $oldData->phone;
        $newData->signature = $oldData->signature;
        $newData->avatar = $oldData->avatar;
        $newData->tags = $oldData->tags;
        $newData->inviter_id = $oldData->inviter_id;
        $newData->is_email_verified = $oldData->is_email_verified;
        $newData->is_phone_verified = $oldData->is_phone_verified;
        $newData->registered_ip = $oldData->registered_ip;
        $newData->registered_way = $oldData->registered_way;
        $newData->locked = $oldData->locked;
        $newData->is_recommended = $oldData->is_recommended;
        $newData->recommended_seq = $oldData->recommended_seq;
        $newData->recommended_at = $oldData->recommended_at;
        $newData->locked_deadline = $oldData->locked_deadline;
        $newData->password_error_times = $oldData->password_error_times;
        $newData->last_password_failed_at = $oldData->last_password_failed_at;
        $newData->last_logined_at = $oldData->last_logined_at;
        $newData->last_logined_ip = $oldData->last_logined_ip;
        $newData->new_messages_count = $oldData->new_messages_count;
        $newData->new_notifications_count = $oldData->new_notifications_count;
        $newData->invitation_code = $oldData->invitation_code;
        $newData->coin = $oldData->coin;
        $newData->remember_token = $oldData->remember_token;
        $newData->deleted_at = $oldData->deleted_at;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行videos迁移
     */
    private function videos($oldData)
    {
        $newData = new Video();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->media_uri = $oldData->media_uri;
        $newData->hash = $oldData->hash ? $oldData->hash : 'default';
        $newData->length = $oldData->length;
        $newData->status = $oldData->status;
        $newData->deleted_at = $oldData->deleted_at;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }

    /**
     * 执行settings迁移
     */
    private function settings($oldData)
    {
        $newData = new Setting();
        $newData::unsetEventDispatcher();

        $newData->id = $oldData->id;
        $newData->namespace = $oldData->namespace;
        $newData->value = $oldData->value;
        $newData->created_at = $oldData->created_at;
        $newData->updated_at = $oldData->updated_at;

        $newData->save();
    }
}
