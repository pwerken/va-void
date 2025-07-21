<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;
use Cake\Database\Schema\TableSchema;

class LammiesTest extends AuthIntegrationTestCase
{
    public function testWhoCanDownloadPdf(): void
    {
        $this->withoutAuth();
        $this->assertGet('/characters/1/1/print', 401);
        $this->assertGet('/characters/1/1/conditions/1/print', 401);
        $this->assertGet('/characters/1/1/conditions/2/print', 401);
        $this->assertGet('/characters/1/1/powers/1/print', 401);
        $this->assertGet('/characters/1/1/powers/2/print', 401);
        $this->assertGet('/characters/1/2/print', 401);
        $this->assertGet('/characters/1/2/conditions/1/print', 401);
        $this->assertGet('/characters/1/2/conditions/2/print', 401);
        $this->assertGet('/characters/1/2/powers/1/print', 401);
        $this->assertGet('/characters/1/2/powers/2/print', 401);
        $this->assertGet('/characters/2/1/conditions/1/print', 401);
        $this->assertGet('/characters/2/1/conditions/2/print', 401);
        $this->assertGet('/characters/2/1/powers/1/print', 401);
        $this->assertGet('/characters/2/1/powers/2/print', 401);
        $this->assertGet('/characters/2/1/print', 401);
        $this->assertGet('/characters/2/2/print', 401);
        $this->assertGet('/characters/99/1/print', 401);
        $this->assertGet('/characters/99/1/conditions/1/print', 401);
        $this->assertGet('/characters/99/1/conditions/2/print', 401);
        $this->assertGet('/characters/99/1/powers/1/print', 401);
        $this->assertGet('/characters/99/1/powers/2/print', 401);
        $this->assertGet('/conditions/1/print', 401);
        $this->assertGet('/conditions/2/print', 401);
        $this->assertGet('/conditions/99/print', 401);
        $this->assertGet('/items/1/print', 401);
        $this->assertGet('/items/2/print', 401);
        $this->assertGet('/items/99/print', 401);
        $this->assertGet('/powers/1/print', 401);
        $this->assertGet('/powers/2/print', 401);
        $this->assertGet('/powers/99/print', 401);

        $this->withAuthPlayer();
        $this->assertPdf('/characters/1/1/print');
        $this->assertPdf('/characters/1/1/print?all');
        $this->assertPdf('/characters/1/1/conditions/1/print');
        $this->assertGet('/characters/1/1/conditions/2/print', 404);
        $this->assertPdf('/characters/1/1/powers/1/print');
        $this->assertGet('/characters/1/1/powers/2/print', 404);
        $this->assertGet('/characters/1/2/print', 404);
        $this->assertGet('/characters/1/2/conditions/1/print', 404);
        $this->assertGet('/characters/1/2/conditions/2/print', 404);
        $this->assertGet('/characters/1/2/powers/1/print', 404);
        $this->assertGet('/characters/1/2/powers/2/print', 404);
        $this->assertGet('/characters/2/1/conditions/1/print', 403);
        $this->assertGet('/characters/2/1/conditions/2/print', 403);
        $this->assertGet('/characters/2/1/powers/1/print', 403);
        $this->assertGet('/characters/2/1/powers/2/print', 403);
        $this->assertGet('/characters/2/1/print', 403);
        $this->assertGet('/characters/2/2/print', 403);
        $this->assertGet('/characters/99/1/print', 403);
        $this->assertGet('/characters/99/1/conditions/1/print', 403);
        $this->assertGet('/characters/99/1/conditions/2/print', 403);
        $this->assertGet('/characters/99/1/powers/1/print', 403);
        $this->assertGet('/characters/99/1/powers/2/print', 403);
        $this->assertGet('/conditions/1/print', 403);
        $this->assertGet('/conditions/2/print', 403);
        $this->assertGet('/conditions/99/print', 403);
        $this->assertPdf('/items/1/print');
        $this->assertPdf('/items/2/print');
        $this->assertGet('/items/99/print', 404);
        $this->assertGet('/powers/1/print', 403);
        $this->assertGet('/powers/2/print', 403);
        $this->assertGet('/powers/99/print', 403);

        $this->withAuthReadOnly();
        $this->assertPdf('/characters/1/1/print');
        $this->assertPdf('/characters/1/1/print?all');
        $this->assertPdf('/characters/1/1/conditions/1/print');
        $this->assertGet('/characters/1/1/conditions/2/print', 404);
        $this->assertPdf('/characters/1/1/powers/1/print');
        $this->assertGet('/characters/1/1/powers/2/print', 404);
        $this->assertGet('/characters/1/2/print', 404);
        $this->assertGet('/characters/1/2/conditions/1/print', 404);
        $this->assertGet('/characters/1/2/conditions/2/print', 404);
        $this->assertGet('/characters/1/2/powers/1/print', 404);
        $this->assertGet('/characters/1/2/powers/2/print', 404);
        $this->assertPdf('/characters/2/1/conditions/1/print');
        $this->assertPdf('/characters/2/1/conditions/2/print');
        $this->assertPdf('/characters/2/1/powers/1/print');
        $this->assertPdf('/characters/2/1/powers/2/print');
        $this->assertPdf('/characters/2/1/print');
        $this->assertGet('/characters/2/2/print', 404);
        $this->assertGet('/characters/99/1/print', 404);
        $this->assertGet('/characters/99/1/conditions/1/print', 404);
        $this->assertGet('/characters/99/1/conditions/2/print', 404);
        $this->assertGet('/characters/99/1/powers/1/print', 404);
        $this->assertGet('/characters/99/1/powers/2/print', 404);
        $this->assertPdf('/conditions/1/print');
        $this->assertPdf('/conditions/1/print?all');
        $this->assertPdf('/conditions/2/print');
        $this->assertPdf('/conditions/2/print?all');
        $this->assertGet('/conditions/99/print', 404);
        $this->assertPdf('/items/1/print');
        $this->assertPdf('/items/2/print');
        $this->assertGet('/items/99/print', 404);
        $this->assertPdf('/powers/1/print');
        $this->assertPdf('/powers/1/print?all');
        $this->assertPdf('/powers/2/print');
        $this->assertPdf('/powers/2/print?all');
        $this->assertGet('/powers/99/print', 404);
    }

