<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/10/22
 * Time: 11:43
 */

namespace App\Services;

/**
 * 在高并发情况下，用户会在同一时间内产生若干条相同的订单，而业务逻辑上的唯一性检查已不满足。
 * 故使用缓存锁的方式解决此类问题（在一定程度上解决）
 *
 * 1. 添加缓存键，保存用户ID、商品ID、商品类型等
 * 2. 设置有效时间，10 秒（视情况而定，错开并发时间即可）
 * 2. 在每一次创建订单时，检查是否存在，若存在则抛出异常
 */
class OrderUnique
{
    /**
     * @const string order:用户ID:商品类型:商品ID
     */
    const OrderUniqueKeyPrefix = 'order:';
    /**
     * @const integer 缓存时间
     */
    const KeyExistsSeconds = 5;
    /**
     * @var string
     */
    public $key;

    /**
     * OrderUnique constructor.
     *
     * @param int    $userId
     * @param string $productType
     * @param int    $productId
     */
    public function __construct(int $userId, string $productType, int $productId)
    {
        $this->key = self::OrderUniqueKeyPrefix . "{$userId}:{$productType}:{$productId}";
    }

    /**
     * @return bool
     */
    public function set()
    {
        return \Redis::setEx($this->key, self::KeyExistsSeconds, true);
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return \Redis::exists($this->key);
    }

    /**
     * @return int
     */
    public function clear()
    {
        return \Redis::del($this->key);
    }

    /**
     * @return int
     */
    public function ttl()
    {
        return \Redis::ttl($this->key);
    }
}