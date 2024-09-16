<?php

namespace app\forms;

use app\models\Guest;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use yii\base\Model;

class GuestForm extends Model
{
    public string  $name = '';
    public string  $last_name = '';
    public string  $email = '';
    public string $phone = '';
    public string $country_code = '';

    private int|null $_id = null;

    public function __construct(Guest $guest = null, $config = [])
    {
        if ($guest) {
            $this->_id = $guest->id;
            $this->name = $guest->name;
            $this->last_name = $guest->last_name;
            $this->email = $guest->email;
            $this->phone = $guest->phone;
            $this->country_code = $guest->country_code;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'last_name', 'email', 'phone'], 'required'],
            [['name', 'last_name', 'country_code'], 'string', 'max' => 255],
            [['email'], 'email'],
            [
                ['email'], 'unique',
                'targetClass' => Guest::class, 'targetAttribute' => ['email' => 'email'],
                'filter' => function ($query) {
                    if (!empty($this->_id)) {
                        $query->andWhere(['<>', 'id', $this->_id]);
                    }
                },
            ],
            [
                ['phone'], 'unique',
                'targetClass' => Guest::class, 'targetAttribute' => ['phone' => 'phone'],
                'filter' => function ($query) {
                    if (!empty($this->_id)) {
                        $query->andWhere(['<>', 'id', $this->_id]);
                    }
                },
            ],
            [['phone'], 'validatePhone'],
        ];
    }

    public function validatePhone($attribute, $params)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $phoneNumber = $phoneUtil->parse($this->phone, null);

            if (!$phoneUtil->isValidNumber($phoneNumber)) {
                $this->addError($attribute, 'Номер телефона недействителен.');
            } else {
                $this->country_code = $phoneUtil->getRegionCodeForNumber($phoneNumber);
            }
        } catch (NumberParseException $e) {
            $this->addError($attribute, 'Ошибка при парсинге номера телефона.');
        }
    }

    public function formName(): string
    {
        return '';
    }

    private function getCode()
    {
        $code = '';

        return $code;
    }

    private function getCountryByCode(string $countryCode)
    {
        return '';
    }
}