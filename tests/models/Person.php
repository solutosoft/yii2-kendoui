<?php

namespace soluto\kendoui\tests\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use soluto\kendoui\tests\queries\PersonQuery;

/**
 * @property string $firstName
 * @property string $lastName
 * @property string $birthDate
 * @property double $salary
 * @property integer $profile_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $tenant_id
 * @property Profile[] $profile
 */
class Person extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'profile_id']);
    }

    public static function find()
    {
        return new PersonQuery(get_called_class());
    }

}
