<?php

namespace Rareloop\Lumberjack\Primer\Facades;

use Rareloop\Lumberjack\Facades\AbstractFacade;

class PatternProvider extends AbstractFacade
{
    protected static function accessor()
    {
        return 'primer.patternProvider';
    }
}
