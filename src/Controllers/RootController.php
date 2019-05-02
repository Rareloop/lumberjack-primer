<?php

namespace Rareloop\Lumberjack\Primer\Controllers;

use Rareloop\Lumberjack\Http\Router;
use Rareloop\Primer\Exceptions\PatternNotFoundException;
use Rareloop\Primer\Primer;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\TextResponse;

class RootController
{
    public function show(Primer $primer, Router $router)
    {
        $menu = $primer->getMenu();

        $sections = ['documents', 'patterns'];

        foreach ($sections as $sectionName) {
            if (!$menu->hasSection($sectionName)) {
                continue;
            }

            $section = $menu->getSection($sectionName);

            if ($section->count() === 0) {
                continue;
            }

            $url = $router->url('primer.' . $sectionName, [
                'id' => $section->getIds()[0],
            ]);

            return new RedirectResponse($url);
        }

        return new TextResponse('404', 404);
    }
}
