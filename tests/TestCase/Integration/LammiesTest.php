<?php
declare(strict_types=1);

namespace App\Test\TestCase\Integration;

use App\Test\TestSuite\AuthIntegrationTestCase;

class LammiesTest extends AuthIntegrationTestCase
{
    public function testWhoCanPrint()
    {
        $this->withoutAuth();
        $this->assertPost('/characters/1/1/print', [], 401);
        $this->assertPost('/characters/1/1/conditions/1/print', [], 401);
        $this->assertPost('/characters/1/1/conditions/2/print', [], 401);
        $this->assertPost('/characters/1/1/powers/1/print', [], 401);
        $this->assertPost('/characters/1/1/powers/2/print', [], 401);
        $this->assertPost('/characters/1/2/print', [], 404);
        $this->assertPost('/characters/1/2/conditions/1/print', [], 404);
        $this->assertPost('/characters/1/2/conditions/2/print', [], 404);
        $this->assertPost('/characters/1/2/powers/1/print', [], 404);
        $this->assertPost('/characters/1/2/powers/2/print', [], 404);
        $this->assertPost('/characters/2/1/conditions/1/print', [], 401);
        $this->assertPost('/characters/2/1/conditions/2/print', [], 401);
        $this->assertPost('/characters/2/1/powers/1/print', [], 401);
        $this->assertPost('/characters/2/1/powers/2/print', [], 401);
        $this->assertPost('/characters/2/1/print', [], 401);
        $this->assertPost('/characters/2/2/print', [], 404);
        $this->assertPost('/characters/99/1/print', [], 404);
        $this->assertPost('/characters/99/1/conditions/1/print', [], 404);
        $this->assertPost('/characters/99/1/conditions/2/print', [], 404);
        $this->assertPost('/characters/99/1/powers/1/print', [], 404);
        $this->assertPost('/characters/99/1/powers/2/print', [], 404);
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
        $this->assertPost('/characters/1/2/print', [], 404);
        $this->assertPost('/characters/1/2/conditions/1/print', [], 404);
        $this->assertPost('/characters/1/2/conditions/2/print', [], 404);
        $this->assertPost('/characters/1/2/powers/1/print', [], 404);
        $this->assertPost('/characters/1/2/powers/2/print', [], 404);
        $this->assertPost('/characters/2/1/conditions/1/print', [], 403);
        $this->assertPost('/characters/2/1/conditions/2/print', [], 403);
        $this->assertPost('/characters/2/1/powers/1/print', [], 403);
        $this->assertPost('/characters/2/1/powers/2/print', [], 403);
        $this->assertPost('/characters/2/1/print', [], 403);
        $this->assertPost('/characters/2/2/print', [], 404);
        $this->assertPost('/characters/99/1/print', [], 404);
        $this->assertPost('/characters/99/1/conditions/1/print', [], 404);
        $this->assertPost('/characters/99/1/conditions/2/print', [], 404);
        $this->assertPost('/characters/99/1/powers/1/print', [], 404);
        $this->assertPost('/characters/99/1/powers/2/print', [], 404);
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
        $this->assertPost('/characters/2/1/conditions/1/print', [], 404);
        $this->assertPost('/characters/2/1/conditions/2/print');
        $this->assertPost('/characters/2/1/powers/1/print', [], 404);
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

    public function testPrintQueueAccess()
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

    public function testPrintQueue()
    {
        $this->withAuthInfobalie();

        // clear table/queue before testing
        $table = $this->getTableLocator()->get('Lammies');
        $connection = $table->getConnection();
        foreach ($table->getSchema()->truncateSql($connection) as $sql) {
            $connection->execute($sql);
        }

        // confirm empty queue
        $this->assertArrayKeyValue('list', [], $this->assertGet('/lammies'));
        $this->assertEquals(0, $this->assertGet('/lammies/queue'));

        // putting things in the queue...
        $this->assertPost('/characters/1/1/print');
        $this->assertEquals(1, $this->jsonBody());

        $this->assertPost('/characters/1/1/conditions/1/print');
        $this->assertEquals(1, $this->jsonBody());

        $this->assertPost('/characters/1/1/powers/1/print');
        $this->assertEquals(1, $this->jsonBody());

        $this->assertPost('/conditions/1/print');
        $this->assertEquals(1, $this->jsonBody());

        $this->assertPost('/conditions/2/print');
        $this->assertEquals(1, $this->jsonBody());

        $this->assertPost('/items/1/print');
        $this->assertEquals(1, $this->jsonBody());

        $this->assertPost('/items/2/print');
        $this->assertEquals(1, $this->jsonBody());

        $this->assertPost('/powers/1/print');
        $this->assertEquals(1, $this->jsonBody());

        $this->assertPost('/powers/2/print');
        $this->assertEquals(1, $this->jsonBody());

        $this->assertPost('/characters/1/1/print', 'all');
        $this->assertEquals(4, $this->jsonBody());

        // double check queue size
        $this->assertEquals(13, $this->assertGet('/lammies/queue'));

        // check pdf response (magic bytes only)
        $this->assertPost('/lammies/single', '13');
        $pdf = (string)$this->_response->getBody();
        $this->assertEquals('%PDF', substr($pdf, 0, 4));
        $this->assertPost('/lammies/double', '13');
        $pdf = (string)$this->_response->getBody();
        $this->assertEquals('%PDF', substr($pdf, 0, 4));

        // mark all as printed
        $this->assertPost('/lammies/printed', '13');
        $this->assertEquals(13, $this->jsonBody());

        // check queue is empty again
        $this->assertEquals(0, $this->assertGet('/lammies/queue'));
    }
}
