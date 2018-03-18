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

use Cake\Datasource\ModelAwareTrait;
use ReflectionClass;

/**
 * Loads Services via Annotations
 *
 * While I'm personally not a big fan of using annotations for anything
 * some people like them. Well, I don't have to use it and it was easy to
 * implement.
 */
trait AnnotationServiceLoaderTrait
{

    use ModelAwareTrait;
    use ServiceAwareTrait;

    public function loadServicesFromAnnotation()
    {
        $reflector = new ReflectionClass($this);
        $docBlock = $reflector->getDocComment();
        $docBlock = explode("\n", $docBlock);
        foreach ($docBlock as $line) {
            if (preg_match('/\@service [a-z]+/i', $line, $match)) {
                $this->loadService(substr($match[0], 9));
            }
            if (preg_match('/\@table [a-z]+/i', $line, $match)) {
                $this->loadModel(substr($match[0], 7));
            }
        }
    }
}
