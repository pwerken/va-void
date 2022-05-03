<h3>Configured Routes</h3>
<?php
use Cake\Routing\Router;

?>
<table cellspacing="0" cellpadding="0">
<tr><th>Path</th><th>HTTP Method</th><th>Function</th></tr>
<?php

foreach (Router::routes() as $key => $route)
{
	$method = 'GET';
	if (array_key_exists('_method', $route->defaults))
		$method = $route->defaults['_method'];
	if (is_array($method))
		$method = implode(',', $method);

	$prefix = '';
	if (array_key_exists('prefix', $route->defaults))
		$prefix = $route->defaults['prefix'] . '\\';

	$action = '{:action}';
	if(isset($route->defaults['action']))
		$action = $route->defaults['action'];
	$function = $prefix.$route->defaults['controller'].'&rarr;'.$action;

	$pass = [];
	if (array_key_exists('pass', $route->options))
		$pass = $route->options['pass'];

    printf( "<tr><td>%s</td><td>%s</td><td>%s( %s )</td></tr>\n"
			, $route->template, $method, $function, implode(", ", $pass)
			);
}

?>
</table>
