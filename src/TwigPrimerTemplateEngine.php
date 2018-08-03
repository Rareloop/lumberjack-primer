<?php

namespace Rareloop\Lumberjack\Primer;

use Rareloop\Primer\TemplateEngine\Twig\Template;

/**
 * A proxy class to delay the instantiation of Primer so that other Service Providers get
 * an opportunity to setup listeners
 */
class TwigPrimerTemplateEngine extends Template
{
    /**
     * Get the singleton Twig Environment
     *
     * @return Twig_Environment
     */
    protected function twigEnvironment()
    {
        $shouldTriggerFilter = !isset(self::$twig);

        $twig = parent::twigEnvironment();

        if ($shouldTriggerFilter) {
            $twig = apply_filters('timber/twig', $twig);
        }

        return $twig;
    }
}
