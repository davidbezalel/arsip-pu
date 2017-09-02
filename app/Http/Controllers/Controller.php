<?php

namespace App\Http\Controllers;

use App\Http\ResponseData;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use App\Helper\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * @var array variable will be sent to end-application
     */
    public $data;
    protected $response_json;

    const SERVER_ERROR = "Something wrong with server. Please contact our admin";

    public function __construct()
    {
        $this->data = array();
        $this->response_json = new ResponseData();
    }


    function isPost()
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
            return true;
        }
        return false;
    }

    function __json($data = null)
    {
        $this->response_json = ($data) ?: $this->response_json;
        return response()->json($this->response_json);
    }

    public function getServerError()
    {
        return self::SERVER_ERROR;
    }
}
