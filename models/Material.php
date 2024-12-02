<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Material
 * @package app\models
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int|null $blog_id
 */
class Material extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'materials';
    }

    public function rules(): array
    {
        return [
            [['title', 'content'], 'required'],
            [['blog_id'], 'integer'],
        ];
    }
}