<?php

namespace Rareloop\Lumberjack\Primer;

use Rareloop\Lumberjack\Application;
use Zend\Diactoros\Response\HTMLResponse;

class PrimerController
{
    public function pattern(PrimerProxy $primer, $section, $group, $pattern)
    {
        $id = implode('/', [$section, $group, $pattern]);

        $patterns = $primer->getPatterns([$id], !isset($_GET['minimal']));

        return new HTMLResponse($patterns);
    }

    public function group(PrimerProxy $primer, $section, $group)
    {
        $id = implode('/', [$section, $group]);

        $patterns = $primer->getPatterns([$id], !isset($_GET['minimal']));

        return new HTMLResponse($patterns);
    }

    public function section(PrimerProxy $primer, $section)
    {
        $patterns = $primer->getPatterns([$section], !isset($_GET['minimal']));

        return new HTMLResponse($patterns);
    }

    public function template(PrimerProxy $primer, $template)
    {
        $template = $primer->getTemplate($template);

        return new HTMLResponse($template);
    }

    public function all(PrimerProxy $primer)
    {
        $template = $primer->getAllPatterns(!isset($_GET['minimal']));

        return new HTMLResponse($template);
    }
}
