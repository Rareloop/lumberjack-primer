<?php

namespace Rareloop\Lumberjack\Primer\Facades;

use LogicException;
use Blast\Facades\AbstractFacade;

class Primer extends AbstractFacade
{
    protected static function accessor()
    {
        return 'primer';
    }

    public static function currentPatternStateData($state = 'default')
    {
        $currentPatternId = Primer::currentPatternId();

        if (Primer::currentPatternState() === 'default') {
            throw new LogicException('Cannot use "Primer::currentPatternStateData()" when rendering the default state as it will lead to an infinite loop.');
        }

        return Primer::getPatternStateData($currentPatternId, $state);
    }

    public static function currentTemplateStateData($state = 'default')
    {
        $currentTemplateId = Primer::currentTemplateId();

        if (Primer::currentTemplateState() === 'default') {
            throw new LogicException('Cannot use "Primer::currentTemplateStateData()" when rendering the default state as it will lead to an infinite loop.');
        }

        return Primer::getTemplateStateData($currentTemplateId, $state);
    }
}
