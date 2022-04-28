<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;
use Tests\Support\Database\Seeds\AnotherSeeder;
use Tests\Support\Database\Seeds\CITestSeeder;

/**
 * @group DatabaseLive
 *
 * @internal
 */
final class DatabaseTestCaseTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    private static bool $loaded = false;

    /**
     * Should the db be refreshed before
     * each test?
     *
     * @var bool
     */
    protected $refresh = true;

    /**
     * The seed file(s) used for all tests within this test case.
     * Should be fully-namespaced or relative to $basePath
     *
     * @var array|string
     */
    protected $seed = [
        CITestSeeder::class,
        AnotherSeeder::class,
    ];

    /**
     * The namespace(s) to help us find the migration classes.
     * Empty is equivalent to running `spark migrate -all`.
     * Note that running "all" runs migrations in date order,
     * but specifying namespaces runs them in namespace order (then date)
     *
     * @var array|string|null
     */
    protected $namespace = [
        'Tests\Support',
        'Tests\Support\MigrationTestMigrations',
    ];

    protected function setUp(): void
    {
        $this->setUpMethods[] = 'setUpAddNamespace';

        parent::setUp();
    }

    protected function setUpAddNamespace()
    {
        Services::autoloader()->addNamespace(
            'Tests\Support\MigrationTestMigrations',
            SUPPORTPATH . 'MigrationTestMigrations'
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->regressDatabase();
    }

    public function testMultipleSeeders()
    {
        $this->seeInDatabase('user', ['name' => 'Jerome Lohan']);
    }

    public function testMultipleMigrationNamespaces()
    {
        $this->seeInDatabase('foo', ['key' => 'foobar']);
    }
}