    public function testWhoCanQueue(): void
    {
        $this->withoutAuth();
        $this->assertPost('/characters/1/1/print', [], 401);
        $this->assertPost('/characters/1/1/conditions/1/print', [], 401);
        $this->assertPost('/characters/1/1/conditions/2/print', [], 401);
        $this->assertPost('/characters/1/1/powers/1/print', [], 401);
        $this->assertPost('/characters/1/1/powers/2/print', [], 401);
        $this->assertPost('/characters/1/2/print', [], 401);
        $this->assertPost('/characters/1/2/conditions/1/print', [], 401);
        $this->assertPost('/characters/1/2/conditions/2/print', [], 401);
        $this->assertPost('/characters/1/2/powers/1/print', [], 401);
        $this->assertPost('/characters/1/2/powers/2/print', [], 401);
        $this->assertPost('/characters/2/1/conditions/1/print', [], 401);
        $this->assertPost('/characters/2/1/conditions/2/print', [], 401);
        $this->assertPost('/characters/2/1/powers/1/print', [], 401);
        $this->assertPost('/characters/2/1/powers/2/print', [], 401);
        $this->assertPost('/characters/2/1/print', [], 401);
        $this->assertPost('/characters/2/2/print', [], 401);
        $this->assertPost('/characters/99/1/print', [], 401);
        $this->assertPost('/characters/99/1/conditions/1/print', [], 401);
        $this->assertPost('/characters/99/1/conditions/2/print', [], 401);
        $this->assertPost('/characters/99/1/powers/1/print', [], 401);
        $this->assertPost('/characters/99/1/powers/2/print', [], 401);
        $this->assertPost('/conditions/1/print', [], 401);
        $this->assertPost('/conditions/2/print', [], 401);
        $this->assertPost('/conditions/99/print', [], 401);
        $this->assertPost('/items/1/print', [], 401);
        $this->assertPost('/items/2/print', [], 401);
        $this->assertPost('/items/99/print', [], 401);
        $this->assertPost('/powers/1/print', [], 401);
        $this->assertPost('/powers/2/print', [], 401);
        $this->assertPost('/powers/99/print', [], 401);

        $this->withAuthReadOnly();
        $this->assertPost('/characters/1/1/print', [], 403);
        $this->assertPost('/characters/1/1/conditions/1/print', [], 403);
        $this->assertPost('/characters/1/1/conditions/2/print', [], 403);
        $this->assertPost('/characters/1/1/powers/1/print', [], 403);
        $this->assertPost('/characters/1/1/powers/2/print', [], 403);
        $this->assertPost('/characters/1/2/print', [], 403);
        $this->assertPost('/characters/1/2/conditions/1/print', [], 403);
        $this->assertPost('/characters/1/2/conditions/2/print', [], 403);
        $this->assertPost('/characters/1/2/powers/1/print', [], 403);
        $this->assertPost('/characters/1/2/powers/2/print', [], 403);
        $this->assertPost('/characters/2/1/conditions/1/print', [], 403);
        $this->assertPost('/characters/2/1/conditions/2/print', [], 403);
        $this->assertPost('/characters/2/1/powers/1/print', [], 403);
        $this->assertPost('/characters/2/1/powers/2/print', [], 403);
        $this->assertPost('/characters/2/1/print', [], 403);
        $this->assertPost('/characters/2/2/print', [], 403);
        $this->assertPost('/characters/99/1/print', [], 403);
        $this->assertPost('/characters/99/1/conditions/1/print', [], 403);
        $this->assertPost('/characters/99/1/conditions/2/print', [], 403);
        $this->assertPost('/characters/99/1/powers/1/print', [], 403);
        $this->assertPost('/characters/99/1/powers/2/print', [], 403);
        $this->assertPost('/conditions/1/print', [], 403);
        $this->assertPost('/conditions/2/print', [], 403);
        $this->assertPost('/conditions/99/print', [], 403);
        $this->assertPost('/items/1/print', [], 403);
        $this->assertPost('/items/2/print', [], 403);
        $this->assertPost('/items/99/print', [], 403);
        $this->assertPost('/powers/1/print', [], 403);
        $this->assertPost('/powers/2/print', [], 403);
        $this->assertPost('/powers/99/print', [], 403);

        $this->withAuthReferee();
        $this->assertPost('/characters/1/1/print');
        $this->assertPost('/characters/1/1/conditions/1/print');
        $this->assertPost('/characters/1/1/conditions/2/print', [], 404);
        $this->assertPost('/characters/1/1/powers/1/print');
        $this->assertPost('/characters/1/1/powers/2/print', [], 404);
        $this->assertPost('/characters/1/2/print', [], 404);
        $this->assertPost('/characters/1/2/conditions/1/print', [], 404);
        $this->assertPost('/characters/1/2/conditions/2/print', [], 404);
        $this->assertPost('/characters/1/2/powers/1/print', [], 404);
        $this->assertPost('/characters/1/2/powers/2/print', [], 404);
        $this->assertPost('/characters/2/1/conditions/1/print');
        $this->assertPost('/characters/2/1/conditions/2/print');
        $this->assertPost('/characters/2/1/powers/1/print');
        $this->assertPost('/characters/2/1/powers/2/print');
        $this->assertPost('/characters/2/1/print');
        $this->assertPost('/characters/2/2/print', [], 404);
        $this->assertPost('/characters/99/1/print', [], 404);
        $this->assertPost('/characters/99/1/conditions/1/print', [], 404);
        $this->assertPost('/characters/99/1/conditions/2/print', [], 404);
        $this->assertPost('/characters/99/1/powers/1/print', [], 404);
        $this->assertPost('/characters/99/1/powers/2/print', [], 404);
        $this->assertPost('/conditions/1/print');
        $this->assertPost('/conditions/2/print');
        $this->assertPost('/conditions/99/print', [], 404);
        $this->assertPost('/items/1/print');
        $this->assertPost('/items/2/print');
        $this->assertPost('/items/99/print', [], 404);
        $this->assertPost('/powers/1/print');
        $this->assertPost('/powers/2/print');
        $this->assertPost('/powers/99/print', [], 404);
    }

