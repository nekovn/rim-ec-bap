<?php

namespace App\Aspect\Modules;

use App\Aspect\PointCut\TransactionalPointCut;
use App\Aspect\PointCut\PointCutable;

/**
 * Class TransactionalModule
 */
class TransactionalModule extends AspectModule
{
    /** 
     * @var array 
     */
    protected $classes = [
        // \App\Services\XxxxxxService::class,
        ['app/Services' , '*Service*'],
        ['app/Services/Admin' , '*Service*'],
        ['app/Services/Member' , '*Service*'],
    ];

    /**
     * @return PointCutable
     */
    public function registerPointCut(): PointCutable
    {
        return new TransactionalPointCut;
    }
}
