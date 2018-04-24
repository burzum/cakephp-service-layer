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

use App\Model\Business\Article;
use Burzum\Cake\Model\Business\BusinessModelLocator;
use Cake\TestSuite\TestCase;

/**
 * ServiceLocatorTest
 */
class BusinessModelLocatorTest extends TestCase
{
    /**
     * testLocate
     *
     * @return void
     */
    public function testLocate()
    {
        $locator = new BusinessModelLocator();
        $service = $locator->load('Article');
        $this->assertInstanceOf(Article::class, $service);
    }

    /**
     * testLocateClassNotFound
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Business Model class `DoesNotExist` not found.
     * @return void
     */
    public function testLocateClassNotFound()
    {
        $locator = new BusinessModelLocator();
        $locator->load('DoesNotExist');
    }
}
