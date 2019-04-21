<?php

namespace App\Http\Controllers\Web;

use App\Enums\Status;
use App\Http\Transformers\RechargingTransformer;
use App\Models\Recharging;
use App\Http\Controllers\Controller;

class RechargingController extends Controller
{
    /**
     * @SWG\Tag(name="web/recharging",description="充值额度")
     */

    /**
     * @SWG\Get(
     *  path="/recharging",
     *  tags={"web/recharging"},
     *  summary="充值额度列表",
     *  description="仅展示已发布的充值额度",
     *  @SWG\Response(response=200,ref="#/responses/RechargingResponse"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        $data = Recharging::where('status', Status::PUBLISHED)->get([
            'id',
            'title',
            'price',
            'origin_price',
            'coin',
            'extra_coin'
        ]);

        return $this->response->collection($data, new RechargingTransformer());
    }

    public function show(Recharging $recharging)
    {
        return $this->response->item($recharging, new RechargingTransformer());
    }
}
