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

namespace App\Service;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\ResultSet;

class ArticlesService
{
    use LocatorAwareTrait;

    /**
     * @var \Cake\ORM\Table
     */
    protected $Articles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        $this->Articles = $this->fetchTable('Articles');
    }

    /**
     * List articles
     *
     * @param \Cake\Http\ServerRequest $request
     * @return \Cake\Datasource\ResultSetInterface
     */
    public function listing($request)
    {
        $result = new ResultSet([]);

        return $result;
    }
}
