<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Blog
 * @package app\models
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $company_id
 */
class Blog extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'blogs';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'company_id'], 'integer'],
        ];
    }
}