<?php

namespace unclead\widgets\examples\models;

use yii\base\Model;
use yii\validators\EmailValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;

/**
 * Class ExampleModel
 * @package unclead\widgets\examples\actions
 */
class ExampleModel extends Model
{
    const TYPE_EMAIL = 'email';
    const TYPE_PHONE = 'phone';

    /**
     * @var array virtual attribute for keeping emails
     */
    public $emails;

    /**
     * @var
     */
    public $phones;

    /**
     * @var
     */
    public $schedule;

    public function init()
    {
        parent::init();
        $this->emails = [
            'test@test.com',
            'test2@test.com',
            'test3@test.com',
        ];

        $this->schedule = [
            [
                'day'       => 0,
                'user_id'   => 1,
                'priority'  => 1
            ],
            [
                'day'       => 0,
                'user_id'   => 2,
                'priority'  => 2
            ],
        ];
    }


    public function rules()
    {
        return [
            ['emails', 'validateEmails'],
            ['phones', 'validatePhones'],
            ['schedule', 'validateSchedule']
        ];
    }

    public function attributes()
    {
        return [
            'emails',
            'phones'
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['emails', 'phones', 'schedule']
        ];
    }

    /**
     * Phone number validation
     *
     * @param $attribute
     */
    public function validatePhones($attribute)
    {
        $items = $this->$attribute;

        if (!is_array($items)) {
            $items = [];
        }

        $multiple = true;
        if(!is_array($items)) {
            $multiple = false;
            $items = (array) $items;
        }

        foreach ($items as $index => $item) {
            $validator = new NumberValidator();
            $error = null;
            $validator->validate($item, $error);
            if (!empty($error)) {
                $key = $attribute . ($multiple ? '[' . $index . ']' : '');
                $this->addError($key, $error);
            }
        }
    }

    /**
     * Email validation.
     *
     * @param $attribute
     */
    public function validateEmails($attribute)
    {
        $items = $this->$attribute;

        if (!is_array($items)) {
            $items = [];
        }

        foreach ($items as $index => $item) {
            $validator = new EmailValidator();
            $error = null;
            $validator->validate($item, $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . ']';
                $this->addError($key, $error);
            }
        }
    }

    public function validateSchedule($attribute)
    {
        $requiredValidator = new RequiredValidator();

        foreach($this->$attribute as $index => $row) {
            $error = null;
            $requiredValidator->validate($row['priority'], $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][priority]';
                $this->addError($key, $error);
            }
        }
    }
}