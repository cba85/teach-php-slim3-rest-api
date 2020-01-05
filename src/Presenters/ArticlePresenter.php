<?php

namespace App\Presenters;

class ArticlePresenter extends Presenter
{
    public function format()
    {
        return [
            'title' => $this->data->title,
            'article' => $this->data->body
        ];
    }
}