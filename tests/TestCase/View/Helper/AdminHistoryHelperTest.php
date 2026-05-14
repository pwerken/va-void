<?php
declare(strict_types=1);

namespace App\Test\TestCase\View\Helper;

use App\Model\Entity\Character;
use App\Model\Entity\History;
use App\Model\Entity\Item;
use App\Test\TestSuite\TestCase;
use App\Utility\Json;
use App\View\Helper\AdminHistoryHelper;
use Cake\Http\ServerRequest;
use Cake\Http\Session;
use Cake\View\View;

class AdminHistoryHelperTest extends TestCase
{
    /**
     * @var ?\Cake\View\View
     */
    protected $View;

    /**
     * @var ?\App\View\Helper\AdminHistoryHelper
     */
    protected $AdminHistory;

    public function setUp(): void
    {
        parent::setUp();

        $this->View = new View(new ServerRequest(['session' => new Session()]));
        $this->AdminHistory = new AdminHistoryHelper($this->View);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->View = null;
        $this->AdminHistory = null;

        $this->clearPlugins();
    }

    public function getFixtures(): array
    {
        return [
            'app.Characters',
            'app.Manatypes',
        ];
    }

    public function testFormatField(): void
    {
        $result = $this->AdminHistory->formatField('character_id', 2);
        $this->assertEquals('<em>character_id:</em> 2 = 2/1 Mathilda', $result);

        $result = $this->AdminHistory->formatField('teacher_id', 2);
        $this->assertEquals('<em>teacher_id:</em> 2 = 2/1 Mathilda', $result);

        $result = $this->AdminHistory->formatField('manatype_id', 1);
        $this->assertEquals('<em>manatype_id:</em> 1 = Mana', $result);

        $result = $this->AdminHistory->formatField('other', 'f00');
        $this->assertEquals('<em>other:</em> &#039;f00&#039;', $result);

        $result = $this->AdminHistory->formatField('other', '< & # >');
        $this->assertEquals('<em>other:</em> &#039;&lt; &amp; # &gt;&#039;', $result);
    }

    public function testMakeLinkCharacter(): void
    {
        $c = new Character();
        $c->set('plin', 2);
        $c->set('chin', 3);

        $h = new History();
        $h->set('entity', 'Character');
        $h->set('data', Json::encode($c, false));

        $expected = [];
        $expected['controller'] = 'History';
        $expected['action'] = 'character';
        $expected[] = 2;
        $expected[] = 3;

        $result = $this->AdminHistory->makeLink($h);
        $this->assertEquals($expected, $result);
    }

    public function testMakeLinkItem(): void
    {
        $h = new History();
        $h->set('entity', 'Item');
        $h->set('data', Json::encode(new Item(), false));
        $h->set('key1', 4);

        $expected = [];
        $expected['controller'] = 'History';
        $expected['action'] = 'item';
        $expected[] = 4;

        $result = $this->AdminHistory->makeLink($h);
        $this->assertEquals($expected, $result);
    }

    public function testRelationLinkCharactersItem(): void
    {
        $h = new History();
        $h->set('entity', 'CharactersItem');
        $h->set('data', '{}');
        $h->set('key1', 1);
        $h->set('key2', 2);

        $expected = [];
        $expected['controller'] = 'History';
        $expected['action'] = 'item';
        $expected[] = 2;

        $result = $this->AdminHistory->relationLink($h);
        $this->assertEquals($expected, $result);
    }

    public function testRelationLinkCharactersCondition(): void
    {
        $h = new History();
        $h->set('entity', 'CharactersCondition');
        $h->set('data', '{}');
        $h->set('key1', 2);
        $h->set('key2', 1);

        $expected = [];
        $expected['controller'] = 'History';
        $expected['action'] = 'condition';
        $expected[] = 1;

        $result = $this->AdminHistory->relationLink($h, true);
        $this->assertEquals($expected, $result);

        $expected = [];
        $expected['controller'] = 'History';
        $expected['action'] = 'character';
        $expected[] = 2;
        $expected[] = 1;

        $result = $this->AdminHistory->relationLink($h, false);
        $this->assertEquals($expected, $result);
    }

    public function testRelationLinkCharactersPowers(): void
    {
        $h = new History();
        $h->set('entity', 'CharactersPower');
        $h->set('data', '{}');
        $h->set('key1', 2);
        $h->set('key2', 1);

        $expected = [];
        $expected['controller'] = 'History';
        $expected['action'] = 'power';
        $expected[] = 1;

        $result = $this->AdminHistory->relationLink($h, true);
        $this->assertEquals($expected, $result);

        $expected = [];
        $expected['controller'] = 'History';
        $expected['action'] = 'character';
        $expected[] = 2;
        $expected[] = 1;

        $result = $this->AdminHistory->relationLink($h, false);
        $this->assertEquals($expected, $result);
    }
}
