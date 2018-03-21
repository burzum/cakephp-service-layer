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
declare(strict_types = 1);

namespace Burzum\Cake\Service;

use App\Service\TestService;
use Cake\TestSuite\TestCase;

/**
 * ServiceLocatorTest
 */
class ServiceLocatorTest extends TestCase
{
    /**
     * testLocate
     *
     * @return void
     */
    public function testLocate()
    {
        $locator = new ServiceLocator();
        $service = $locator->load('Test');
        $this->assertInstanceOf(TestService::class, $service);
    }

    /**
     * testLocateClassNotFound
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Service class `DoesNotExist` not found.
     * @return void
     */
    public function testLocateClassNotFound()
    {
        $locator = new ServiceLocator();
        $locator->load('DoesNotExist');
    }
}
