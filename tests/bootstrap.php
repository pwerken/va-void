<?php

use Migrations\TestSuite\Migrator;

/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */
require dirname(__DIR__) . '/vendor/autoload.php';

require dirname(__DIR__) . '/config/bootstrap.php';

// Use migrations to build test database schema.
//
// Will rebuild the database if the migration state differs
// from the migration history in files.
$migrator = new Migrator();
$migrator->run();
