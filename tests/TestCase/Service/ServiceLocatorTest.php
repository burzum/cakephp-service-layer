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

namespace Burzum\CakeServiceLayer\Service;

use App\Service\Sub\Folder\NestedService;
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
     * testLocate multiple
     *
     * @return void
     */
    public function testLocateMultiple()
    {
        $locator = new ServiceLocator();
        $service = $locator->load('Test');
        $service = $locator->load('Test');

        $this->assertInstanceOf(TestService::class, $service);
    }

    /**
     * testLocate
     *
     * @return void
     */
    public function testLocateNested()
    {
        $locator = new ServiceLocator();
        $service = $locator->load('Sub/Folder/Nested');
        $this->assertInstanceOf(NestedService::class, $service);
    }

    /**
     * testLocateClassNotFound
     *
     * @return void
     */
    public function testLocateClassNotFound()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Service class `DoesNotExist` not found.');
        $locator = new ServiceLocator();
        $locator->load('DoesNotExist');
    }

    /**
     * testPassingClassName
     *
     * @return void
     */
    public function testPassingClassName()
    {
        $locator = new ServiceLocator();
        $locator->load('Existing', [
            'className' => TestService::class
        ]);

        $this->assertInstanceOf(TestService::class, $locator->get('Existing'));

        $this->expectException(\RuntimeException::class);
        $locator->get('Test');
    }
}
