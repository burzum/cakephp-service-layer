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

namespace Burzum\CakeServiceLayer\DomainModel;

use Cake\Core\App;
use Cake\Core\ObjectRegistry;
use RuntimeException;

/**
 * Domain Model Locator
 *
 * CakePHP style locator to load service classes
 */
class DomainModelLocator extends ObjectRegistry
{
    /**
     * Should resolve the class name for a given object type.
     *
     * @param string $class The class to resolve.
     * @return string|null The resolved name or false for failure.
     */
    protected function _resolveClassName(string $class): ?string
    {
        return App::className($class, 'DomainModel');
    }

    /**
     * Throw an exception when the requested object name is missing.
     *
     * @param string $class The class that is missing.
     * @param string $plugin The plugin $class is missing from.
     * @return void
     * @throws \Exception
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
    {
        if (!empty($plugin)) {
            $message = sprintf(
                'Domain Model class `%s` in plugin `%s` not found.',
                $class,
                $plugin
            );
        } else {
            $message = sprintf(
                'Domain Model class `%s` not found.',
                $class
            );
        }

        throw new RuntimeException($message);
    }

    /**
     * Create an instance of a given classname.
     *
     * The passed config array will be used as constructor args for the new
     * object.
     *
     * @param object|string $class The class to build.
     * @param string $alias The alias of the object.
     * @param array $config The Configuration settings for construction
     * @return object
     */
    protected function _create(object|string $class, string $alias, array $config): object
    {
        if (empty($config)) {
            return new $class();
        }

        return new $class(...$config);
    }
}
