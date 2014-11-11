<?php

namespace App;

class Log
{
	public static function debug() {
		ob_start();
		$args = func_get_args();
		foreach($args as $arg)
			var_dump($arg);

		$str = ob_get_contents();
		ob_end_clean();
		\Cake\Log\Log::debug(trim($str));
	}
}
