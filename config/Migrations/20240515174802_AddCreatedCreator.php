<?php
declare(strict_types=1);

use App\Migrations\Migration;

class AddCreatedCreator extends Migration
{
    public function up(): void
    {
        $this->table('items')
            ->addColumn(
                'created',
                'datetime',
                [ 'default' => null
                , 'limit' => null
                , 'null' => true
                , 'after' => 'deprecated',
                ],
            )
            ->addColumn(
                'creator_id',
                'integer',
                [ 'default' => null
                , 'limit' => null
                , 'length' => 11
                , 'null' => true
                , 'after' => 'created',
                ],
            )
            ->save();

        $this->table('conditions')
            ->addColumn(
                'created',
                'datetime',
                [ 'default' => null
                , 'limit' => null
                , 'null' => true
                , 'after' => 'deprecated',
                ],
            )
            ->addColumn(
                'creator_id',
                'integer',
                [ 'default' => null
                , 'limit' => null
                , 'length' => 11
                , 'null' => true
                , 'after' => 'created',
                ],
            )
            ->save();

        $this->table('powers')
            ->addColumn(
                'created',
                'datetime',
                [ 'default' => null
                , 'limit' => null
                , 'null' => true
                , 'after' => 'deprecated',
                ],
            )
            ->addColumn(
                'creator_id',
                'integer',
                [ 'default' => null
                , 'limit' => null
                , 'length' => 11
                , 'null' => true
                , 'after' => 'created',
                ],
            )
            ->save();

        // set initial created/creator_field based on modified/modifier_id
        $query = $this->getQueryBuilder('update')
                    ->update('items')
                    ->set(function ($exp) {
                        return $exp->equalFields('created', 'modified');
                    })
                    ->set(function ($exp) {
                        return $exp->equalFields('creator_id', 'modifier_id');
                    })
                    ->execute();
        $query = $this->getQueryBuilder('update')
                    ->update('conditions')
                    ->set(function ($exp) {
                        return $exp->equalFields('created', 'modified');
                    })
                    ->set(function ($exp) {
                        return $exp->equalFields('creator_id', 'modifier_id');
                    })
                    ->execute();
        $query = $this->getQueryBuilder('update')
                    ->update('powers')
                    ->set(function ($exp) {
                        return $exp->equalFields('created', 'modified');
                    })
                    ->set(function ($exp) {
                        return $exp->equalFields('creator_id', 'modifier_id');
                    })
                    ->execute();

        // try to improve on created/creator_id based on historical data
        $query = $this->getQueryBuilder('select')
                    ->select(function ($query) {
                        return ['min' => $query->func()->min('id')];
                    })
                    ->select(['entity', 'key1', 'modified', 'modifier_id'])
                    ->from('history')
                    ->group(['entity', 'key1', 'key2'])
                    ->having(function ($exp, $query) {
                        $or = $exp->or(['modified IS NOT' => null]);
                        $or->add(['modifier_id IS NOT' => null]);
                        $list = $exp->in('entity', ['item', 'condition', 'power']);

                        return $exp->and([$or, $list]);
                    });
        foreach ($query->execute() as $row) {
            $this->getQueryBuilder('update')
                ->update(strtolower($row['entity'] . 's'))
                ->set('created', $row['modified'])
                ->set('creator_id', $row['modifier_id'])
                ->where(['id' => $row['key1']])
                ->execute();
        }
    }

    public function down(): void
    {
        $this->table('conditions')
            ->removeColumn('created')
            ->removeColumn('creator_id')
            ->update();

        $this->table('powers')
            ->removeColumn('created')
            ->removeColumn('creator_id')
            ->update();

        $this->table('items')
            ->removeColumn('created')
            ->removeColumn('creator_id')
            ->update();
    }
}
