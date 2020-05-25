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

use App\DomainModel\Article;
use Burzum\CakeServiceLayer\DomainModel\DomainModelLocator;
use Cake\TestSuite\TestCase;

/**
 * ServiceLocatorTest
 */
class DomainModelLocatorTest extends TestCase
{
    /**
     * testLocate
     *
     * @return void
     */
    public function testLocate(): void
    {
        $locator = new DomainModelLocator();
        $service = $locator->load('Article');
        $this->assertInstanceOf(Article::class, $service);
    }

    /**
     * testLocateClassNotFound
     *
     * @return void
     */
    public function testLocateClassNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Domain Model class `DoesNotExist` not found.');
        $locator = new DomainModelLocator();
        $locator->load('DoesNotExist');
    }
}
