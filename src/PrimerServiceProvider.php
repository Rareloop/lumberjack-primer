<?php

namespace Rareloop\Lumberjack\Primer;

use Rareloop\Lumberjack\Facades\Router;
use Rareloop\Lumberjack\Primer\PrimerLoader;
use Rareloop\Lumberjack\Providers\ServiceProvider;
use Rareloop\Lumberjack\Config;
use Rareloop\Primer\Events\Event;
use Zend\Diactoros\Response\RedirectResponse;

class PrimerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $proxy = new PrimerProxy;

        $this->app->bind(PrimerProxy::class, $proxy);
        $this->app->bind('primer', $proxy);
    }

    public function boot(Config $config)
    {
        $this->addPrimerRoutes();
        $this->addPrimerLoaderToTimber();
        $this->addDataToPrimerTemplateRenders($config);

        Event::listen('twig.init', function ($twig) {
            $twig->setCache(false);
        });
    }

    private function addDataToPrimerTemplateRenders($config)
    {
        Event::listen('render', function ($data) use ($config) {
            $data->primer->environment = $config->get('app.environment');
            $data->primer->themeBaseUrl = get_stylesheet_directory_uri();
        });
    }

    private function addPrimerLoaderToTimber()
    {
        add_filter('timber/loader/loader', function ($loader) {
            $loader2 = new PrimerLoader(get_stylesheet_directory() . '/views/patterns');

            $chainLoader = new \Twig_Loader_Chain([$loader, $loader2]);

            return $chainLoader;
        });
    }

    private function addPrimerRoutes()
    {
        // This is a bit more verbose that we have to be in Primer normal as the routing doesn't
        // have a 'catch all' type language. Which I think is a good thing generally, but not how
        // Primer has been built - doh!
        Router::group('primer', function ($group) {
            $group->get('/', 'Rareloop\Lumberjack\Primer\PrimerController@all')->name('primer.all');
            $group->get('patterns', function () {
                return new RedirectResponse(Router::url('primer.all'));
            });

            // Patterns
            $group->get('patterns/{section}/{group}/{pattern}', 'Rareloop\Lumberjack\Primer\PrimerController@pattern');
            $group->get('patterns/{section}/{group}', 'Rareloop\Lumberjack\Primer\PrimerController@group');
            $group->get('patterns/{section}', 'Rareloop\Lumberjack\Primer\PrimerController@section');

            // Templates
            $group->get('templates/{template}', 'Rareloop\Lumberjack\Primer\PrimerController@template');
        });
    }
}
