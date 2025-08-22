<?php
declare(strict_types=1);

use App\Utility\CheckConfig;

/**
 * @var \App\View\AdminView $this
 */
?>
<h3>Check Configuration</h3>

<div class="checks">
<?php

foreach (CheckConfig::installation() as $msg => $success) :
    $class = $success ? 'success' : 'problem';
    ?>
    <p class="<?= $class ?>"><?=$msg?>
<?php endforeach; ?>
</div>
