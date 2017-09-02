<?php

namespace App\Http;


class ResponseData
{
    public $status;
    public $data;
    public $message;

    public function __construct()
    {
        $this->status = false;
        $this->data = new \stdClass();
        $this->message = 'Oops, something wrong. Please try again.';
    }
}