<?php

namespace Rareloop\Lumberjack\Primer\Facades;

use Rareloop\Lumberjack\Facades\AbstractFacade;

class DocumentProvider extends AbstractFacade
{
    protected static function accessor()
    {
        return 'primer.documentProvider';
    }
}
