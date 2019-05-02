<?php

namespace Rareloop\Lumberjack\Primer\Facades;

use Blast\Facades\AbstractFacade;

class TemplateProvider extends AbstractFacade
{
    protected static function accessor()
    {
        return 'primer.templateProvider';
    }
}
