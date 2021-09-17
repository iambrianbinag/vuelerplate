<?php

namespace App\Services\Cache\Interfaces;

interface CacheInterface
{    
    /**
     * Run a command
     *
     * @param string $name
     * @param  array $parameters
     * @return mixed
     */
    public function command(string $name, array $parameters);
}