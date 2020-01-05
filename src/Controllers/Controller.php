<?php

namespace App\Controllers;

class Controller
{
protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function response($response, $content, $httpStatus = 200)
    {
        $body = $response->getBody();
        $body->write($content);
        return $response->withStatus(200)->withBody($body);
    }
}