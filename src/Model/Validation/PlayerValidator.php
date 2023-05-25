<?php
declare(strict_types=1);

namespace App\Model\Validation;

use Cake\Routing\Router;

use App\Model\Entity\Player;

class PlayerValidator
    extends AppValidator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('id', 'create');
        $this->requirePresence('first_name', 'create');
        $this->requirePresence('last_name', 'create');

        $this->nonNegativeInteger('id');
        $this->inList('role', Player::roleValues());
#        $this->add('role', 'authorize', ['rule' => [$this, 'authCheck']]);
        $this->allowEmptyString('password');
        $this->notEmptyString('first_name');
        $this->allowEmptyString('insertion');
        $this->notEmptyString('last_name');
        $this->allowEmptyString('gender')->inList('gender', Player::genderValues());
        $this->allowEmptyString('date_of_birth')->date('date_of_birth');
    }

    public function authCheck($value, $context)
    {
        #TODO
#        var_dump(Router::getRequest()->getAttribute('identity'));
#
#        if(!$entity->isDirty('role') || AuthState::hasRole('super'))
#            return true;
#
#        // don't demote someone who is above your auth level
#        if(!AuthState::hasRole($entity->getOriginal('role')))
#            return "Cannot demote user with more authorization than you.";
#
#        // don't promote someone to above your auth level
#        if(!AuthState::hasRole($entity->get('role')))
#            return "Cannot promote user above your own authorization.";
#
        return true;
    }
}
