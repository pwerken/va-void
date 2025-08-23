<?php
declare(strict_types=1);

use App\Migrations\Migration;

class CharPlayerIdtoPlin extends Migration
{
    public function up(): void
    {
        $this->table('characters')
            ->renameColumn('player_id', 'plin')
            ->removeIndex(['plin'])
            ->removeIndex(['plin', 'chin'])
            ->addIndex(['plin'])
            ->addIndex(['plin', 'chin'])
            ->update();

        // also transform history data
        $query = $this->getSelectBuilder();
        $query->select('*')->from('history')
            ->where(['entity' => 'Character', 'data IS NOT' => null]);
        foreach ($query->execute() as $row) {
            $data = str_replace('"player_id":', '"plin":', $row['data']);
            $this->getUpdateBuilder()
                ->update('history')
                ->set(['data' => $data])
                ->where(['id' => $row['id']])
                ->execute();
        }
    }

    public function down(): void
    {
        $this->table('characters')
            ->renameColumn('plin', 'player_id')
            ->removeIndex(['player_id'])
            ->removeIndex(['player_id', 'chin'])
            ->addIndex(['player_id'])
            ->addIndex(['player_id', 'chin'])
            ->update();

        // revert history data
        $query = $this->getSelectBuilder();
        $query->select('*')->from('history')
            ->where(['entity' => 'Character', 'data IS NOT' => null]);
        foreach ($query->execute() as $row) {
            $data = str_replace('"plin":', '"player":', $row['data']);
            $this->getUpdateBuilder()
                ->update('history')
                ->set(['data' => $data])
                ->where(['id' => $row['id']])
                ->execute();
        }
    }
}
