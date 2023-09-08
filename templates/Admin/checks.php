<?php

use App\Utility\CheckConfig;

?>
<h3>Check Configuration</h3>

<ul>
<?php

foreach(CheckConfig::installation() as $msg => $success):

    $class = $success ? 'success' : 'problem';
?>
	<li class="bullet <?=$class?>"><?=$msg?></li>
<?php endforeach; ?>
</ul>
