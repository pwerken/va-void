<?php
declare(strict_types=1);

namespace App\Controller;

class TeachingsController extends Controller
{
    /**
     * GET /characters/{plin}/{chin}/students
     */
    public function charactersIndex(int $char_id): void
    {
        $parent = $this->fetchTable('Characters')->get($char_id);

        $query = $this->Teachings->findWithContain();
        $query->where(['teacher_id' => $char_id]);

        $this->set('parent', $parent);
        $this->set('_serialize', $query->all());
    }

    /**
     * GET /characters/{plin}/{chin}/teacher
     */
    public function charactersView(int $char_id): void
    {
        $query = $this->Teachings->findWithContain();
        $query->where(['student_id' => $char_id]);

        $this->set('_serialize', $query->firstOrFail());
    }

    /**
     * PUT /characters/{plin}/{chin}/teacher
     */
    public function charactersEdit(int $student_id): void
    {
        //TODO
    }

    /**
     * DELETE /characters/{plin}/{chin}/teacher
     */
    public function charactersDelete(int $student_id): void
    {
        //TODO
    }
}
