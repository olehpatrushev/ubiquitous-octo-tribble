<?php

use yii\db\Migration;

/**
 * Class m210419_120702_doc_generate
 */
class m210419_120702_doc_generate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('doc', [
            'id' => $this->primaryKey(),
            'category' => $this->string()->notNull(),
            'firstname' => $this->string()->notNull(),
            'lastname' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'gender' => "ENUM('male', 'female') NOT NULL",
            'birthDate' => $this->date()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('doc');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210419_120702_doc_generate cannot be reverted.\n";

        return false;
    }
    */
}
