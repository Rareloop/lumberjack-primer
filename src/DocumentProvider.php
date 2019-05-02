<?php

namespace Rareloop\Lumberjack\Primer;

use Rareloop\Primer\Contracts\DocumentProvider as DocumentProviderInterface;
use Rareloop\Primer\Document;

class DocumentProvider implements DocumentProviderInterface
{
    protected $manager;

    public function __construct(DocumentProviderManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param  string    $driver
     * @param  \Closure  $callback
     * @return $this
     */
    public function extend($driver, \Closure $callback)
    {
        $this->manager->extend($driver, $callback);

        return $this;
    }

    /**
     * Get a list of all the known document id's
     *
     * @return array
     */
    public function allDocumentIds() : array
    {
        return $this->manager->allDocumentIds();
    }

    /**
     * Retrieve a Document
     *
     * @param  string $id    The pattern ID
     * @param  string $state The state name
     * @return Rareloop\Primer\Document
     */
    public function getDocument(string $id) : Document
    {
        return $this->manager->getDocument($id);
    }
}
