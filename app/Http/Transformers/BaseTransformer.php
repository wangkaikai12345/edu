<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/6/28
 * Time: 11:01
 */

namespace App\Http\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    /**
     * 设置数据的 Transformer
     *
     * @param Collection|Model $dataOrItem
     * @param TransformerAbstract $transformer
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    protected function setDataOrItem($dataOrItem, $transformer)
    {
        // 如果是 Collection 并且为空
        if ($dataOrItem instanceof Collection) {
            if ($dataOrItem->isEmpty()) {
                return $this->null();
            } else {
                return $this->collection($dataOrItem, $transformer);
            }
        } else {
            if (!$dataOrItem) {
                return $this->null();
            }
            return $this->item($dataOrItem, $transformer);
        }
    }
}