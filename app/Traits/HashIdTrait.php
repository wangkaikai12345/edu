<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/23
 * Time: 14:11
 */

namespace App\Traits;

use Hashids;

trait HashIdTrait
{
    private $hashId;

    public function getHashIdAttribute()
    {
        if (!$this->hashId) {

            $this->hashId = Hashids::encode($this->id);
        }

        return $this->hashId;
    }
    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->hash_id;
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {

        if (!is_numeric($value)) {
            $value = current(Hashids::decode($value));

            if (!$value) return;
        }
        return parent::resolveRouteBinding($value);
    }
}