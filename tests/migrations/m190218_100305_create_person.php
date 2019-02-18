<?php

use yii\db\Migration;

class m190218_100305_create_person extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('person', [
            'id' => $this->primaryKey(),
            'firstName' => $this->string()->notNull(),
            'lastName' => $this->string()->notNull(),
            'birthDate' => $this->dateTime(),
            'profile_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'salary' => $this->decimal(18,2)
        ]);

        $this->createTable('profile', [
            'id' => $this->primaryKey(),
            'number' => $this->string()->notNull(),
            'description' => $this->string()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('profile');
        $this->dropTable('person');
    }
}
