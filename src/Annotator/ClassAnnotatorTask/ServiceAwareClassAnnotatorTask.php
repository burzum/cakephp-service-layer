<?php
declare(strict_types=1);

namespace Burzum\CakeServiceLayer\Annotator\ClassAnnotatorTask;

use Cake\Core\App;
use IdeHelper\Annotation\AnnotationFactory;
use IdeHelper\Annotator\ClassAnnotatorTask\AbstractClassAnnotatorTask;
use IdeHelper\Annotator\ClassAnnotatorTask\ClassAnnotatorTaskInterface;

/**
 * Classes that use ServiceAwareTrait should automatically have used tables - via loadService() call - annotated.
 */
class ServiceAwareClassAnnotatorTask extends AbstractClassAnnotatorTask implements ClassAnnotatorTaskInterface
{
    /**
     * @param string $path Path
     * @param string $content Content
     * @return bool
     */
    public function shouldRun(string $path, string $content): bool
    {
        if (preg_match('#\buse ServiceAwareTrait\b#', $content)) {
            return true;
        }

        $className = $this->getClassName($path, $content);
        if (!$className) {
            return false;
        }

        try {
            $object = new $className();
            if (method_exists($object, 'loadService')) {
                return true;
            }
        } catch (\Throwable $exception) {
            // Do nothing
        }

        return false;
    }

    /**
     * @param string $path Path
     * @return bool
     */
    public function annotate(string $path): bool
    {
        $services = $this->_getUsedServices($this->content);

        $annotations = $this->_getServiceAnnotations($services);

        return $this->annotateContent($path, $this->content, $annotations);
    }

    /**
     * @param string $content Content
     * @return array
     */
    protected function _getUsedServices(string $content): array
    {
        preg_match_all('/\$this-\>loadService\(\'([a-z.\\/]+)\'/i', $content, $matches);
        if (empty($matches[1])) {
            return [];
        }

        $services = $matches[1];

        return array_unique($services);
    }

    /**
     * @param array $usedServices Used services
     * @return \IdeHelper\Annotation\AbstractAnnotation[]
     */
    protected function _getServiceAnnotations(array $usedServices): array
    {
        $annotations = [];

        foreach ($usedServices as $usedService) {
            $className = App::className($usedService, 'Service', 'Service');
            if (!$className) {
                continue;
            }
            [, $name] = pluginSplit($usedService);

            if (strpos($name, '/') !== false) {
                $name = substr($name, strrpos($name, '/') + 1);
            }

            $annotations[] = AnnotationFactory::createOrFail('@property', '\\' . $className, '$' . $name);
        }

        return $annotations;
    }

    /**
     * @param string $path Path to PHP class file
     * @param string $content Content of PHP class file
     * @return string|null
     */
    protected function getClassName(string $path, string $content): ?string
    {
        preg_match('#^namespace (.+)\b#m', $content, $matches);
        if (!$matches) {
            return null;
        }

        $className = pathinfo($path, PATHINFO_FILENAME);

        return $matches[1] . '\\' . $className;
    }
}
