<?php

require_once('objs/Lammy/LammySheet.php');
require_once('objs/Lammy/CharLammy.php');

$sheet = LammySheet::getInstance();
$sheet->setDoubleSided(true);

$cl = new CharLammy(NULL);

for($i = 0; $i < 13; $i++)
{
	$sheet->add($cl);
}

$sheet->createPdf();
