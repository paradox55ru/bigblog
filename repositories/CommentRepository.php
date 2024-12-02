<?php

namespace app\repositories;

use app\models\Comment;
use Yii;

class CommentRepository
{
    public function find($materialId, $userId): ?Comment
    {
        return Comment::findOne(['material_id' => $materialId, 'user_id' => $userId]);
    }

    public function save(Comment $comment): bool
    {
        return $comment->save();
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    public function findAllByMaterial($materialId)
    {
        return Comment::findAll(['material_id' => $materialId]);
    }

    public function batchSave(array $comments): bool
    {
        $transaction = Comment::getDb()->beginTransaction();
        try {
            foreach ($comments as $comment) {
                $comment->save();
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public function findCommentsWithPagination($materialId, $offset, $limit)
    {
        return Comment::find()
            ->where(['material_id' => $materialId])
            ->offset($offset)
            ->limit($limit)
            ->all();
    }
}