    public function testPrintQueueAccess(): void
    {
        $this->withoutAuth();
        $this->assertGet('/lammies', 401);
        $this->assertGet('/lammies/queue', 401);
        $this->assertPost('/lammies/single', [], 401);
        $this->assertPost('/lammies/double', [], 401);
        $this->assertPost('/lammies/printed', [], 401);

        $this->withAuthPlayer(); // no access
        $this->assertGet('/lammies', 403);
        $this->assertGet('/lammies/queue', 403);
        $this->assertPost('/lammies/single', [], 403);
        $this->assertPost('/lammies/double', [], 403);
        $this->assertPost('/lammies/printed', [], 403);

        $this->withAuthReadOnly(); // can view print queue
        $this->assertGet('/lammies');
        $this->assertGet('/lammies/queue', 403);
        $this->assertPost('/lammies/single', [], 403);
        $this->assertPost('/lammies/double', [], 403);
        $this->assertPost('/lammies/printed', [], 403);

        $this->withAuthReferee(); // can view print queue
        $this->assertGet('/lammies');
        $this->assertGet('/lammies/queue', 403);
        $this->assertPost('/lammies/single', [], 403);
        $this->assertPost('/lammies/double', [], 403);
        $this->assertPost('/lammies/printed', [], 403);

        $this->withAuthInfobalie(); // can print
        $this->assertGet('/lammies');
        $this->assertGet('/lammies/queue');
        $this->assertPost('/lammies/single');
        $this->assertPost('/lammies/double');
        $this->assertPost('/lammies/printed');
    }

