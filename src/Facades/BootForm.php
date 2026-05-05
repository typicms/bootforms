<?php

declare(strict_types=1);

namespace TypiCMS\BootForms\Facades;

use Illuminate\Support\Facades\Facade;

class BootForm extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'typicms.bootform';
    }
}
