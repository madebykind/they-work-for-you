<?php

namespace InLineStudio\TheyWorkForYou\models;

use yii\db\ActiveRecord;

class Contact extends ActiveRecord
{

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{twfy_contacts}}';
    }
}