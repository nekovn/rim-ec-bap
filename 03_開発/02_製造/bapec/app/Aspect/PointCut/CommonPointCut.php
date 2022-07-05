<?php

namespace App\Aspect\PointCut;

use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Ray\Aop\MethodInterceptor;

/**
 * Class CommonPointCut
 */
class CommonPointCut
{
    /** @var MethodInterceptor */
    protected $interceptor;

    /** @var string */
    protected $annotation;

    /**
     * @param MethodInterceptor $interceptor
     */
    protected function setInterceptor(MethodInterceptor $interceptor): void
    {
        $this->interceptor = $interceptor;
    }

    /**
     * @return Pointcut
     */
    protected function withAnnotatedAnyInterceptor(): PointCut
    {
        if (method_exists($this->interceptor, 'setAnnotation')) {
            $this->interceptor->setAnnotation($this->annotation);
        }

        return new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$this->interceptor]
        );
    }

    public function getAnnotation() {
        return $this->annotation;
    }
}
