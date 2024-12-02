<?php

namespace app\services;

use app\models\Comment;
use app\repositories\CommentRepository;
use Yii;
use yii\web\BadRequestHttpException;

class CommentService
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function addComment($data): Comment
    {
        $comment = new Comment();
        $comment->load($data, '');

        if (!$comment->save()) {
            throw new BadRequestHttpException('Не удалось сохранить комментарий.');
        }

        // Отправка события в RabbitMQ
        Yii::$app->rabbitMQ->publish('new_comment', $comment->id);

        // Обновление кэша Redis
        $this->updateCache($comment->material_id);

        return $comment;
    }

    public function updateComment($materialId, $userId, $data): Comment
    {
        $comment = $this->commentRepository->find($materialId, $userId);

        if (!$comment) {
            throw new BadRequestHttpException('Комментарий не найден.');
        }

        $comment->load($data, '');

        if (!$comment->save()) {
            throw new BadRequestHttpException('Не удалось обновить комментарий.');
        }

        // Обновление кэша Redis
        $this->updateCache($materialId);

        return $comment;
    }

    public function deleteComment($materialId, $userId): void
    {
        $comment = $this->commentRepository->find($materialId, $userId);

        if (!$comment) {
            throw new BadRequestHttpException('Комментарий не найден.');
        }

        $this->commentRepository->delete($comment);

        // Обновление кэша Redis
        $this->updateCache($materialId);
    }

    public function getCommentsByMaterial($materialId, $page = 1, $pageSize = 20)
    {
        $offset = ($page - 1) * $pageSize;
        $cacheKey = "comments_material_{$materialId}_page_{$page}";
        $comments = Yii::$app->cache->get($cacheKey);

        if ($comments === false) {
            $comments = $this->commentRepository->findCommentsWithPagination($materialId, $offset, $pageSize);
            Yii::$app->cache->set($cacheKey, $comments, 3600); // Кэшируем на 1 час
        }

        return $comments;
    }

    private function updateCache($materialId)
    {
        // Удаляем кэш при изменении комментария
        Yii::$app->cache->delete("comments_material_{$materialId}");
    }
}
