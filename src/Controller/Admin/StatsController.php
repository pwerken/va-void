<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use DateInterval;
use DateTime;
use DateTimeImmutable;

class StatsController extends AdminController
{
    /**
     * GET /stats
     */
    public function index(): void
    {
        $this->getRequest()->allowMethod(['get']);

        $since = $this->getRequest()->getQuery('since', '');
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $since);
        if (!$date) {
            $date = new DateTime();
            $date->sub(new DateInterval('P2Y'));
        }
        $since = $date->format('Y-m-d');
        $this->set('since', $since);

        $queries = [
            'xp-curve' => 'Amount of XP per Character (aggregated)',
            'items-per-char' => 'Number of Items per Character',
            'powers-conditions-per-char' => 'Number of Powers+Conditions per Character',
            'attunement-powers-per-char' => 'Number of Attunement Powers per Character',
            'attunement-per-char' => 'Total attunement per Character',
        ];
        $this->set('queries', $queries);

        $selected = $this->getRequest()->getQuery('selected', '');
        if (!isset($queries[$selected])) {
            $selected = '';
        }
        $this->set('selected', $selected);

        $this->set('description', '');
        $this->set('aggregate', false);

        $this->set('data', match ($selected) {
            'xp-curve' => $this->xpPerChar($since),
            'items-per-char' => $this->itemsPerChar($since),
            'powers-conditions-per-char' => $this->pcPerChar($since),
            'attunement-powers-per-char' => $this->attunementsPerChar($since),
            'attunement-per-char' => $this->attunementPerChar($since),
            default => [],
        });
    }

    protected function xpPerChar(string $since): array
    {
        $this->set('description', implode('', [
            'XP curve of the active characters. Rounded to nearest whole XP.',
        ]));
        $this->set('aggregate', 'XP');

        $query = $this->fetchTable('Characters')
            ->find()
            ->where([
                'Characters.modified >' => $since,
                'Characters.status LIKE' => 'active',
            ])
            ->orderByDesc('xp', true);

        $rows = [];
        foreach ($query->all() as $character) {
            $rows[] = [
                'plin' => $character->plin,
                'chin' => $character->chin,
                'name' => $character->name,
                'value' => round((float)$character->xp),
            ];
        }

        return $rows;
    }

    protected function itemsPerChar(string $since): array
    {
        $this->set('description', implode('', [
            'Total number of items the active characters currently have.',
        ]));

        $query = $this->fetchTable('Items')->find('withContain');
        $query->select([
                'Characters.plin',
                'Characters.chin',
                'Characters.name',
                'total' => $query->func()->count('Items.itin'),
            ])
            ->where(['Characters.modified >' => $since])
            ->andWhere(['Characters.status' => 'active'])
            ->groupBy('character_id')
            ->orderByDesc('total', true)
            ->orderByAsc('plin')
            ->orderByDesc('chin');

        $rows = [];
        foreach ($query->all() as $item) {
            $rows[] = [
                'plin' => $item->character->plin,
                'chin' => $item->character->chin,
                'name' => $item->character->name,
                'value' => $item->total,
            ];
        }

        return $rows;
    }

    protected function pcPerChar(string $since): array
    {
        $this->set('description', implode('', [
            'Total number of power and condition cards the active characters have.',
        ]));

        $pQuery = $this->fetchTable('CharactersPowers')->find();
        $pQuery->select([
                'id' => 'character_id',
                'total' => $pQuery->func()->count('*'),
            ])
            ->groupBy('id');

        $cQuery = $this->fetchTable('CharactersConditions')->find();
        $cQuery->select([
                'id' => 'character_id',
                'total' => $pQuery->func()->count('*'),
            ])
            ->groupBy('id');

        $query = $this->fetchTable('Characters')->find();
        $query->from(['c' => $cQuery->union($pQuery)])
            ->selectAlso(['total' => $query->func()->sum($query->identifier('c.total'))])
            ->innerJoin(
                ['Characters' => 'characters'],
                ['Characters.id' => $query->identifier('c.id')],
            )
            ->where([
                'Characters.modified >' => $since,
                'Characters.status' => 'active',
            ])
            ->groupBy('c.id')
            ->orderByDesc('total', true)
            ->orderByAsc('Characters.plin')
            ->orderByDesc('Characters.chin');

        $rows = [];
        foreach ($query->all() as $character) {
            $rows[] = [
                'plin' => $character->plin,
                'chin' => $character->chin,
                'name' => $character->name,
                'value' => $character->total,
            ];
        }

        return $rows;
    }

    protected function attunementPerChar(string $since): array
    {
        $this->set('description', implode('', [
            'Total times the active character has taken the',
            ' attunement skill (rune and glyph combined).',
            ' Not counting from powers, conditions or items.',
        ]));

        $query = $this->fetchTable('CharactersSkills')->find('withContain');
        $query->select([
                'Characters.plin',
                'Characters.chin',
                'Characters.name',
                'total' => $query->func()->sum('times'),
            ])
            ->where([
                'Characters.modified >' => $since,
                'Characters.status' => 'active',
                'CharactersSkills.skill_id IN' => [425, 434],
            ])
            ->groupBy('character_id')
            ->orderByDesc('total', true)
            ->orderByAsc('Characters.plin')
            ->orderByDesc('Characters.chin');

        $rows = [];
        foreach ($query->all() as $charSkill) {
            $rows[] = [
                'plin' => $charSkill->character->plin,
                'chin' => $charSkill->character->chin,
                'name' => $charSkill->character->name,
                'value' => $charSkill->total,
            ];
        }

        return $rows;
    }

    protected function attunementsPerChar(string $since): array
    {
        $this->set('description', implode('', [
            'Number of attunement power cards active characters have,',
            ' both glyphs and runes.',
        ]));

        $query = $this->fetchTable('CharactersPowers')->find('withContain');
        $query->select([
                'Characters.plin',
                'Characters.chin',
                'Characters.name',
                'total' => $query->func()->count('power_id'),
            ])
            ->where([
                'Characters.modified >' => $since,
                'Characters.status' => 'active',
                'OR' => [
                    ['Powers.name LIKE' => 'Rune imbue%'],
                    ['Powers.name LIKE' => 'Glyph imbue%'],
                ],
            ])
            ->groupBy('character_id')
            ->orderByDesc('total')
            ->orderByAsc('Characters.plin')
            ->orderByDesc('Characters.chin');

        $rows = [];
        foreach ($query->all() as $charPower) {
            $rows[] = [
                'plin' => $charPower->character->plin,
                'chin' => $charPower->character->chin,
                'name' => $charPower->character->name,
                'value' => $charPower->total,
            ];
        }

        return $rows;
    }
}
