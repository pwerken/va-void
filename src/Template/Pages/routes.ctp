<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Router;
use Cake\Error\Debugger;

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<p>Display the currently connected routes.
Add a matching route to <?= 'config' . DS . 'routes.php' ?></p>

<h3>Connected Routes</h3>
<table cellspacing="0" cellpadding="0">
<tr><th>Template</th><th>Defaults</th><th>Options</th></tr>
<?php
foreach (Router::routes() as $route):
    echo '<tr>';
    printf(
        '<td width="25%%">%s</td><td>%s</td><td width="20%%">%s</td>',
        $route->template,
        Debugger::exportVar($route->defaults),
        Debugger::exportVar($route->options)
    );
    echo '</tr>';
endforeach;
?>
</table>

</body>
</html>
