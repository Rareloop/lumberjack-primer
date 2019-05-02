<?php

namespace Rareloop\Lumberjack\Primer\Facades;

use Blast\Facades\AbstractFacade;

class DocumentProvider extends AbstractFacade
{
    protected static function accessor()
    {
        return 'primer.documentProvider';
    }
}
