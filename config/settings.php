<?php 

return [

  /*
  |--------------------------------------------------------------------------
  | Custom settings
  |--------------------------------------------------------------------------
  | 
  */
  
  'pagination' => [
    'per_page' => env('PAGINATION_PER_PAGE', 10),
    'order_direction' => env('PAGINATION_ORDER_DIRECTION', 'desc'),
  ],

  'chunk' => [
    'default' => env('CHUNK_DEFAULT', 200),
  ],

];