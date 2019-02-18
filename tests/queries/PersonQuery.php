<?php

namespace soluto\kendoui\tests\queries;

use yii\db\ActiveQuery;
use soluto\kendoui\data\FilterQueryBehavior;

class PersonQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'filter' => [
                'class' => FilterQueryBehavior::class
            ]
        ];
    }
}
