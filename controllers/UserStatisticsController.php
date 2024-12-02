<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\rest\Controller;

class UserStatisticsController extends Controller
{
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $search = $request->get('search', '');
        $page = (int)$request->get('page', 1);
        $limit = (int)$request->get('limit', 10);
        $sort = $request->get('sort', 'comments_count');
        $order = $request->get('order', 'asc');

        // Определение ключа для кэша
        $cacheKey = "user_statistics:search:$search:page:$page:limit:$limit:sort:$sort:order:$order";

        // Проверка кэша
        $cachedData = Yii::$app->cache->get($cacheKey);
        if ($cachedData !== false) {
            return $this->asJson($cachedData);
        }

        // Если данные не в кэше, делаем запрос к базе данных
        $offset = ($page - 1) * $limit;

        $query = User::find()
            ->select(['u.id', 'u.name',
                      'COUNT(DISTINCT c.id) AS comments_count',
                      'COUNT(DISTINCT s.user_id) AS followers_count',
                      'COUNT(DISTINCT b.id) AS posts_count'])
            ->alias('u')
            ->leftJoin('comments c', 'u.id = c.user_id')
            ->leftJoin('subscriptions s', 'u.id = s.user_id')
            ->leftJoin('blogs b', 'u.id = b.user_id OR u.id = b.company_id')
            ->where(['like', 'u.name', "%$search%", false])
            ->groupBy('u.id')
            ->orderBy([$sort => $order === 'asc' ? SORT_ASC : SORT_DESC])
            ->limit($limit)
            ->offset($offset);

        // Получение данных
        $users = $query->all();

        // Кэшируем результаты на 60 секунд
        Yii::$app->cache->set($cacheKey, $users, 60);

        // Возврат данных в формате JSON
        return $this->asJson($users);
    }
}
