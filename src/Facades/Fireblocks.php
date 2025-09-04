<?php

namespace Developerayo\FireblocksLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Fireblocks extends Facade
{
    /**
     * Get the registered name of the component
     * 
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'fireblocks';
    }
}