<?php

namespace Rareloop\Lumberjack\Primer;

class PrimerLoader implements \Twig_LoaderInterface
{
    // Base path for patterns lib.
    protected $patternsPath;

    public function __construct(string $patternsPath)
    {
        $this->patternsPath = $patternsPath;
    }

    /**
     * Retrieves the Primer style absolute path for the provided template name
     *
     * @param  String $name The template name
     * @return String       Absolute path to template file
     */
    protected function getPathForName($name)
    {
        // Remove the extension at this point as we add it ourselves
        $id = preg_replace('/\.twig$/', '', $name);

        // Add template.twig into path
        $path = $this->patternsPath . '/' . $id . '/template.twig';

        return $path;
    }

    /**
     * Gets the source code of a template, given its name.
     *
     * @param  string $name string The name of the template to load
     *
     * @return string The template source code
     */
    public function getSource($name)
    {
        $path = $this->getPathForName($name);

        return file_get_contents($path);
    }

    /**
     * Returns the source context for a given template logical name.
     *
     * @param string $name The template logical name
     *
     * @return Twig_Source
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getSourceContext($name)
    {
        $path = $this->getPathForName($name);

        return new \Twig_Source(file_get_contents($path), $name, $path);
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param  string $name string The name of the template to load
     *
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        return md5($name);
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     */
    public function isFresh($name, $time)
    {
        return true;
    }

    /**
     * Check if we have the source code of a template, given its name.
     *
     * @param string $name The name of the template to check if we can load
     *
     * @return bool If the template source code is handled by this loader or not
     */
    public function exists($name)
    {
        $path = $this->getPathForName($name);

        return file_exists($path);
    }
}
