<?php

namespace App\Presenters;

class ErrorPresenter extends Presenter
{
    public function format()
    {
        return [
            'success' => false,
            'error' => [
                'message' => $this->data->message,
            ]
        ];
    }
}