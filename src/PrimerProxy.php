<?php

namespace Rareloop\Lumberjack\Primer;

use Rareloop\Primer\Primer;
use Rareloop\Primer\TemplateEngine\Twig\Template as TwigTemplateEngine;

/**
 * A proxy class to delay the instantiation of Primer so that other Service Providers get
 * an opportunity to setup listeners
 */
class PrimerProxy
{
    protected $primer;

    public function __call($method, $arguments)
    {
        return $this->primer()->$method(...$arguments);
    }

    protected function primer() : Primer
    {
        if (!isset($this->primer)) {
            $this->primer = Primer::start([
                'basePath' => get_stylesheet_directory().'/views',
                'viewPath' => get_stylesheet_directory().'/views/primer',
                'templateClass' => TwigTemplateEngine::class,
                'wrapTemplate' => false,
            ]);
        }

        return $this->primer;
    }
}
