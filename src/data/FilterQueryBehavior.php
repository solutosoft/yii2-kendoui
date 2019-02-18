<?php

namespace soluto\kendoui\data;

use yii\base\Behavior;
use yii\data\DataFilter;
use yii\helpers\ArrayHelper;
use yii\base\DynamicModel;
use yii\data\ActiveDataFilter;
use yii\db\Expression;


class FilterQueryBehavior extends Behavior
{

     /**
     * @var array keywords or expressions that could be used in a filter.
     * Array keys are the expressions used in raw filter value obtained from user request.
     * Array values are internal build keys used in this class methods.
     */
    private $_filterOperators = [
        'and' => 'AND',
        'or' => 'OR',
        'not' => 'NOT',
        'lt' => '<',
        'gt' => '>',
        'lte' => '<=',
        'gte' => '>=',
        'eq' => '=',
        'neq' => '!=',
        'in' => 'IN',
        'nin' => 'NOT IN',
        'contains' => 'LIKE',
        'doesnotcontain' => 'NOT LIKE',
        'startswith' => 'LIKE',
        'endswith' => 'LIKE',
        'isnull' => '=',
        'isempty' => '=',
        'isnotempty' => '!='
    ];

    public function filter($data)
    {
        $condition = $this->buildCondition($data);
        $sort = ArrayHelper::getValue($data, 'sort', []);

        foreach ($sort as $info) {
            $order = $this->normalizeFieldName($info['field']) . ' ' . $info['dir'];
            $this->owner->addOrderBy($order);
        }

        return $this->addFilterCondition($condition);
    }

     /**
     * Adds given filter condition to the owner query.
     * @param array $condition filter condition.
     * @return \yii\db\ActiveQueryInterface|static owner query instance.
     */
    protected function addFilterCondition($condition)
    {
        if (method_exists($this->owner, 'andOnCondition')) {
            return $this->owner->andOnCondition($condition);
        }
        return $this->owner->andWhere($condition);
    }

    /**
     * Adds table alias for field name
     * @param string $field
     * @return array
     */
    protected function normalizeFieldName($field)
    {
        if (method_exists($this->owner, 'getTablesUsedInFrom')) {
            $fromTables = $this->owner->getTablesUsedInFrom();
            $alias = array_keys($fromTables)[0];

            if (strpos($field, '[[') === false) {
                $field = '[[' . $field . ']]';
            }

            if (strpos($field, '.') === false) {
                return $alias . '.' . $field;
            }
        }

        return $field;
    }

    /**
     * Builds the condition based on filter rules
     * @param array $data
     * @return array
     */
    protected function buildCondition($data)
    {
        $result = [];
        $logic = ArrayHelper::getValue($data, 'logic', 'AND');
        $filters = ArrayHelper::getValue($data, 'filters', []);

        foreach ($filters as $item) {
           if (isset($item['filters'])) {
               $result[] = $this->buildCondition($item);
           } else {
               $field = ArrayHelper::getValue($item, 'field');
               $operator = ArrayHelper::getValue($item, 'operator');
               $value = ArrayHelper::getValue($item, 'value');

               switch ($operator) {
                    case 'startswith':
                        $value = $this->formatLikeValue("%{$value}");
                        break;

                    case 'endswith':
                        $value = $this->formatLikeValue("{$value}%");
                        break;

                    case 'contains':
                        $value = $this->formatLikeValue("%{$value}%");
                        break;
               }

               $condition = [
                   $this->_filterOperators[$operator],
                   $this->normalizeFieldName($field),
                   $value
                ];

               $result = empty($result) ? $condition : [$logic, $result, $condition];
           }
        }

        return $result;
    }

    /**
     * Replaces space character by '%'
     * @param string $value
     * @return \yii\db\Expression
     */
    private function formatLikeValue($value)
    {
        return new Expression(preg_replace('/\s+/', '%', "'$value'"));
    }

}
