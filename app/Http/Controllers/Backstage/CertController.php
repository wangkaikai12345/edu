<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CertRequest;
use Illuminate\Support\Facades\Storage;

class CertController extends Controller
{
    public function store(CertRequest $request)
    {
        $pem = $request->file('pem');

        $fileName = $request->type . '.pem';

        Storage::disk('local')->putFileAs('certs', $pem, $fileName);

        $path = Storage::path("certs/cert_client.pem");

        return $this->response->array(['path' => $path])->setStatusCode(201);
    }
}
