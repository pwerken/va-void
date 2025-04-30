<?php

use App\Utility\CheckConfig;

?>
<h3>Check Configuration</h3>

<div class="checks">
<?php

foreach (CheckConfig::installation() as $msg => $success) :
    $class = $success ? 'success' : 'problem';
    ?>
    <p class="<?=$class?>"><?=$msg?>
<?php endforeach; ?>
</div>
