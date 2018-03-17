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

use Cake\Datasource\Paginator;
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
     */
    protected $_paginator;

    /**
     * Default paginator class
     *
     * @var string
     */
    protected $_defaultPaginator = Paginator::class;

    /**
     * Set paginator instance.
     *
     * @param \Cake\Datasource\Paginator $paginator Paginator instance.
     * @return self
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->_paginator = $paginator;
        return $this;
    }

    /**
     * Get paginator instance.
     *
     * @return \Cake\Datasource\Paginator
     */
    public function getPaginator()
    {
        if (empty($this->_paginator)) {
            $this->_paginator = new $this->_defaultPaginator();
        }
        return $this->_paginator;
    }

    /**
     * Paginate
     *
     * @param \Cake\Datasource\RepositoryInterface|\Cake\Datasource\QueryInterface $object The table or query to paginate.
     * @param array $params Request params or the request object itself
     * @param null|\Cake\Http\ServerRequest $request
     * @return \Cake\Datasource\ResultSetInterface Query results
     * @throws \Cake\ORM\Exception\PageOutOfBoundsException.
     */
    public function paginate($object, $params, $request = null)
    {
        $this->dispatchEvent('ServicePagination.beforePaginate', compact(
            'object',
            'params'
        ));
        if ($params instanceof ServerRequest) {
            $request = $params;
            $params = $request->getQueryParams();
        }
        $result = $this->getPaginator()->paginate($object, $params);
        $pagingParams = $this->getPaginator()->getPagingParams();
        if ($request instanceof ServerRequest) {
            $this->addPagingParamToRequest($request);
        }
        $this->dispatchEvent('ServicePagination.afterPaginate', compact(
            'object',
            'params',
            'result',
            'pagingParams'
        ));
        return $result;
    }

    /**
     * addPagingParamToRequest
     */
    public function addPagingParamToRequest(ServerRequest &$request)
    {
        $request->addParams([
            'paging' => $this->_paginator->getPagingParams()
                + (array)$request->getParam('paging')
        ]);
    }
}