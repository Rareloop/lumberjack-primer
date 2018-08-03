<?php

namespace Rareloop\Lumberjack\Primer;

use Rareloop\Lumberjack\Primer\TwigPrimerTemplateEngine;
use Rareloop\Primer\Primer;

/**
 * A proxy class to delay the instantiation of Primer so that other Service Providers get
 * an opportunity to setup listeners
 */
class PrimerProxy
{
    protected static $primer;

    public function __call($method, $arguments)
    {
        return (static::primer())->$method(...$arguments);
    }

    public static function __callStatic($method, $arguments)
    {
        return (static::primer())::$method(...$arguments);
    }

    protected static function primer() : Primer
    {
        if (!isset(static::$primer)) {
            static::$primer = Primer::start([
                'basePath' => get_stylesheet_directory().'/views',
                'viewPath' => get_stylesheet_directory().'/views/primer',
                'templateClass' => TwigPrimerTemplateEngine::class,
                'wrapTemplate' => false,
            ]);
        }

        return static::$primer;
    }
}
