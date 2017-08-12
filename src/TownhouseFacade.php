<?php

namespace TomSchlick\Townhouse;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TomSchlick\Townhouse\Tenant
 */
class TownhouseFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'townhouse';
    }
}
