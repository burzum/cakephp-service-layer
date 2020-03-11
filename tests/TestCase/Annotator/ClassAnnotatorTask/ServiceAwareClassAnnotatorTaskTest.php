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

use Burzum\CakeServiceLayer\Annotator\ClassAnnotatorTask\ServiceAwareClassAnnotatorTask;
use Cake\Console\ConsoleIo;
use Cake\TestSuite\Stub\ConsoleOutput;
use Cake\TestSuite\TestCase;
use IdeHelper\Console\Io;

class ServiceAwareClassAnnotatorTaskTest extends TestCase
{
    /**
     * @return void
     */
    public function testAnnotate(): void
    {
        $out = new ConsoleOutput();
        $io = new Io(new ConsoleIo($out));
        $config = [
            'dry-run' => true,
            'verbose' => true,
        ];

        $path = TEST_FILES . 'annotator' . DS . 'MyController.php';
        $content = file_get_contents($path);
        $task = new ServiceAwareClassAnnotatorTask($io, $config, $content);

        $result = $task->annotate($path);
        $this->assertTrue($result);

        $this->assertTextContains('-> 2 annotations added.', $out->output());
    }
}
