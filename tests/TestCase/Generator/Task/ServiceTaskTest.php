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

namespace Burzum\Cake\Test\TestCase\Generator\Task;

use Burzum\Cake\Generator\Task\ServiceTask;
use Cake\TestSuite\TestCase;

class ServiceTaskTest extends TestCase
{
    /**
     * testLocate
     *
     * @return void
     */
    public function testCollect()
    {
        $task = new ServiceTask();
        $map = $task->collect();
        $expected = [
            '\Burzum\Cake\Service\ServiceAwareTrait::loadService(0)' => [
                'Articles' => '\App\Service\ArticlesService::class',
                'Test' => '\App\Service\TestService::class',
                'Sub/Folder/Nested' => '\App\Service\Sub\Folder\NestedService::class',
            ],
        ];
        $this->assertSame($expected, $map);
    }
}
