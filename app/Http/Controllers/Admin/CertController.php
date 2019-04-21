<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CertRequest;
use Illuminate\Support\Facades\Storage;

class CertController extends Controller
{
    /**
     * @SWG\Tag(name="admin/cert",description="证书")
     */

    /**
     * @SWG\Post(
     *  path="/admin/certs",
     *  tags={"admin/cert"},
     *  summary="证书上传",
     *  description="",
     *  @SWG\Parameter(in="formData",name="type",type="string",enum={"cert_client","cert_key"},description="微信证书类型"),
     *  @SWG\Parameter(in="formData",name="pem",type="string",description="证书文件"),
     *  @SWG\Response(response=201,description="{'path':'certs/cert_client.pem'}"),
     *  security={
     *      {"Bearer": {}}
     *  }
     * )
     */
    public function store(CertRequest $request)
    {
        $pem = $request->file('pem');

        $path = Storage::disk('local')->putFileAs('certs', $pem, $request->type . '.pem');

        return $this->response->array(['path' => $path])->setStatusCode(201);
    }
}
