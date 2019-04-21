<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function show($content, Request $request)
    {
        return frontend_view('pages.'.$content);
    }
}
