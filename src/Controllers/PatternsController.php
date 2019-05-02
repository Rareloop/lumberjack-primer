<?php

namespace Rareloop\Lumberjack\Primer\Controllers;

use Rareloop\Primer\Exceptions\PatternNotFoundException;
use Rareloop\Primer\Primer;
use Zend\Diactoros\Response\TextResponse;

class PatternsController
{
    public function show(Primer $primer, $id, $state = 'default')
    {
        $state = trim($state, '~');

        try {
            if (isset($_GET['fullscreen'])) {
                return $primer->renderPatternWithoutChrome($id, $state);
            }

            if ($state !== 'default') {
                return $primer->renderPattern($id, $state);
            }

            return $primer->renderPatterns($id);
        } catch (PatternNotFoundException $e) {
            return new TextResponse('404', 404);
        }
    }
}
