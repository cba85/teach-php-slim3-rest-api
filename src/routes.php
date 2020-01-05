<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/articles/{id}', '\App\Controllers\ArticleController:get');
$app->post('/articles', '\App\Controllers\ArticleController:post');
$app->put('/articles/{id}', '\App\Controllers\ArticleController:put');
$app->delete('/articles/[{id}]', '\App\Controllers\ArticleController:delete');