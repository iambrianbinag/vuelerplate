<?php

namespace App\Services\Cache;

use App\Services\Cache\Interfaces\CacheInterface;
use Illuminate\Support\Facades\Redis;

class RedisService implements CacheInterface
{        
    /**
     * @var Redis
     */
    protected $redis;
    
    /**
     * RedisService constructor
     * 
     * @param  string $connectionName
     */
    public function __construct(string $connectionName = null)
    {
        $this->redis = Redis::connection($connectionName);
    }

    /**
     * Run Redis command
     *
     * @param  string $name
     * @param  array $parameters
     * @return mixed
     */
    public function command(string $name, array $parameters = [])
    {
        return $this->redis->command($name, $parameters);
    }
}