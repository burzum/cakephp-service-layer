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

use Cake\Core\ObjectRegistry;

/**
 * Service Aware Trait
 *
 * CakePHP style locator to load service classes
 */
trait ServiceAwareTrait
{
    /**
     * Service Locator Registry
     *
     * @var \Cake\Core\ObjectRegistry
     */
    protected $serviceLocator;

    /**
     * Default Service Locator Class
     *
     * @var string
     */
    protected $defaultServiceLocator = ServiceLocator::class;

    /**
     * Load a service
     *
     * @param string $service Service Name
     * @param array $constructorArgs Constructor Args
     * @param bool $assignProperty Assigns the service to a class property of the same name as  the service
     * @return \stdClass
     */
    public function loadService($service, array $constructorArgs = [], $assignProperty = false)
    {
        $service = $this->getServiceLocator()->load($service, $constructorArgs);

        if (!$assignProperty) {
            return $service;
        }

        list(, $name) = pluginSplit($service);

        if (isset($this->{$name})) {
            trigger_error(__CLASS__ . '::$%s is already in use.', E_USER_WARNING);
        }

        $this->{$name} = $service;

        return $service;
    }

    /**
     * Get the service locator
     *
     * @return \Cake\Core\ObjectRegistry
     */
    public function getServiceLocator()
    {
        if (empty($this->serviceLocator)) {
            $class = $this->defaultServiceLocator;
            $this->serviceLocator = new $class();
        }

        return $this->serviceLocator;
    }

    /**
     * Sets the service locator
     *
     * @param \Cake\Core\ObjectRegistry $locator Locator
     * @return void
     */
    public function setServiceLocator(ObjectRegistry $locator)
    {
        $this->serviceLocator = $locator;
    }
}
