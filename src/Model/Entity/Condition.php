<?php
namespace App\Model\Entity;

class Condition
    extends AppEntity
{
    protected $_compact  = [ 'id', 'name', 'deprecated' ];
}
