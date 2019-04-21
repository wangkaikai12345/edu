<?php

namespace App\Exports;


use App\Enums\CouponType;
use App\Models\Coupon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConversationExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    /**
     * 获取数据
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function collection()
    {
        return Coupon::query()->latest()->get();
    }

    /**
     * 格式化数据
     *
     * @param mixed $coupon
     * @return array
     */
    public function map($coupon): array
    {
        return [
            $coupon->code,
            CouponType::getDescription($coupon->type),
            $this->getContent($coupon),
            $coupon->expired_at,
            $coupon->status == 'unused' ? '未使用' : '已使用',
            $coupon->user->username,
            $coupon->created_at,
        ];
    }

    /**
     * 表头
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            '优惠码',
            '类型',
            '优惠金额',
            '截止日期',
            '状态',
            '创建者',
            '创建时间',
        ];
    }


    /**
     * 获取优惠内容
     *
     * @param $coupon
     * @return null|string
     */
    private function getContent($coupon)
    {
        $string = null;
        switch ($coupon->type) {
            case 'discount':

                if (empty($coupon->product_id)) {
                    $string = "折扣{$coupon->value}%&nbsp;-&nbsp;全部课程";
                } else {
                    $string = "折扣{$coupon->value}}%&nbsp;-&nbsp;{$coupon->product->course_title}";
                }
                break;
            case
            'voucher' :
                if (empty($coupon->product_id))
                    $string = "抵现{$coupon->value}元 & nbsp;-&nbsp;全部课程";
                else {
                    $string = "抵现{$coupon->value}元 & nbsp;-&nbsp;{$coupon->product->course_title}";
                }

                break;
        }
        return $string;
    }
}