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
declare(strict_types=1);

namespace Burzum\CakeServiceLayer\Generator\Task;

use Burzum\CakeServiceLayer\Filesystem\Folder;
use Cake\Core\App;
use Cake\Core\Plugin;
use IdeHelper\Generator\Directive\Override;
use IdeHelper\Generator\Task\TaskInterface;

/**
 * ServiceTask
 */
class ServiceTask implements TaskInterface
{
    /**
     * Aliases
     *
     * @var string[]
     */
    protected array $aliases = [
        '\Burzum\CakeServiceLayer\Service\ServiceAwareTrait::loadService(0)',
    ];

    /**
     * Buffer
     *
     * @var string[]|null
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
     * @return \IdeHelper\Generator\Directive\BaseDirective[]
     */
    public function collect(): array
    {
        $map = [];

        $services = $this->collectServices();
        foreach ($services as $service => $className) {
            $map[$service] = '\\' . $className . '::class';
        }

        $result = [];
        foreach ($this->aliases as $alias) {
            $directive = new Override($alias, $map);
            $result[$directive->key()] = $directive;
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

        $folders = App::classPath('Service');
        foreach ($folders as $folder) {
            $services = $this->addServices($services, $folder);
        }

        $plugins = Plugin::loaded();
        foreach ($plugins as $plugin) {
            $folders = App::classPath('Service', $plugin);
            foreach ($folders as $folder) {
                $services = $this->addServices($services, $folder, null, $plugin);
            }
        }

        static::$services = $services;

        return $services;
    }

    /**
     * @param string[] $services Services array
     * @param string $path Path
     * @param string|null $subFolder Sub folder
     * @param string|null $plugin Plugin
     * @return string[]
     */
    protected function addServices(
        array $services,
        string $path,
        ?string $subFolder = null,
        ?string $plugin = null
    ): array {
        $folderContent = (new Folder($path))->read(Folder::SORT_NAME, true);

        foreach ($folderContent[1] as $file) {
            preg_match('/^(.+)Service\.php$/', $file, $matches);
            if (!$matches) {
                continue;
            }
            $service = $matches[1];
            if ($subFolder) {
                $service = $subFolder . '/' . $service;
            }

            if ($plugin) {
                $service = $plugin . '.' . $service;
            }

            $className = App::className($service, 'Service', 'Service');
            if (!$className) {
                continue;
            }

            $services[$service] = $className;
        }

        foreach ($folderContent[0] as $subDirectory) {
            $nextSubFolder = $subFolder ? $subFolder . '/' . $subDirectory : $subDirectory;
            $services = $this->addServices($services, $path . $subDirectory . DS, $nextSubFolder, $plugin);
        }

        return $services;
    }
}
