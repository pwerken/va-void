<?php
namespace App\Model\Table;

class TeachingsTable
	extends AppTable
{

	protected $_contain = ['Student','Teacher','Skills','Started','Update'];

	public function initialize(array $config)
	{
		$this->table('teachings');
		$this->primaryKey('student_id');
		$this->addBehavior('Timestamp');
		$this->belongsTo('Teacher',
			[ 'className' => 'Characters', 'foreignKey' => 'teacher_id'
			, 'propertyName' => 'teacher']);
		$this->belongsTo('Skills');
		$this->belongsTo('Student',
			[ 'className' => 'Characters', 'foreignKey' => 'student_id'
			, 'propertyName' => 'student' ]);
		$this->belongsTo('Started',
			[ 'className' => 'Events', 'foreignKey' => 'started_id'
			, 'propertyName' => 'started_object']);
		$this->belongsTo('Updated',
			[ 'className' => 'Events', 'foreignKey' => 'updated_id'
			, 'propertyName' => 'updated_object']);
	}

	public function orderBy()
	{
		return	[ 'student_id' => 'ASC' ];
	}

}
