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
</div>
