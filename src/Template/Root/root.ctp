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
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
use Cake\Auth\DefaultPasswordHasher;

$this->layout = false;

if (!Configure::read('debug')) :
    throw new NotFoundException('Please replace src/Template/Pages/home.ctp with your own version.');
endif;

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOID: API</title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('home.css') ?>
    <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
</head>
<body class="home">
    <div id="content">
        <div class="row">
<?php

	if ($user):
		echo "<hr>Logged in as: ";
		echo $user['full_name']." (".$user['role'].") ";
		echo $this->Html->link(__('Logout'), '/auth/logout');
	else:
		echo $this->Form->create('', ['url' => '/auth/login']);
		echo "<fieldset>\n";
		echo $this->Form->input('id', ['label' => 'Plin', 'type' => 'text']);
		echo $this->Form->input('password', ['type'=>'password']);
		echo $this->Form->button(__('Login'));
		echo "</fieldset>\n";
		echo $this->Form->end();
	endif;

?>
<hr/>
<table cellpadding="0" cellspacing="0">
<thead>
    <tr>
        <th colspan=2>Uri's</th>
    </tr>
</thead>
<tbody>
    <tr>
		<td>
<ul>
<?php
    $list =
		[ [ 'Debug',                'index',                           ]
		, [ 'Players',              'index'                            ]
		, [ 'Players',              'view',             987            ]
		, [ 'Characters',           'playersIndex',     987            ]
		, [ 'Characters',           'index',                           ]
		, [ 'Characters',           'view',             987,   2       ]
		, [ 'Items',                'charactersIndex',  987,   2       ]
		, [ 'CharactersConditions', 'charactersIndex',  987,   2       ]
		, [ 'CharactersConditions', 'charactersView',   987,   2, 3137 ]
		, [ 'CharactersPowers',     'charactersIndex',  987,   2       ]
		, [ 'CharactersPowers',     'charactersView',   987,   2, 2462 ]
		, [ 'CharactersSkills',     'charactersIndex',  987,   2       ]
		, [ 'CharactersSkills',     'charactersView',   987,   2,    1 ]
		, [ 'CharactersSpells',     'charactersIndex',  987,   2       ]
		, [ 'CharactersSpells',     'charactersView',   987,   2,    9 ]
		, [ 'Teachings',            'charactersIndex',  987,   2       ]
		, [ 'Teachings',            'charactersView',   987,   2       ]
		, [ 'Items',                'index'                            ]
		, [ 'Items',                'view',             724            ]
		, [ 'AttributesItems',      'itemsIndex',       724            ]
		, [ 'AttributesItems',      'itemsView',        724, 482       ]
		, [ 'Attributes',           'index'                            ]
		, [ 'Attributes',           'view',             482            ]
		, [ 'AttributesItems',      'attributesIndex',  482            ]
		, [ 'Powers',               'index'                            ]
		, [ 'Powers',               'view',            2462            ]
		, [ 'CharactersPowers',     'powersIndex',     2462            ]
		, [ 'Conditions',           'index'                            ]
		, [ 'Conditions',           'view',            3137            ]
		, [ 'CharactersConditions', 'conditionsIndex', 3137            ]
		, [ 'Factions',             'index'                            ]
		, [ 'Factions',             'view',               2            ]
		, [ 'Characters',           'factionsIndex',      2            ]
		, [ 'Believes',             'index'                            ]
		, [ 'Believes',             'view',               2            ]
		, [ 'Characters',           'believesIndex',      2            ]
		, [ 'Groups',               'index'                            ]
		, [ 'Groups',               'view',               3            ]
		, [ 'Characters',           'groupsIndex',        3            ]
		, [ 'Worlds',               'index'                            ]
		, [ 'Worlds',               'view',               2            ]
		, [ 'Characters',           'worldsIndex',        2            ]
		, [ 'Events',               'index'                            ]
		, [ 'Lammies',              'index'                            ]
		, [ 'Lammies',              'queue'                            ]
		];

	foreach ($list as $i => $url) {
		$controller        = array_shift($url);
		$action            = array_shift($url);
		$url['controller'] = $controller;
		$url['action']     = $action;
		$url['_method']    = 'GET';

		switch($i) {
		case 24:
			echo "</ul></td>\n<td><ul>";
			break;
		case 1:
		case 4:
		case 17:
		case 21:
		case 24:
		case 27:
		case 30:
		case 33:
		case 36:
		case 39:
		case 42:
		case 43:
			echo '<br/>';
		}
        echo '<li>'.$this->Html->link($url) . "</li>\n";
	}
?>
</ul>
		</td>
    </tr>
</tbody>
</table>

        </div>
    </div>
</body>
</html>
