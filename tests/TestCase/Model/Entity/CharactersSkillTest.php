<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\CharactersSkill;
use App\Model\Entity\Skill;
use App\Test\TestSuite\TestCase;

class CharactersSkillTest extends TestCase
{
    public function testPrintableName(): void
    {
        $skill = new Skill();
        $skill->set('name', 'test skill');
        $skill->set('cost', 6);
        $skill->set('blanks', true);
        $skill->set('loresheet', true);

        $cs = new CharactersSkill();
        $cs->set('times', 2);

        $expected = 'test skill x2 (lore & blanks) (12)';
        $this->assertEquals($expected, $cs->printableName($skill));

        $skill->set('blanks', false);
        $expected = 'test skill x2 (lore) (12)';
        $this->assertEquals($expected, $cs->printableName($skill));

        $skill->set('blanks', true);
        $skill->set('loresheet', false);
        $expected = 'test skill x2 (blanks) (12)';
        $this->assertEquals($expected, $cs->printableName($skill));
    }
}
