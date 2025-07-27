<?php

namespace App\Utils;

class Response {

    private bool $status;
    private string $message;
    private mixed $data;

    private function __construct(bool $status, $message, $data)
    {
        $this->status = $status;        
        $this->message = $message;        
        $this->data = $data;        
    }

    private function __clone(){}

    public static function getResponse(bool $status, $message = '', $data = null) {
        return new Response($status, $message, $data);
    }

    public function getStatus() {
        return $this->status; 
    }

    public function getMessage() {
        return $this->message; 
    }

    public function getData() {
        return $this->data; 
    }

}