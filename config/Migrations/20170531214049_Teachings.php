<?php
declare(strict_types=1);

use App\Migrations\AppMigration;

class Teachings extends AppMigration
{
    public function up(): void
    {
        $this->table('teachings', ['id' => false, 'primary_key' => ['student_id']])
            ->addColumn('student_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('teacher_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('skill_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('started_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('updated_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('xp', 'decimal', [
                'default' => '0.0',
                'null' => false,
                'precision' => 3,
                'scale' => 1,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'skill_id',
                ]
            )
            ->addIndex(
                [
                    'started_id',
                ]
            )
            ->addIndex(
                [
                    'teacher_id',
                ]
            )
            ->addIndex(
                [
                    'updated_id',
                ]
            )
            ->create();

        $this->table('teachings')
            ->addForeignKey(
                'skill_id',
                'skills',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'started_id',
                'events',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'student_id',
                'characters',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'teacher_id',
                'characters',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'updated_id',
                'events',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();
    }

    public function down(): void
    {
        $this->table('teachings')
            ->dropForeignKey(
                'skill_id'
            )
            ->dropForeignKey(
                'started_id'
            )
            ->dropForeignKey(
                'student_id'
            )
            ->dropForeignKey(
                'teacher_id'
            )
            ->dropForeignKey(
                'updated_id'
            );

        $this->dropTable('teachings');
    }
}
