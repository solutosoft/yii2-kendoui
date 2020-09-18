<?php

namespace soluto\kendoui\tests\data;

use soluto\kendoui\tests\TestCase;
use soluto\kendoui\tests\models\Person;

class FilterQueryBehaviorTest extends TestCase
{

    public function setUp()
    {
        $this->mockApplication('web');
    }

    public function testFilter()
    {
        foreach ($this->rulesProvider() as $rule) {
           $query = Person::find()
                ->filter($rule['filter']);

            if (isset($rule['sort'])) {
                $query->sort($rule['sort']);
            }

            $sql = $query
                ->createCommand()
                ->getRawSql();

           $this->assertContains($rule['expected'], $sql);
       }
    }

    /**
     * The filters rules to be tested
     * @return array
     */
    private function rulesProvider()
    {
        return [
            [
                'expected' => "(`person`.`email` = 'joe@test.com') OR (`person`.`email` = 'bruce@test.com')",
                'filter' => [
                    'logic' => 'or',
                    'filters' => [
                        ['field' => 'email', 'operator' => 'eq', 'value' => 'joe@test.com'],
                        ['field' => 'email', 'operator' => 'eq', 'value' => 'bruce@test.com']
                    ],
                ],
            ],[
                'expected' => "(LOWER(`person`.`firstName`) LIKE LOWER('%kurt%other%name%') ESCAPE '\') AND (`person`.`lastName` = 'cobain') AND (`person`.`id` = 10) ORDER BY `person`.`firstName`, `person`.`lastName` DESC",
                'filter' => [
                    'logic' => 'and',
                    'filters' =>[
                        ['field' => 'firstName', 'operator' => 'contains', 'value' => 'kurt other name'],
                        ['field' => 'lastName', 'operator' => 'eq', 'value' => 'cobain'],
                        [
                            'logic' => 'and',
                            'filters' => [
                                ['field' => 'id', 'operator' => 'eq', 'value' => 10]
                            ]
                        ]
                    ],
                ],
                'sort' => [
                    ['field' => 'firstName', 'dir' => 'asc'],
                    ['field' => 'lastName', 'dir' => 'desc'],
                ]
            ]/*,[
                'expected' => '(`email` = ?)',
                'filter' => [
                    'logic' => 'or',
                    'filters' => [
                        ['field' => 'email', 'operator' => '=', 'value' => 'joe'],
                        ['field' => 'salary', 'operator' => '=', 'value' => 1000] //invalid
                    ]
                ],
            ]*/
        ];
    }

}
