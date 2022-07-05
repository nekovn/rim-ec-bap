<?php

namespace App\Aspect\PointCut;

use Illuminate\Contracts\Container\Container;
use Ray\Aop\Pointcut;

/**
 * Interface PointCutable
 */
interface PointCutable
{
    /**
     * @param Container $app
     * @return \Ray\Aop\Pointcut
     */
    public function configure(Container $app): PointCut;
}