    public function testPrintQueue(): void
    {
        $this->withAuthInfobalie();

        // clear table/queue before testing
        $table = $this->fetchTable('Lammies');
        $schema = $table->getSchema();
        $this->assertInstanceOf(TableSchema::class, $schema);
        $connection = $table->getConnection();
        foreach ($schema->truncateSql($connection) as $sql) {
            $connection->execute($sql);
        }

        // confirm empty queue
        $this->assertArrayKeyValue('list', [], $this->assertGet('/lammies'));
        $this->assertGetResponse(0, '/lammies/queue');

        // putting things in the queue...
        $this->assertPostResponse(1, '/characters/1/1/print');
        $this->assertPostResponse(1, '/characters/1/1/conditions/1/print');
        $this->assertPostResponse(1, '/characters/1/1/conditions/1/print');
        $this->assertPostResponse(1, '/characters/1/1/powers/1/print');
        $this->assertPostResponse(1, '/conditions/1/print');
        $this->assertPostResponse(1, '/conditions/2/print');
        $this->assertPostResponse(1, '/items/1/print');
        $this->assertPostResponse(1, '/items/2/print');
        $this->assertPostResponse(1, '/powers/1/print');
        $this->assertPostResponse(1, '/powers/2/print');
        $this->assertPostResponse(4, '/characters/1/1/print', 'all');
        $this->assertPostResponse(2, '/conditions/1/print', 'all');
        $this->assertPostResponse(2, '/powers/1/print', 'all');

        // double check total queue size
        $this->assertGetResponse(18, '/lammies/queue');

        // check pdf response (magic bytes only)
        $this->assertPost('/lammies/single', '18');
        $this->assertStringStartsWith('%PDF', (string)$this->_response->getBody());
        $this->assertPost('/lammies/double', '18');
        $this->assertStringStartsWith('%PDF', (string)$this->_response->getBody());

        // mark all as printed
        $this->assertPostResponse(18, '/lammies/printed', '18');

        // check queue is empty again
        $this->assertGetResponse(0, '/lammies/queue');
    }

    private function assertPdf(string $url): void
    {
        $message = "Failed asserting response of `GET` on `$url`.";

        $this->setConfigRequest(false);
        $this->_sendRequest($url, 'GET', []);
        $this->assertResponseCode(200, $message);

        $actual = (string)$this->_response->getBody();
        $this->assertStringStartsWith('%PDF', $actual, $message);
    }

    private function assertGetResponse(mixed $expected, string $url): void
    {
        $actual = $this->assertGet($url);

        $message = "Failed asserting response of `GET` on `$url`.";
        $this->assertEquals($expected, $actual, $message);
    }

    private function assertPostResponse(mixed $expected, string $url, string|array $data = ''): void
    {
        $actual = $this->assertPost($url, $data);

        $message = "Failed asserting response of `POST` on `$url`.";
        $this->assertEquals($expected, $actual, $message);
    }
}
