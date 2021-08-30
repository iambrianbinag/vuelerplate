<?php

namespace App\Services;

class Service
{    
    /**
     * @var int
     */
    public $paginationPerPageDefault;
    
    /**
     * @var string
     */
    public $paginationOrderDirectionDefault;
    
    /**
     * @var int
     */
    public $queryChunkDefault;
    
    /**
     * Service constructor
     */
    public function __construct()
    {
        $this->paginationPerPageDefault = config('settings.pagination.per_page');

        $this->paginationOrderDirectionDefault = config('settings.pagination.order_direction');

        $this->queryChunkDefault = config('settings.chunk.default');
    }
}