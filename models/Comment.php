<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Comment
 * @package app\models
 *
 * @property int $material_id
 * @property int $user_id
 * @property string $content
 */
class Comment extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'comments';
    }

    public function rules(): array
    {
        return [
            [['material_id', 'user_id', 'content'], 'required'],
            [['material_id', 'user_id'], 'integer'],
        ];
    }
}