<?php

namespace app\controllers;

use app\services\CommentService;
use Yii;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

class CommentController extends Controller
{
    private $commentService;

    public function __construct($id, $module, CommentService $commentService, $config = [])
    {
        $this->commentService = $commentService;
        parent::__construct($id, $module, $config);
    }

    public function actionCreate()
    {
        $data = Yii::$app->request->bodyParams;
        return $this->commentService->addComment($data);
    }

    public function actionUpdate($materialId, $userId)
    {
        $data = Yii::$app->request->bodyParams;
        return $this->commentService->updateComment($materialId, $userId, $data);
    }

    public function actionDelete($materialId, $userId)
    {
        $this->commentService->deleteComment($materialId, $userId);
    }

    public function actionIndex($materialId)
    {
        return $this->commentService->getCommentsByMaterial($materialId);
    }
}
