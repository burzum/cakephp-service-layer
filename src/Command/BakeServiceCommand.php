<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Burzum\CakeServiceLayer\Command;

use Bake\Command\SimpleBakeCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;

/**
 * Command class for generating migration snapshot files.
 */
class BakeServiceCommand extends SimpleBakeCommand
{
    /**
     * Task name used in path generation.
     *
     * @var string
     */
    public $pathFragment = 'Service/';

    /**
     * @var string
     */
    protected $_name;

    /**
     * @inheritDoc
     */
    public static function defaultName(): string
    {
        return 'bake service';
    }

    /**
     * @inheritDoc
     */
    public function bake(string $name, Arguments $args, ConsoleIo $io): void
    {
        $this->_name = $name;

        parent::bake($name, $args, $io);
    }

    /**
     * @inheritDoc
     */
    public function template(): string
    {
        return 'Burzum/CakeServiceLayer.Service/service';
    }

    /**
     * @inheritDoc
     */
    public function templateData(Arguments $arguments): array
    {
        $name = $this->_name;
        $namespace = Configure::read('App.namespace');
        $pluginPath = '';
        if ($this->plugin) {
            $namespace = $this->_pluginNamespace($this->plugin);
            $pluginPath = $this->plugin . '.';
        }

        $namespace .= '\\Service';

        $namespacePart = null;
        if (strpos($name, '/') !== false) {
            $parts = explode('/', $name);
            $name = array_pop($parts);
            $namespacePart = implode('\\', $parts);
        }
        if ($namespacePart) {
            $namespace .= '\\' . $namespacePart;
        }

        return [
            'plugin' => $this->plugin,
            'pluginPath' => $pluginPath,
            'namespace' => $namespace,
            'name' => $name,
        ];
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'service';
    }

    /**
     * @inheritDoc
     */
    public function fileName(string $name): string
    {
        return $name . 'Service.php';
    }
}
