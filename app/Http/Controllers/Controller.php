<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $response;
    public function __construct()
    {
        $this->response = [
            "err" => true,
            "msg" => "Request Not Processed",
            "data" => [],
            "code" => 400,
        ];
    } 

}
