<?php

namespace App\Aspect;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

use function count;
use function array_merge;

/**
 * Class AnnotationConfiguration
 */
class AnnotationConfiguration
{
    /** @var array */
    protected $configuration = [];

    /** @var array */
    protected $customAnnotations = [];

    /** @var string[] */
    private $annotations = [];

    /**
     * @param array $configuration
     * @param array $customAnnotations
     */
    public function __construct(array $configuration, array $customAnnotations = [])
    {
        $this->configuration = $configuration;
        $this->customAnnotations = $customAnnotations;
        $this->registerAnnotations();
    }

    /**
     * Add a new annotation to the globally ignored annotation names with regard to exception handling.
     */
    public function ignoredAnnotations(): void
    {
        if (isset($this->configuration['ignores'])) {
            $ignores = $this->configuration['ignores'];
            if (count($ignores)) {
                foreach ($ignores as $ignore) {
                    AnnotationReader::addGlobalIgnoredName($ignore);
                }
            }
        }
    }

    protected function registerAnnotations(): void
    {
        $this->annotations = array_merge(
            $this->annotations,
            $this->customAnnotations
        );
        if (isset($this->configuration['custom'])) {
            $this->annotations = array_merge(
                $this->annotations,
                $this->configuration['custom']
            );
        }
        foreach ($this->annotations as $annotation) {
            AnnotationRegistry::loadAnnotationClass($annotation);
        }
    }
}
