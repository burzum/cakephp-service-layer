<?php
/**
 * Copyright (c) Florian Krämer
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Florian Krämer
 * @link          https://github.com/burzum/cakephp-service-layer
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
declare(strict_types=1);

namespace Burzum\CakeServiceLayer\Service;

use App\Service\ArticlesService;
use Cake\Http\ServerRequest;
use Cake\ORM\ResultSet;
use Cake\TestSuite\TestCase;

/**
 * ServicePaginatorTraitTest
 */
class ServicePaginatorTraitTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'core.Articles',
    ];

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * testPagination
     *
     * @return void
     */
    public function testPagination()
    {
        $request = new ServerRequest();
        $service = new ArticlesService();
        $result = $service->listing($request);

        $this->assertInstanceOf(ResultSet::class, $result);
    }
}
