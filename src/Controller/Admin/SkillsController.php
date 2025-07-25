<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use DateInterval;
use DateTime;
use DateTimeImmutable;

class SkillsController extends AdminController
{
    /**
     * GET /skills
     */
    public function index(): void
    {
        $skills = $this->fetchTable('Skills');
        $this->set('skills', $skills->find('list')->all()->toArray());

        $since = $this->request->getQuery('since', '');
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $since);
        if (!$date) {
            $date = new DateTime();
            $date->sub(new DateInterval('P2Y'));
            $since = $date->format('Y-m-d');
        }
        $this->set('since', $since);

        $and = $this->request->getQuery('and', 0);
        $this->set('and', $and);

        $ids = $this->request->getQuery('skills', []);
        $this->set('selected', $ids);

        $characters = [];
        if (!empty($ids)) {
            $query = $this->fetchTable('Characters')
                        ->find()
                        ->where(['Characters.modified >=' => $since])
                        ->orderByDesc('Characters.modified')
                        ->enableHydration(false);
            $query->innerJoinWith('Skills', function ($q) use ($ids) {
                return $q->where(['Skills.id IN' => $ids]);
            });
            $query->select([
                'Characters.id',
                'Characters.player_id',
                'Characters.chin',
                'Characters.name',
                'Characters.status',
                'Characters.modified',
                'CharactersSkills.skill_id',
                'CharactersSkills.times',
            ]);

            foreach ($query->all() as $c) {
                $id = $c['id'];
                $skill_id = $c['_matchingData']['CharactersSkills']['skill_id'];
                $times = $c['_matchingData']['CharactersSkills']['times'];
                if (isset($characters[$id])) {
                    $characters[$id]['_matchingData'][$skill_id] = $times;
                    continue;
                }
                $c['_matchingData'] = [$skill_id => $times];
                $characters[$id] = $c;
            }
            if ($and) {
                $filtered = [];
                $skillCount = count($ids);
                foreach ($characters as $id => $character) {
                    if (count($character['_matchingData']) === $skillCount) {
                        $filtered[$id] = $character;
                    }
                }
                $characters = $filtered;
            }
        }
        $this->set('characters', $characters);
    }
}
