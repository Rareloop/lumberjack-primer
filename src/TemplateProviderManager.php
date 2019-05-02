<?php

namespace Rareloop\Lumberjack\Primer;

use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Manager;
use Rareloop\Primer\DataParsers\JSONDataParser;
use Rareloop\Primer\FileSystemPatternProvider;

class TemplateProviderManager extends Manager
{
    protected $config;

    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->config = $this->app->get(Config::class);
    }

    public function getDefaultDriver()
    {
        return $this->config->get('primer.templates.driver', 'file');
    }

    public function createFileDriver()
    {
        $templateLoadPaths = $this->config->get('primer.templates.file.paths', []);

        return new FileSystemPatternProvider($templateLoadPaths, 'twig', new JSONDataParser);
    }
}
