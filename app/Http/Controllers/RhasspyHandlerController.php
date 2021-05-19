<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RhasspyHandlerController extends Controller
{
    public function handle(): Response {
        return response('hello world', 200)
                  ->header('Content-Type', 'application/json');
    }
}
