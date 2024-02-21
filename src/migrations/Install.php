<?php

namespace InLineStudio\TheyWorkForYou\migrations;

use Craft;
use craft\db\Migration;

/**
 * m240209_111248_add_contacts_table migration.
 */
class Install extends Migration
{
    public string $tableName = '{{%twfy_contacts}}';
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'forename' => $this->string()->defaultValue('')->notNull(),
            'surname' => $this->string()->defaultValue('')->notNull(),
            'display_as' => $this->string()->defaultValue('')->notNull(),
            'list_as' => $this->string()->defaultValue('')->notNull(),
            'party' => $this->string()->defaultValue('')->notNull(),
            'constituency' => $this->string()->defaultValue('')->notNull(),
            'email' => $this->string()->defaultValue(''),
            'address_1' => $this->string()->defaultValue(''),
            'address_2' => $this->string()->defaultValue(''),
            'postcode' => $this->string()->defaultValue(''),
            'uid' => $this->string()->defaultValue(''),
        ]);

        $this->createIndexWrapper($this->tableName, 'forename');
        $this->createIndexWrapper($this->tableName, 'surname');
        $this->createIndexWrapper($this->tableName, 'constituency');
        $this->createIndexWrapper($this->tableName, 'party');
        $this->createIndexWrapper($this->tableName, 'postcode');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropTableIfExists($this->tableName);
        return true;
    }

    public function createIndexWrapper(string $table, string $column, bool $unique = false): void
    {
        $this->createIndex(
            'idx_' . $column,
            $table,
            $column,
            $unique
        );
    }
}
