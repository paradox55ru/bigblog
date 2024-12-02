<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class User
 * @package app\models
 *
 * @property int $id
 * @property string $name
 * @property string $login
 * @property string|null $avatar
 * @property string $email
 * @property string|null $phone
 * @property string|null $website
 */
class User extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'users';
    }

    public function rules(): array
    {
        return [
            [['name', 'login', 'email'], 'required'],
            [['avatar', 'phone', 'website'], 'string'],
            ['email', 'email'],
            ['login', 'unique'],
        ];
    }
}