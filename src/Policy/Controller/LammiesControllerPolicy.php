<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class LammiesControllerPolicy
    extends AppControllerPolicy
{
    // GET /lammies
    public function index(): bool
    {
        return $this->hasAuth('read-only');
    }

    // PUT /lammies
    public function add(): bool
    {
        return false; //$this->hasAuth('super');
    }

    // GET /lammies/:id
    public function view(): bool
    {
        $this->index();
    }

    // PUT /lammies/:id
    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /lammies/:id
    public function delete(): bool
    {
        return $this->add();
    }

    // GET /lammies/queue
    public function queue(): bool
    {
        return $this->hasAuth('referee');
    }

    // POST /lammies/printed
    public function printed(): bool
    {
        return $this->hasAuth('infobalie');
    }

    // POST /lammies/single
    public function pdfSingle(): bool
    {
        return $this->printed();
    }

    // POST /lammies/double
    public function pdfDouble(): bool
    {
        return $this->printed();
    }
}
