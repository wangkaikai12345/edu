<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Requests\Admin\RechargingRequest;
use App\Http\Transformers\RechargingTransformer;
use App\Models\Recharging;
use App\Http\Controllers\Controller;

class RechargingController extends Controller
{
    /**
     * @SWG\Tag(name="admin/recharging",description="充值额度管理")
     */

    /**
     * @SWG\Get(
     *  path="/admin/recharging",
     *  tags={"admin/recharging"},
     *  summary="充值额度列表",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/RechargingQuery-title"),
     *  @SWG\Parameter(ref="#/parameters/RechargingQuery-price"),
     *  @SWG\Parameter(ref="#/parameters/RechargingQuery-origin_price"),
     *  @SWG\Parameter(ref="#/parameters/RechargingQuery-coin"),
     *  @SWG\Parameter(ref="#/parameters/RechargingQuery-extra_coin"),
     *  @SWG\Parameter(ref="#/parameters/RechargingQuery-income"),
     *  @SWG\Parameter(ref="#/parameters/RechargingQuery-bought_count"),
     *  @SWG\Parameter(ref="#/parameters/RechargingQuery-status"),
     *  @SWG\Parameter(ref="#/parameters/RechargingQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/RechargingQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/Recharging-sort"),
     *  @SWG\Parameter(ref="#/parameters/Recharging-include"),
     *  @SWG\Response(response=200,ref="#/responses/RechargingPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        $data = Recharging::filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new RechargingTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/recharging",
     *  tags={"admin/recharging"},
     *  summary="添加充值额度",
     *  description="",
     *  @SWG\Parameter(ref="#/parameters/RechargingForm-title"),
     *  @SWG\Parameter(ref="#/parameters/RechargingForm-price"),
     *  @SWG\Parameter(ref="#/parameters/RechargingForm-origin_price"),
     *  @SWG\Parameter(ref="#/parameters/RechargingForm-coin"),
     *  @SWG\Parameter(ref="#/parameters/RechargingForm-extra_coin"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/Recharging"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(RechargingRequest $request)
    {
        $recharging = new Recharging($request->all());
        $recharging->user_id = auth()->id();
        $recharging->save();

        return $this->response->item($recharging, new RechargingTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Put(
     *  path="/admin/recharging/{recharging_id}",
     *  tags={"admin/recharging"},
     *  summary="修改充值额度",
     *  description="",
     *  @SWG\Parameter(name="recharging_id",in="path",type="integer",required=true,description="充值额度ID"),
     *  @SWG\Parameter(ref="#/parameters/RechargingForm-title"),
     *  @SWG\Parameter(ref="#/parameters/RechargingForm-price"),
     *  @SWG\Parameter(ref="#/parameters/RechargingForm-origin_price"),
     *  @SWG\Parameter(ref="#/parameters/RechargingForm-coin"),
     *  @SWG\Parameter(ref="#/parameters/RechargingForm-extra_coin"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Recharging $recharging, RechargingRequest $request)
    {
        $recharging->fill($request->all());
        $recharging->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/recharging/{recharging_id}",
     *  tags={"admin/recharging"},
     *  summary="关闭充值额度",
     *  description="",
     *  @SWG\Parameter(name="recharging_id",in="path",type="integer",description="充值记录ID"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Recharging $recharging)
    {
        $recharging->status = Status::CLOSED;
        $recharging->save();

        return $this->response->noContent();
    }

    public function publish(Recharging $recharging)
    {  
        $recharging->status = Status::PUBLISHED;
        $recharging->save();
      
        return $this->response->noContent();
    }
}
