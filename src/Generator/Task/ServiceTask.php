<?php
/**
 * Copyright (c) Florian Krämer
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

namespace Burzum\Cake\Generator\Task;

use Cake\Core\App;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use IdeHelper\Generator\Task\TaskInterface;

/**
 * ServiceTask
 */
class ServiceTask implements TaskInterface
{
    /**
     * Aliases
     *
     * @var array
     */
    protected $aliases = [
        '\Burzum\Cake\Service\ServiceAwareTrait::loadService(0)',
        '\ServiceAwareTrait::loadService(0)',
    ];

    /**
     * Buffer
     *
     * @var array|null
     */
    protected static $services;

    /**
     * @return void
     */
    public static function clearBuffer()
    {
        static::$services = null;
    }

    /**
     * @return array
     */
    public function collect()
    {
        $map = [];

        $services = $this->collectServices();
        foreach ($services as $service => $className) {
            $map[$service] = '\\' . $className . '::class';
        }

        $result = [];
        foreach ($this->aliases as $alias) {
            $result[$alias] = $map;
        }

        return $result;
    }

    /**
     * @return string[]
     */
    protected function collectServices()
    {
        if (static::$services !== null) {
            return static::$services;
        }

        $services = [];

        $folders = App::path('Service');
        foreach ($folders as $folder) {
            $services = $this->addServices($services, $folder);
        }

        $plugins = Plugin::loaded();
        foreach ($plugins as $plugin) {
            $folders = App::path('Service', $plugin);
            foreach ($folders as $folder) {
                $services = $this->addServices($services, $folder, $plugin);
            }
        }

        static::$services = $services;

        return $services;
    }

    /**
     * @param array $services Services array
     * @param string $folder Folder
     * @param string|null $plugin Plugin
     * @return string[]
     */
    protected function addServices(array $services, $folder, $plugin = null)
    {
        $folderContent = (new Folder($folder))->read(Folder::SORT_NAME, true);

        foreach ($folderContent[1] as $file) {
            preg_match('/^(.+)Service\.php$/', $file, $matches);
            if (!$matches) {
                continue;
            }
            $service = $matches[1];
            if ($plugin) {
                $service = $plugin . '.' . $service;
            }

            $className = App::className($service, 'Service', 'Service');
            if (!$className) {
                continue;
            }

            $services[$service] = $className;
        }

        return $services;
    }
}
