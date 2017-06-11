<?php
use Cake\Routing\Router;

?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Debug links') ?></li>
<?php
foreach($links as $url => $descr)
	echo '<li>'.$this->Html->link($descr, $url)."</il>\n";
?>
    </ul>
</nav>
<div class="players index large-9 medium-8 columns content">
	<h3>Configured Routes</h3>

<table cellspacing="0" cellpadding="0">
<tr><th>Path</th><th>HTTP Method</th><th>Function</th></tr>
<?php

foreach (Router::routes() as $key => $route)
{
	$method = @$route->defaults['_method'] ?: 'GET';
	if(is_array($method))
		$method = implode(',', $method);

	$action = @$route->defaults['action'] ?: ':action';
	$function = $route->defaults['controller'].'->'.$action;

    printf( "<tr><td>%s</td><td>%s</td><td>%s( %s )</td></tr>\n"
			, $route->template
			, $method, $function
			, implode(", ", @$route->options['pass'] ?: [])
			);
}

?>
</table>
</div>
