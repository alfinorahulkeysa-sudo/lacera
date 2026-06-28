<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class HealthController extends Controller
{
    public function index()
    {
        return response('OK', Response::HTTP_OK);
    }
}
