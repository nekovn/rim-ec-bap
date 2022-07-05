<?php

namespace App\Aspect\Modules;

use App\Aspect\PointCut\PointCutable;

/**
 * Class AspectModule
 */
abstract class AspectModule
{
    /** @var array */
    protected $classes = [];

    /**
     * @codeCoverageIgnore
     * @return PointCutable
     */
    abstract public function registerPointCut(): PointCutable;

    /**
     * @return object[]
     */
    public function target(): array
    {
        return $this->classes;
    }
}
