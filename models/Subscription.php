<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Subscription
 * @package app\models
 *
 * @property int $user_id
 * @property int $blog_id
 */
class Subscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'subscriptions';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'blog_id'], 'required'],
            [['user_id', 'blog_id'], 'integer'],
            [['user_id', 'blog_id'], 'unique', 'targetAttribute' => ['user_id', 'blog_id']],
        ];
    }
}