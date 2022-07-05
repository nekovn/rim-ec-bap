<?php

namespace App\Aspect;

use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Finder\Finder;
use Ray\Aop\Compiler;
use Ray\Aop\Weaver;
use App\Aspect\Modules\AspectModule;

use function class_exists;
use function count;
use function strval;
use function serialize;
use function unserialize;
use function is_array;

/**
 * Class RayAspect
 */
class RayAspect
{
    /** @var Container|\Illuminate\Container\Container */
    protected $app;

    /** @var array */
    protected $configure;

    /** @var Compiler */
    protected $compiler;

    /** @var Filesystem */
    protected $filesystem;

    /** @var bool */
    protected $cacheable = false;

    /** @var bool */
    protected $forceCompile = false;

    /** @var AspectModule */
    protected $aspectResolver;

    /** @var AspectModule[] */
    protected $registerModules = [];

    /** @var AspectModule[] */
    protected $modules = [];

    /** @var string */
    private $mapFile = 'aspect.map.serialize';

    /**
     * RayAspectKernel constructor.
     *
     * @param Container  $app
     *
     * @throws ClassNotFoundException
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->configure = $this->app['config']->get('aspect', []);
        $this->filesystem = $this->app['files'];
        $this->makeCompileDir();
        $this->makeCacheableDir();
        $this->registerAspectModule();
    }

    /**
     * @param string $module
     *
     * @throws ClassNotFoundException
     */
    public function register(string $module = null): void
    {
        if (!class_exists($module)) {
            throw new \Exception();
        }
        $this->modules[] = new $module;
    }

    /**
     * weaving
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \ReflectionException
     */
    public function weave(): void
    {
        if (!count($this->modules)) {
            return;
        }
        $compiler =  new Compiler((string)$this->configure['compile_dir']);
        $container = new ContainerInterceptor($this->app);
        foreach ($this->aspectConfiguration() as $class => $pointcuts) {
            $bind = (new AspectBind($this->filesystem, strval($this->configure['cache_dir']), $this->cacheable))
                ->bind($class, $pointcuts);
            $weaved = $this->forceCompile
                ? $compiler->compile($class, $bind)
                : (new Weaver($bind, (string)$this->configure['compile_dir']))->weave($class);
            $container->intercept($class, $bind, $weaved);
        }
    }

    /**
     * make source compile file directory
     */
    protected function makeCompileDir()
    {
        $this->makeDirectories(strval($this->configure['compile_dir']), 0775);
        $this->forceCompile = (bool)($this->configure['force_compile'] ?? false);
    }

    /**
     * make aspect cache directory
     *
     * @codeCoverageIgnore
     */
    protected function makeCacheableDir()
    {
        if ($this->configure['cache']) {
            $this->makeDirectories(strval($this->configure['cache_dir']), 0775);
            $this->cacheable = true;
        }
    }

    /**
     * @param string $dir
     * @param int    $mode
     */
    private function makeDirectories(string $dir, int $mode = 0777)
    {
        // @codeCoverageIgnoreStart
        if (!$this->filesystem->exists($dir)) {
            $this->filesystem->makeDirectory($dir, $mode, true);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * register Aspect Module
     *
     * @throws ClassNotFoundException
     */
    protected function registerAspectModule()
    {
        if (isset($this->configure['modules'])) {
            foreach ($this->configure['modules'] as $module) {
                $this->register($module);
            }
        }
    }

    /**
     * @return array
     * @codeCoverageIgnore
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function aspectConfiguration(): array
    {
        $map = [];
        $file = $this->configure['cache_dir'] . "/{$this->mapFile}";
        if ($this->configure['cache']) {
            if ($this->filesystem->exists($file)) {
                foreach ($this->modules as $module) {
                    $module->registerPointCut()->configure($this->app);
                }

                return unserialize($this->filesystem->get($file));
            }
        }
        foreach ($this->modules as $module) {
            $tpc = $module->registerPointCut();
            $pointcut = $tpc->configure($this->app);
            foreach ($module->target() as $class) {

                if (is_array($class)) {
                    $classList = $this->getTargetClassList($class[0], $class[1]);
                    foreach ($classList as $classs) {
                        if ($this->has($classs, $tpc->getAnnotation())) {
                            $map[$classs][] = $pointcut;
                        }
                    }
                } else {
                    if ($this->has($class, $tpc->getAnnotation())) {
                        $map[$class][] = $pointcut;
                    }
                }
            }
        }

        if ($this->configure['cache']) {
            $this->filesystem->put($file, serialize($map));
        }

        return $map;
    }

    /**
     * Undocumented function
     *
     * @param string $dir
     * @param string $expression
     * @return array
     */
    protected function getTargetClassList($dir, $expression) {
        $classes = array();
        $finder = new Finder();
        $iterator = $finder
            ->in(base_path() . '/' . $dir)
            ->name($expression)
            ->files();
        
        $path = array();
        foreach ($iterator as $fileinfo) {
            $path[] = $fileinfo->getPathname();
        }

        $dc = get_declared_classes();
        foreach ($dc as $class) {
            $reflect = new \ReflectionClass($class);
            $filename = $reflect->getFileName();
            if( ! $reflect->isInternal() ){
                $filename = str_replace(array('\\'), array('/'), $filename);    
                if (in_array($filename, $path)) {
                    $classes[] = $class;
                }    
            }
        }

        return $classes;
    }

    /**
     * @param Class $class
     * @param       $annotation
     *
     * @return bool
     */
    protected function has($class, $annotation): bool
    {
        if (!$class) {
            return false;
        }
        
        $reflectionClass =  new \ReflectionClass($class);
        $reader = new AnnotationReader();
        foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            $match = $reader->getMethodAnnotation($reflectionMethod, $annotation);
            if ($match) {
                return true;
            }
        }
        return false;
    }
}
