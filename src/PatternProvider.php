<?php

namespace Rareloop\Lumberjack\Primer;

use Rareloop\Lumberjack\Manager;
use Rareloop\Primer\Contracts\PatternProvider as ProviderProviderInterface;
use Rareloop\Primer\Contracts\TemplateProvider as TemplateProviderInterface;
use Rareloop\Primer\Document;
use Rareloop\Primer\Pattern;

class PatternProvider implements ProviderProviderInterface, TemplateProviderInterface
{
    protected $manager;

    public function __construct(Manager $manager)
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
     * Get a list of all the known pattern id's
     *
     * @return array
     */
    public function allPatternIds() : array
    {
        return $this->manager->allPatternIds();
    }

    /**
     * Does a given pattern exist?
     *
     * @param  string $id The pattern ID
     * @return bool
     */
    public function patternExists(string $id) : bool
    {
        return $this->manager->patternExists($id);
    }

    /**
     * Does a given state exists for a given pattern?
     *
     * All valid pattern's will return true for the `default` state
     *
     * @param  string      $id    The pattern ID
     * @param  string      $state The state name
     * @return bool
     */
    public function patternHasState(string $id, string $state = 'default') : bool
    {
        return $this->manager->patternHasState($id, $state);
    }

    /**
     * Retrieve a Pattern
     *
     * @param  string $id    The pattern ID
     * @param  string $state The state name
     * @return Rareloop\Primer\Pattern
     */
    public function getPattern(string $id, string $state = 'default') : Pattern
    {
        return $this->manager->getPattern($id, $state);
    }

    /**
     * Get the data for the given pattern and state
     *
     * @param  string $id    [description]
     * @param  string $state [description]
     * @return [type]        [description]
     */
    public function getPatternStateData(string $id, string $state = 'default') : array
    {
        return $this->manager->getPatternStateData($id, $state);
    }

    /**
     * Get the contents of the template for a given pattern
     *
     * @param  string $id The pattern ID
     * @return string
     */
    public function getPatternTemplate(string $id) : string
    {
        return $this->manager->getPatternTemplate($id);
    }

    /**
     * Get when a pattern template was last modified
     *
     * @param  string $id The pattern ID
     * @return int        Unix timestamp of when last modified
     */
    public function getPatternTemplateLastModified(string $id) : int
    {
        return $this->manager->getPatternTemplateLastModified($id);
    }
}
