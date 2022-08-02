<?php
declare(strict_types=1);

namespace App\Controller;

class TeachingsController
    extends AppController
{

    public function charactersIndex($teacher_id)
    {
    }

    public function charactersEdit($student_id)
    {
        $action = 'charactersEdit';
        if(!$this->Teachings->exists(['student_id = ' => $student_id])) {
            $action = 'charactersAdd';
            $this->request = $this->request->withData('student_id', $student_id);
        }

        $plin = $this->request->getData('plin');
        $chin = $this->request->getData('chin');
        $this->request = $this->request->withoutData('plin');
        $this->request = $this->request->withoutData('chin');

        if($plin || $chin) {
            $chars = $this->loadModel('Characters');
            $char = $chars->findByPlayerIdAndChin($plin, $chin)->first();
            $char_id = $char ? $char->id : -1;
            $this->request->withData('teacher_id', $char_id);
        } else {
            $this->request->withData('teacher_id', null);
        }

        $this->dataNameToId('Events', 'updated');
        $this->dataNameToId('Events', 'started');
    }
}
