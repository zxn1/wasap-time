<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function makeCallingRequest(Request $req)
    {
        $data = [
            'from_id' => $req->from_id,
            'to_id' => $req->to_id,
        ];

        return view('pages.webRTC', ['data' => $data]);
    }
}
