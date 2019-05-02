<?php

namespace Rareloop\Lumberjack\Primer\Facades;

use Blast\Facades\AbstractFacade;

class PatternProvider extends AbstractFacade
{
    protected static function accessor()
    {
        return 'primer.patternProvider';
    }
}
