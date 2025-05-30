<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Log\Engine\FileLog as CakeFileLog;

class FileLog extends CakeFileLog
{
    public function logrotate(): void
    {
        $originalSize = $this->_size;
        $this->_size = 1;
        $this->log('info', 'logrotate');
        $this->_size = $originalSize;
    }
}
