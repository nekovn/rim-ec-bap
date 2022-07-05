<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Aspect\AnnotationConfiguration;
use App\Aspect\RayAspect;

/**
 * Class AspectServiceProvider
 */
class AspectServiceProvider extends ServiceProvider
{
    /** @var bool */
    protected $defer = false;

    /**
     * boot aspect kernel
     */
    public function boot(): void
    {
        $this->app['ray.aop']->weave();
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->singleton(AnnotationConfiguration::class, function ($app) {
            $annotationConfiguration = new AnnotationConfiguration(
                $app['config']->get('aspect.annotation')
            );
            return $annotationConfiguration;
        });

        $this->app->singleton('ray.aop', function ($app) {
            /** @var AnnotationConfiguration $annotationConfiguration */
            $annotationConfiguration = $app->make(AnnotationConfiguration::class);
            $annotationConfiguration->ignoredAnnotations();

            // register annotation
            return new RayAspect($app);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'ray.aop',
        ];
    }
}
