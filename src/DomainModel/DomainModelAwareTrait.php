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

namespace Burzum\Cake\DomainModel;

use Cake\Core\ObjectRegistry;

/**
 * Domain Model Aware Trait
 *
 * CakePHP style locator to load domain model classes
 */
trait DomainModelAwareTrait
{
    /**
     * Service Locator Registry
     *
     * @var \Cake\Core\ObjectRegistry
     */
    protected $domainModelLocator;

    /**
     * Default Domain Model Locator Class
     *
     * @var string
     */
    protected $defaultDomainModelLocator = DomainModelLocator::class;

    /**
     * Load a Domain Model
     *
     * @param string $model Domain Model Name
     * @param array $constructorArgs Constructor Args
     * @param bool $assignProperty Assigns the domain model to a class property of the same name
     * @return \stdClass
     */
    public function loadService($model, array $constructorArgs = [], $assignProperty = false)
    {
        $domainModel = $this->getDomainModelLocator()->load($model, $constructorArgs);

        if (!$assignProperty) {
            return $domainModel;
        }

        list(, $name) = pluginSplit($model);

        if (isset($this->{$name})) {
            trigger_error(__CLASS__ . '::$%s is already in use.', E_USER_WARNING);
        }

        $this->{$name} = $domainModel;

        return $domainModel;
    }

    /**
     * Get the service locator
     *
     * @return \Cake\Core\ObjectRegistry
     */
    public function getDomainModelLocator()
    {
        if (empty($this->domainModelLocator)) {
            $class = $this->defaultDomainModelLocator;
            $this->domainModelLocator = new $class();
        }

        return $this->domainModelLocator;
    }

    /**
     * Sets the Domain model locator
     *
     * @param \Cake\Core\ObjectRegistry $locator Locator
     * @return void
     */
    public function setDomainModelLocator(ObjectRegistry $locator)
    {
        $this->domainModelLocator = $locator;
    }
}
