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

use Cake\Datasource\Paging\PaginatorInterface;
use Cake\Datasource\Paging\SimplePaginator;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\ServerRequest;

/**
 * ServicePaginatorTrait
 */
trait ServicePaginatorTrait
{
    use EventDispatcherTrait;

    /**
     * Paginator instance
     *
     * @var \Cake\Datasource\Paging\PaginatorInterface
     */
    protected $_paginator;

    /**
     * Default paginator class
     *
     * @phpstan-var class-string<\Cake\Datasource\Paging\PaginatorInterface>
     * @var string
     */
    protected $_defaultPaginatorClass = SimplePaginator::class;

    /**
     * Set paginator instance.
     *
     * @param \Cake\Datasource\Paging\PaginatorInterface $paginator Paginator instance.
     * @return static
     */
    public function setPaginator(PaginatorInterface $paginator)
    {
        $this->_paginator = $paginator;

        return $this;
    }

    /**
     * Get paginator instance.
     *
     * @return \Cake\Datasource\Paging\PaginatorInterface
     */
    public function getPaginator()
    {
        if ($this->_paginator === null) {
            $class = $this->_defaultPaginatorClass;
            $this->setPaginator(new $class());
        }

        return $this->_paginator;
    }

    /**
     * Paginate
     *
     * @param \Cake\Datasource\RepositoryInterface|\Cake\Datasource\QueryInterface $object The table or query to paginate.
     * @param array $params Request params
     * @param array $settings The settings/configuration used for pagination.
     * @return \Cake\Datasource\ResultSetInterface Query results
     */
    public function paginate($object, array $params = [], array $settings = [])
    {
        $event = $this->dispatchEvent('Service.beforePaginate', compact(
            'object',
            'params',
            'settings'
        ));

        if ($event->isStopped()) {
            return $event->getResult();
        }

        $result = $this->getPaginator()->paginate($object, $params, $settings);
        $pagingParams = $this->getPaginator()->getPagingParams();

        $event = $this->dispatchEvent('Service.afterPaginate', compact(
            'object',
            'params',
            'settings',
            'result',
            'pagingParams'
        ));

        if ($event->getResult() !== null) {
            return $event->getResult();
        }

        return $result;
    }

    /**
     * Adds the paginator params to the request objects params
     *
     * @param \Cake\Http\ServerRequest $request Request object
     * @return \Cake\Http\ServerRequest
     */
    public function addPagingParamToRequest(ServerRequest $request): ServerRequest
    {
        $paging = $this->getPaginator()->getPagingParams() + $request->getAttribute('paging', []);

        return $request->withAttribute('paging', $paging);
    }
}
