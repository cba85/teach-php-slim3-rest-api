<?php

namespace App\Controllers;

use App\Controllers\Controller;
use PDO;
use App\Presenters\ArticlePresenter;
use App\Presenters\ErrorPresenter;

class ArticleController extends Controller
{
    protected $fillable = ['title', 'body'];

    public function get($request, $response, $args)
    {
        $article = $this->getArticleById($args['id']);

        if (!$article) {
            return $response->withStatus(404);
        }

        return $this->response($response, (new ArticlePresenter($article))->present(), 200);
    }

    public function post($request, $response, $args)
    {
        $body = $request->getParsedBody();

        if (!isset($body['title'], $body['body'])) {
            return $this->response($response, (new ErrorPresenter(['message' => 'Title and body are required']))->present(), 400);
        }

        $create = $this->container->get('db')->prepare("
            INSERT INTO articles (title, body) VALUES (:title, :body)
        ");
        $create->execute([
            'title' => $body['title'],
            'body' => $body['body'],
        ]);

        $article = $this->getArticleById($this->container->get('db')->lastInsertId());

        return $this->response($response, (new ArticlePresenter($article))->present(), 200);
    }

    public function put($request, $response, $args)
    {
        $body = $request->getParsedBody();
        $toFill = array_filter(array_map(function($column) {
            if (in_array($column, $this->fillable)) {
                return "{$column} = :{$column}";
            }
        }, array_keys($body)));

        if (empty($toFill)) {
            return $this->response($response, (new ErrorPresenter(['message' => 'No columns selected to update article.']))->present(), 400);
        }

        $update = $this->container->get('db')->prepare("
            UPDATE articles SET " . implode(', ', $toFill) . " WHERE id = :id
        ");
        $update->execute(array_merge(['id' => $args['id']], $body));

        if (!$update) {
            return $this->response($response, (new ErrorPresenter(['message' => 'Article was not updated.']))->present(), 400);
        }

        $article = $this->getArticleById($args['id']);

        if (!$article) {
            return $this->response($response, null, 404);
        }

        return $this->response($response, (new ArticlePresenter($article))->present(), 200);
    }

    public function delete($request, $response, $args)
    {
        $delete = $this->container->get('db')->prepare("
            DELETE FROM articles WHERE id = :id
        ");
        $delete->execute(['id' => $args['id']]);

        if (!$delete) {
            return $this->response($response, (new ErrorPresenter(['message' => 'Article was not deleted.']))->present(), 500);
        }

        if (!$delete->rowCount()) {
            return $this->response($response, null, 404);
        }

        return $this->response($response, null, 200);
    }

    protected function getArticleById($id)
    {
        $article = $this->container->get('db')->prepare("
            SELECT * FROM articles WHERE id = :id
        ");
        $article->execute(['id' => $id]);
        return $article->fetch(PDO::FETCH_OBJ);
    }
}