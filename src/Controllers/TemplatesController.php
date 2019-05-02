<?php

namespace Rareloop\Lumberjack\Primer\Controllers;

use Rareloop\Primer\Exceptions\PatternNotFoundException;
use Rareloop\Primer\Primer;
use Zend\Diactoros\Response\TextResponse;

class TemplatesController
{
    public function show(Primer $primer, $id, $state = 'default')
    {
        $state = trim($state, '~');

        try {
            return $primer->renderTemplate($id, $state);
        } catch (PatternNotFoundException $e) {
            return new TextResponse('404', 404);
        }
    }
}
