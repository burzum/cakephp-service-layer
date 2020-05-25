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

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * ServicePaginatorTraitTest
 */
class ArticlesTable extends Table
{
    /**
     * Initialize
     *
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config = []): void
    {
        parent::initialize($config);
    }
}
