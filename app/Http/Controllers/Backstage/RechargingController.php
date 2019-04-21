<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\Status;
use App\Http\Requests\Admin\RechargingRequest;
use App\Http\Transformers\RechargingTransformer;
use App\Models\Recharging;
use App\Http\Controllers\Controller;

class RechargingController extends Controller
{

    public function index()
    {
        $data = Recharging::filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new RechargingTransformer());
    }

    public function store(RechargingRequest $request)
    {
        $recharging = new Recharging($request->all());
        $recharging->user_id = auth()->id();
        $recharging->save();

        return $this->response->item($recharging, new RechargingTransformer())->setStatusCode(201);
    }


    public function update(Recharging $recharging, RechargingRequest $request)
    {
        $recharging->fill($request->all());
        $recharging->save();

        return $this->response->noContent();
    }


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
