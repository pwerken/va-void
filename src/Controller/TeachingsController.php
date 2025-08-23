<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * @property \App\Controller\Component\AddComponent $Add;
 * @property \App\Controller\Component\DeleteComponent $Delete;
 * @property \App\Controller\Component\IndexRelationComponent $IndexRelation;
 * @property \App\Controller\Component\LammyComponent $Lammy
 * @property \App\Controller\Component\ViewComponent $View;
 */
class TeachingsController extends Controller
{
    /**
     * GET /characters/{plin}/{chin}/students
     */
    public function charactersIndex(int $student_id): void
    {
        $parent = $this->fetchTable('Characters')->get($student_id);

        $query = $this->fetchTable()->find('withContain');
        $query->where(['teacher_id' => $student_id]);

        $this->loadComponent('IndexRelation');
        $this->IndexRelation->action($parent, $query);
    }

    /**
     * GET /characters/{plin}/{chin}/teacher
     */
    public function charactersView(int $student_id): void
    {
        $parent = $this->fetchTable('Characters')->get($student_id);
        $this->Authorization->authorize($parent, 'view');

        $this->loadComponent('View');
        $this->View->action($student_id);
    }

    /**
     * PUT /characters/{plin}/{chin}/teacher
     */
    public function charactersAdd(int $student_id): void
    {
        $request = $this->getRequest();
        $request = $request->withData('student_id', $student_id);
        $this->setRequest($request);

        $this->loadComponent('Add');
        $this->Add->action();
    }

    /**
     * DELETE /characters/{plin}/{chin}/teacher
     */
    public function charactersDelete(int $student_id): void
    {
        $this->loadComponent('Delete');
        $this->Delete->action($student_id);
    }

    /**
     * GET /characters/{plin}/{chin}/teacher/print
     */
    public function charactersPdf(int $student_id): void
    {
        $this->loadComponent('Lammy');
        $this->Lammy->actionPdf($student_id);
    }

    /**
     * POST /characters/{plin}/{chin}/teacher/print
     */
    public function charactersQueue(int $student_id): void
    {
        $this->loadComponent('Lammy');
        $this->Lammy->actionQueue($student_id);
    }
}
