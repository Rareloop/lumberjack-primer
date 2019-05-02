<?php

namespace Rareloop\Lumberjack\Primer\Controllers;

use Rareloop\Primer\Exceptions\PatternNotFoundException;
use Rareloop\Primer\Primer;
use Zend\Diactoros\Response\TextResponse;

class DocsController
{
    public function show(Primer $primer, $id)
    {
        try {
            return $primer->renderDocument($id);
        } catch (DocumentNotFoundException $e) {
            return new TextResponse('404', 404);
        }
    }
}
