<?php

namespace App\Utilities;

class Utils 
{  
  /**
   * Get array difference recursively
   *
   * @param array $arr1
   * @param array $arr2
   * @return array
   */
  public static function arrayRecursiveDiff(array $arr1, array $arr2) {
    $outputDiff = [];

    foreach ($arr1 as $key => $value){
        if(array_key_exists($key, $arr2)){
            if(is_array($value)){
                $recursiveDiff = self::arrayRecursiveDiff($value, $arr2[$key]);
                if (count($recursiveDiff)){
                    $outputDiff[$key] = $recursiveDiff;
                }
            } else if(!in_array($value, $arr2)){
                $outputDiff[$key] = $value;
            } else{
              // Do nothing...
            }
        } else if(!in_array($value, $arr2)){
            $outputDiff[$key] = $value;
        } else {
          // Do nothing...
        }
    }

    return $outputDiff;
  } 
}