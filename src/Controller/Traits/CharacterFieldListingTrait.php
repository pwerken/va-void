<?php
declare(strict_types=1);

namespace App\Controller\Traits;

trait CharacterFieldListingTrait
{
    /**
     * Helper for simple listing of believes/groups/worlds.
     */
    protected function _createListing(string $field, string $class): void
    {
        $results = $this->fetchTable('Characters')
                    ->find($field)
                    ->execute();

        $content = [];
        foreach ($results as $row) {
            $content[] = [ 'class' => $class,'name' => $row['name'] ];
        }

        $this->set('_serialize', [
            'class' => 'List',
            'url' => rtrim($this->request->getPath(), '/'),
            'list' => $content,
        ]);
    }
}
