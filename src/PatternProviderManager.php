<?php

namespace Rareloop\Lumberjack\Primer;

use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Manager;
use Rareloop\Primer\DataParsers\JSONDataParser;
use Rareloop\Primer\FileSystemPatternProvider;

class PatternProviderManager extends Manager
{
    protected $config;

    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->config = $this->app->get(Config::class);
    }

    public function getDefaultDriver()
    {
        return $this->config->get('primer.patterns.driver', 'file');
    }

    public function createFileDriver()
    {
        $patternLoadPaths = $this->config->get('primer.patterns.file.paths', []);

        return new FileSystemPatternProvider($patternLoadPaths, 'twig', new JSONDataParser);
    }
}
