<?php
namespace App\Model\Entity;

class Teaching
	extends AppEntity
{

	protected $_hidden = [ 'student_id', 'teacher_id', 'skill_id'
		, 'started_id', 'started_object', 'updated_id', 'updated_object' ];

	protected $_virtual = [ 'student', 'teacher', 'started', 'updated' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['student', 'teacher', 'skill', 'xp', 'started', 'updated']);
	}

	protected function _getStarted()
	{
		return $this->started_object->name;
	}

	protected function _getUpdated()
	{
		return $this->updated_object->name;
	}

	public function getUrl($fallback = NULL)
	{
		$student = $this->student ?: $fallback;
		return $student->getUrl() . '/teacher';
	}

	public function progress()
	{
		$txp = $xp = 0;
		for($i = $this->xp * 2; $i >= 6; $i -= 6) {
			$xp += 2;
			$txp += 2;
		}
		for( ; $i >= 2; $i -= 2) {
			$xp += 1;
		}
		$txp += $i;
		return [$txp, $xp];
	}
}
