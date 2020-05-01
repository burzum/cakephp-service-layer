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

namespace Burzum\CakeServiceLayer\Test\TestCase\Generator\Task;

use Burzum\CakeServiceLayer\Generator\Task\ServiceTask;
use Cake\TestSuite\TestCase;

class ServiceTaskTest extends TestCase
{
    /**
     * testCollect
     *
     * @return void
     */
    public function testCollect()
    {
        $task = new ServiceTask();

        $result = $task->collect();

        $this->assertCount(1, $result);

        /** @var \IdeHelper\Generator\Directive\Override $directive */
        $directive = array_shift($result);
        $this->assertSame('\Burzum\CakeServiceLayer\Service\ServiceAwareTrait::loadService(0)', $directive->toArray()['method']);

        $map = $directive->toArray()['map'];
        $expected = [
            'Articles' => '\App\Service\ArticlesService::class',
            'Test' => '\App\Service\TestService::class',
            'Sub/Folder/Nested' => '\App\Service\Sub\Folder\NestedService::class',
        ];
        $this->assertSame($expected, $map);
    }
}
