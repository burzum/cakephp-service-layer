<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Burzum\CakeServiceLayer;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;

/**
 * Plugin class for Burzum/CakeServiceLayer
 */
class Plugin extends BasePlugin
{
    /**
     * @var string
     */
    protected $name = 'Burzum/CakeServiceLayer';

    /**
     * @var bool
     */
    protected $routesEnabled = false;

    /**
     * @var bool
     */
    protected $middlewareEnabled = false;

    /**
     * Add migrations commands.
     *
     * @param \Cake\Console\CommandCollection $collection The command collection to update
     * @return \Cake\Console\CommandCollection
     */
    public function console(CommandCollection $collection): CommandCollection
    {
        if (class_exists('Bake\Command\SimpleBakeCommand')) {
            $commands = $collection->discoverPlugin($this->getName());

            return $collection->addMany($commands);
        }

        return $collection;
    }
}
