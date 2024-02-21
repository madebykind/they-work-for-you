<?php

namespace InLineStudio\TheyWorkForYou\models;

use Craft;
use craft\base\Model;
use yii\validators\Validator;

/**
 * TheyWorkForYou settings
 */
class Settings extends Model
{
    public $apiUrl;
    public $apiKey;

    /**
     * Settings constructor.
     */
    public function __construct(array $config = [])
    {
        $this->apiUrl = '';
        $this->apiKey = '';
    }

    /**
     * Take a look here for default validators:
     * 
     * @see(yii\validators\Validator::$builtInValidators)
     * 
     * @inheritDoc
     * 
     * @return array<array<string>,Validator>
     */
    public function defineRules(): array
    {
        return [
            [['apiUrl', 'apiKey'], 'required'],
            [['apiUrl', 'apiKey'], 'string'],
            [['apiUrl'], 'url'],
            // ...
        ];
    }
}
