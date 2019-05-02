<?php

namespace Rareloop\Lumberjack\Primer;

use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Manager;
use Rareloop\Primer\DocumentParsers\ChainDocumentParser;
use Rareloop\Primer\DocumentParsers\MarkdownDocumentParser;
use Rareloop\Primer\DocumentParsers\TwigDocumentParser;
use Rareloop\Primer\DocumentParsers\YAMLDocumentParser;
use Rareloop\Primer\FileSystemDocumentProvider;
use Timber\Loader;

class DocumentProviderManager extends Manager
{
    protected $config;

    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->config = $this->app->get(Config::class);
    }

    public function getDefaultDriver()
    {
        return $this->config->get('primer.documents.driver', 'file');
    }

    public function createFileDriver()
    {
        $dummyLoader = new Loader();
        $twig = $dummyLoader->get_twig();

        $documentLoadPaths = $this->config->get('primer.documents.file.paths', []);

        /**
         * Create a Document Provider that will parse YAML, Twig and Markdown
         */
        return new FileSystemDocumentProvider(
            $documentLoadPaths,
            'md',
            new ChainDocumentParser(
                [
                    new YAMLDocumentParser,
                    new TwigDocumentParser($twig),
                    new MarkdownDocumentParser
                ]
            )
        );
    }
}
