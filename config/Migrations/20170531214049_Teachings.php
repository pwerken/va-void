<?php
declare(strict_types=1);

use App\Migrations\Migration;

class Teachings extends Migration
{
    public function up(): void
    {
        $this->table('teachings', ['id' => false, 'primary_key' => ['student_id']])
            ->addColumnInteger('student_id')
            ->addColumnInteger('teacher_id')
            ->addColumnInteger('skill_id')
            ->addColumnInteger('started_id')
            ->addColumnInteger('updated_id')
            ->addColumn('xp', 'decimal', [
                'default' => '0.0',
                'null' => false,
                'precision' => 3,
                'scale' => 1,
            ])
            ->addColumnDateTime('modified')
            ->addIndex(['skill_id'])
            ->addIndex(['started_id'])
            ->addIndex(['teacher_id'])
            ->addIndex(['updated_id'])
            ->create();

        $this->table('teachings')
            ->addForeignKey(
                'skill_id',
                'skills',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'started_id',
                'events',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'student_id',
                'characters',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'teacher_id',
                'characters',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'updated_id',
                'events',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->update();
    }

    public function down(): void
    {
        $this->table('teachings')
            ->dropForeignKey('skill_id')
            ->dropForeignKey('started_id')
            ->dropForeignKey('student_id')
            ->dropForeignKey('teacher_id')
            ->dropForeignKey('updated_id')
            ->update();

        $this->table('teachings')->drop()->update();
    }
}
