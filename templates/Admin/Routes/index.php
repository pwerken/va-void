<?php
declare(strict_types=1);

use Cake\Routing\Router;

/**
 * @var \App\View\AdminView $this
 */
?>
<h3>Configured Routes</h3>

<table cellspacing="0" cellpadding="0">
<tr><th>Path</th><th>HTTP Method</th><th>Function</th></tr>
<?php

$cmpfn = function ($a, $b): int {
    $astr = $a->template;
    $bstr = $b->template;
    if ($astr == $bstr) {
        return strcmp($b->defaults['action'], $a->defaults['action']);
    }

    return strcmp($astr, $bstr);
};

$routes = Router::routes();
uasort($routes, $cmpfn);

foreach ($routes as $route) {
    $method = 'GET';
    if (array_key_exists('_method', $route->defaults)) {
        $method = $route->defaults['_method'];
    }
    if (is_array($method)) {
        $method = implode(',', $method);
    }

    $prefix = '';
    if (array_key_exists('prefix', $route->defaults)) {
        $prefix = $route->defaults['prefix'] . '\\';
    }

    $controller = '{controller}';
    if (isset($route->defaults['controller'])) {
        $controller = $route->defaults['controller'];
    }

    $action = '{action}';
    if (isset($route->defaults['action'])) {
        $action = $route->defaults['action'];
    }

    $function = $prefix . $controller . '&rarr;' . $action;

    $pass = [];
    if (array_key_exists('pass', $route->options)) {
        $pass = $route->options['pass'];
    }

    printf(
        "<tr><td>%s</td><td>%s</td><td>%s( %s )</td></tr>\n",
        $route->template,
        $method,
        $function,
        implode(', ', $pass),
    );
}

?>
</table>
