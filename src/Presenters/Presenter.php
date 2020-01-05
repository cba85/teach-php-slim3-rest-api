<?php

namespace App\Presenters;

class Presenter
{
    protected $data;

    public function __construct($data = [])
    {
        $this->data = (object) $data;
    }

    public function present()
    {
        return json_encode($this->format());
    }
}