<?php

namespace App\Aspect\PointCut;

use Illuminate\Contracts\Container\Container;
use Ray\Aop\Pointcut;
use App\Aspect\Annotation\Transactional;
use App\Aspect\Interceptor\TransactionalInterceptor;

/**
 * Class TransactionalPointCut
 */
class TransactionalPointCut extends CommonPointCut implements PointCutable
{
    protected $annotation = Transactional::class;

    public function configure(Container $app): Pointcut
    {
        $interceptor = new TransactionalInterceptor;
        $this->setInterceptor($interceptor);

        return $this->withAnnotatedAnyInterceptor();
    }
}
