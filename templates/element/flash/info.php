<?php
declare(strict_types=1);

/**
 * @var \Cake\View\View $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="message" onclick="this.classList.add('hidden');"><?= $message ?></div>
