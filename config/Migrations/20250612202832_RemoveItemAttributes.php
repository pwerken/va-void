<?php
declare(strict_types=1);

use App\Migrations\Migration;

class RemoveItemAttributes extends Migration
{
    public function up(): void
    {
        $this->backupData();

        $this->table('attributes_items')->drop()->update();
        $this->table('attributes')->drop()->update();
    }

    public function down(): void
    {
        $this->table('attributes')
            ->addColumnString('name', ['null' => true])
            ->addColumnString('category', ['null' => true])
            ->addColumnString('code', ['limit' => 2])
            ->addIndex(['category', 'id' ])
            ->create();

        $this->relationTable('attributes_items', ['attribute_id', 'item_id'])
            ->addColumnDateTime('modified')
            ->addColumnInteger('modifier_id', ['null' => true])
            ->addForeignKey(
                'attribute_id',
                'attributes',
                'id',
                ['update' => 'CASCADE', 'delete' => 'CASCADE'],
            )
            ->addForeignKey(
                'item_id',
                'items',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->create();
    }

    public function backupData(): void
    {
        $history = $this->table('history');
        $insertAll = [];

        $rows = $this->getSelectBuilder()
            ->select('*')
            ->from('attributes_items')
            ->order(['attribute_id' => 'ASC', 'item_id' => 'ASC'])
            ->execute();
        foreach ($rows as $row) {
            $current = [
                'entity' => 'AttributesItem',
                'key1' => $row['attribute_id'],
                'key2' => $row['item_id'],
                'data' => '{}',
                'modified' => $row['modified'],
                'modifier_id' => $row['modifier_id'],
            ];

            $delete = $current;
            $delete['data'] = null;
            $delete['modified'] = $this->now();
            $delete['modifier_id'] = -2;

            $insertAll[] = $current;
            $insertAll[] = $delete;
        }

        $rows = $this->getSelectBuilder()
            ->select('*')
            ->from('attributes')
            ->order(['id' => 'ASC'])
            ->execute();

        foreach ($rows as $row) {
            $data = [
                'name' => $row['name'],
                'category' => $row['category'],
                'code' => $row['code'],
            ];

            $attribute = [
                'entity' => 'Attribute',
                'key1' => $row['id'],
                'key2' => null,
                'data' => json_encode($data),
                'modified' => $this->now(),
                'modifier_id' => -2,
            ];

            $insertAll[] = $attribute;
        }

        $history
            ->insert($insertAll)
            ->saveData();
    }
}
