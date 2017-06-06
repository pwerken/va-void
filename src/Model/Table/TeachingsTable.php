<?php
namespace App\Model\Table;

class TeachingsTable
	extends AppTable
{

	protected $_contain = ['Student','Teacher','Skills','Started','Updated'];

	public function initialize(array $config)
	{
		$this->table('teachings');
		$this->primaryKey('student_id');
		$this->addBehavior('Timestamp');
		$this->belongsTo('Teacher', ['className' => 'Characters'])
			->setForeignKey('teacher_id')->setProperty('teacher');
		$this->belongsTo('Skills');
		$this->belongsTo('Student', ['className' => 'Characters'])
			->setForeignKey('student_id')->setProperty('student');
		$this->belongsTo('Started', ['className' => 'Events'])
			->setForeignKey('started_id')->setProperty('started_object');
		$this->belongsTo('Updated', ['className' => 'Events'])
			->setForeignKey('updated_id')->setProperty('updated_object');
	}

	public function orderBy()
	{
		return	[ 'student_id' => 'ASC' ];
	}

}
