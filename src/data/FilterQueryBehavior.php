<?php

namespace soluto\kendoui\data;

use Yii;
use yii\base\Behavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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

    /**
     * Filters query from $data configuration.
     * @param array $data
     * @return \yii\db\ActiveQueryInterface|static
     */
    public function filter($data)
    {
        $condition = $this->buildCondition($data);
        return $this->addFilterCondition($condition);
    }

    /**
     * Sorts query from $sort configuration
     * @param array $sort
     * @return \yii\db\ActiveQueryInterface|static
     */
    public function sort($sort)
    {
        foreach ((array)$sort as $info) {
            $order = $this->normalizeFieldName($info['field']) . ' ' . $info['dir'];
            $this->owner->addOrderBy($order);
        }

        return $this->owner;
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
     * @param boolean $lower
     * @return array
     */
    protected function normalizeFieldName($field, $lower = false)
    {
        if (method_exists($this->owner, 'getTablesUsedInFrom')) {
            $fromTables = $this->owner->getTablesUsedInFrom();
            $alias = array_keys($fromTables)[0];

            if (strpos($field, '[[') === false) {
                $field = '[[' . $field . ']]';
            }

            if (strpos($field, '.') === false) {
                $field = $alias . '.' . $field;
            }
        }

        return $lower ? "LOWER($field)" : $field;
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
               $lower = $this->getIsLike($operator);

               $condition = [
                   $this->_filterOperators[$operator],
                   $this->normalizeFieldName($field, $lower),
                   $this->formatValue($value, $operator)
                ];

               $result = empty($result) ? $condition : [$logic, $result, $condition];
           }
        }

        return $result;
    }

    /**
     * Format value used in condition
     * @param string $value
     * @param string $operator
     * @return \yii\db\Expression
     */
    private function formatValue($value, $operator)
    {
        if (!$this->getIsLike($operator)) {
            return $value;
        }

        switch ($operator) {
            case 'startswith':
                $value = $value . "%";
                break;

            case 'endswith':
                $value = "%" . $value;
                break;

            case 'contains':
                $value = "%". $value ."%";
                break;
       }

       $value = Yii::$app->db->quoteValue(preg_replace('/\s+/', '%', $value));
       return new Expression("LOWER($value)");
    }

    /**
     * @param string $operator
     * @return boolean
     */
    private function getIsLike($operator)
    {
        return in_array($operator, [
            'startswith',
            'endswith',
            'contains',
            'doesnotcontain'
        ]);
    }

}
