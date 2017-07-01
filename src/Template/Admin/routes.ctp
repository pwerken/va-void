<h3>Configured Routes</h3>
<?php
use Cake\Routing\Router;

?>
<table cellspacing="0" cellpadding="0">
<tr><th>Path</th><th>HTTP Method</th><th>Function</th></tr>
<?php

foreach (Router::routes() as $key => $route)
{
	$method = @$route->defaults['_method'] ?: 'GET';
	if(is_array($method))
		$method = implode(',', $method);

	$prefix = '';
	if(isset($route->defaults['prefix'])) {
		$prefix = $route->defaults['prefix'] . '\\';
	}

	$action = ':action';
	if(isset($route->defaults['action'])) {
		$action = $route->defaults['action'];
	}
	$function = $prefix.$route->defaults['controller'].'->'.$action;

    printf( "<tr><td>%s</td><td>%s</td><td>%s( %s )</td></tr>\n"
			, $route->template
			, $method, $function
			, implode(", ", @$route->options['pass'] ?: [])
			);
}

?>
</table>
