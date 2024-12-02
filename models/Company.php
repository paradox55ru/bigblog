<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Company
 * @package app\models
 *
 * @property int $id
 * @property string $title
 * @property string|null $website
 * @property string|null $address
 */
class Company extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'companies';
    }

    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['website', 'address'], 'string'],
        ];
    }
}