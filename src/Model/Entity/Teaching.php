<?php
namespace App\Model\Entity;

class Teaching
	extends AppEntity
{

	protected $_compact =
		[ 'student', 'teacher', 'skill', 'xp', 'started', 'updated' ];

	protected $_virtual = [ 'student', 'teacher', 'started', 'updated' ];

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
		$a = ($this->teacher ?: $fallback)->getUrl();
		$b = ($this->student ?: $fallback)->getUrl();
		return $a . '/students' . substr($b, 11);
	}
}
