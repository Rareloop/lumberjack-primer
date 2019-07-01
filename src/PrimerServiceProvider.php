<?php

namespace Rareloop\Lumberjack\Primer;

use Gajus\Dindent\Indenter;
use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Facades\Router;
use Rareloop\Lumberjack\Primer\DocumentProvider;
use Rareloop\Lumberjack\Primer\DocumentProviderManager;
use Rareloop\Lumberjack\Primer\PatternProvider;
use Rareloop\Lumberjack\Primer\PatternProviderManager;
use Rareloop\Lumberjack\Primer\TemplateProviderManager;
use Rareloop\Lumberjack\Providers\ServiceProvider;
use Rareloop\Primer\Primer;
use Rareloop\Primer\Twig\PrimerLoader;
use Rareloop\Primer\Twig\TwigTemplateRenderer;
use Timber\Loader;
use Twig\Loader\ChainLoader;
use Twig\TwigFilter;

class PrimerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerPatternProvider();
        $this->registerTemplateProvider();
        $this->registerDocumentProvider();

        $this->registerPrimer();
    }

    public function boot(Config $config)
    {
        $this->addLoadersToTwig();

        if (!$this->isEnabled()) {
            return;
        }

        $this->addRoutes($config->get('primer.routes.prefix', 'primer'));
        $this->addTwigFilters();
    }

    protected function isEnabled()
    {
        return true;
    }

    protected function registerPatternProvider()
    {
        $this->app->bind('primer.patternProvider', new PatternProvider($this->app->get(PatternProviderManager::class)));
    }

    protected function registerTemplateProvider()
    {
        $this->app->bind('primer.templateProvider', new PatternProvider($this->app->get(TemplateProviderManager::class)));
    }

    protected function registerDocumentProvider()
    {
        $this->app->bind('primer.documentProvider', new DocumentProvider($this->app->get(DocumentProviderManager::class)));
    }

    protected function registerPrimer()
    {
        // Build the Primer instance only when it is required
        $this->app->singleton(Primer::class, function (Config $config) {
            // This is a bit gross but it's how Timber does it :(
            $dummyLoader = new Loader();
            $twig = $dummyLoader->get_twig();

            $primer = new Primer(
                new TwigTemplateRenderer($twig),
                $this->app->get('primer.patternProvider'),
                $this->app->get('primer.templateProvider'),
                $this->app->get('primer.documentProvider')
            );

            $primer->setCustomData('routePrefix', $config->get('primer.routes.prefix', 'primer'));

            return $primer;
        });

        // Also map the the `primer` keyword to the same singleton
        $this->app->bind('primer', function () {
            return $this->app->get(Primer::class);
        });
    }

    protected function addTwigFilters()
    {
        add_filter('timber/twig', function ($twig) {
            /**
             * Add a filter to ensure we get sane HTML layout out
             */
            $twig->addFilter(new TwigFilter('dindent', function ($html) {
                $indenter = new Indenter();
                return $indenter->indent($html);
            }));

            return $twig;
        });
    }

    protected function addRoutes($prefix = 'primer')
    {
        Router::group($prefix, function ($group) {
            /**
             * Handle Patterns
             */
            $group
                ->get('patterns/{id}{state?}', 'Rareloop\Lumberjack\Primer\Controllers\PatternsController@show')
                ->where(['id' => '[\w\-\/]+', 'state' => '~[\w\-]+'])
                ->name('primer.patterns');

            /**
             * Handle Templates
             */
            $group
                ->get('templates/{id}{state?}', 'Rareloop\Lumberjack\Primer\Controllers\TemplatesController@show')
                ->where(['id' => '[\w\-\/]+', 'state' => '~[\w\-]+'])
                ->name('primer.templates');

            /**
             * Handle Documentation
             */
            $group
                ->get('docs/{id}', 'Rareloop\Lumberjack\Primer\Controllers\DocsController@show')
                ->where('id', '[\w\-\/]+')
                ->name('primer.documents');

            /**
             * Handle root
             */
            $group->get('/', 'Rareloop\Lumberjack\Primer\Controllers\RootController@show');

            /**
             * Handle Frontend Assets
             */
            $group->group('assets', function ($assetsGroup) {
                $assetsGroup->get('css/{file}', 'Rareloop\Lumberjack\Primer\Controllers\AssetsController@stylesheet')->where('file', '.+')->name('primer.assets.css');
                $assetsGroup->get('js/{file}', 'Rareloop\Lumberjack\Primer\Controllers\AssetsController@javascript')->where('file', '.+')->name('primer.assets.js');
                $assetsGroup->get('img/{file}', 'Rareloop\Lumberjack\Primer\Controllers\AssetsController@image')->where('file', '.+')->name('primer.assets.image');
            });
        });
    }

    protected function addLoadersToTwig()
    {
        add_filter('timber/loader/loader', function ($loader) {
            return new ChainLoader([
                $loader,
                new PrimerLoader($this->app->get('primer.templateProvider')),
                new PrimerLoader($this->app->get('primer.patternProvider')),
            ]);
        });
    }
}
