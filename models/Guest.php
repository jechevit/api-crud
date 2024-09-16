<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $phone
 * @property string $email
 * @property string $country_code
 * @property int $created_at
 * @property int $updated_at
 */
class Guest extends ActiveRecord
{
    /**
     * @param string $name
     * @param string $lastName
     * @param string $phone
     * @param string $email
     * @return Guest
     */
    public static function create(
        string $name,
        string $lastName,
        string $phone,
        string $email,
        string $countryCode
    ): Guest
    {
        $model = new self();
        $model->name = $name;
        $model->last_name = $lastName;
        $model->phone = $phone;
        $model->email = $email;
        $model->country_code = $countryCode;

        return $model;
    }

    /**
     * @param string $name
     * @param string $lastName
     * @param string $phone
     * @param string $email
     * @return void
     */
    public function edit(string $name, string $lastName, string $phone, string $email, string $countryCode): void
    {
        $this->name = $name;
        $this->last_name = $lastName;
        $this->phone = $phone;
        $this->email = $email;
        $this->country_code = $countryCode;
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function tableName(): string
    {
        return '{{%guests}}';
    }
}