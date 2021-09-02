<?php

use yii\db\Migration;

/**
 * Class m210902_170000_add_status_to_requests_table
 */
class m210902_170000_add_status_to_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%requests}}',
            'status',
            $this->boolean()->notNull()->defaultValue(false)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%requests}}', 'status');
    }
}
