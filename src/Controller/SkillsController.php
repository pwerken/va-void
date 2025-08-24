<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\IndexTrait;
use App\Controller\Traits\ViewTrait;

// @phpcs:disable PSR12.Traits.UseDeclaration.NoBlankLineAfterUse
class SkillsController extends Controller
{
    use IndexTrait; // GET /skills
    use ViewTrait; // GET /skills/{id}
}
