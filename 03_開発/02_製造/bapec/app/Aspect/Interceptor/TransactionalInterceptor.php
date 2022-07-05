<?php

namespace App\Aspect\Interceptor;

use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class TransactionalInterClass 
 */
class TransactionalInterceptor implements MethodInterceptor
{
    /**
     * @param MethodInvocation $invocation
     *
     * @return object
     * @throws \Exception
     */
    public function invoke(MethodInvocation $invocation)
    {
        DB::beginTransaction();
        
        try {
            $result = $invocation->proceed();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return $result;
    }
}
