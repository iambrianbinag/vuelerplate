<?php

namespace App\Services\Logs;

use App\Services\Logs\Exceptions\InvalidDescriptionLogException;
use App\Services\Logs\Exceptions\InvalidPropertyLogKeyException;
use App\Services\Service;
use App\Utils\Util;
use Spatie\Activitylog\Contracts\Activity;
use Illuminate\Database\Eloquent\Model;

class LogService extends Service
{ 
  /** @var string */
  private $logName;

  /** @var bool */
  private $submitEmptyLogs;

  /** @var bool */
  private $logOnlyDirty;

  /** @var Model */
  private $performedOn;

  /** @var Model */
  private $causedBy;

  /** @var array */
  private $withProperties;

  /** @var string  */
  private $description;

  /** @var array */
  private $allowedPropertyKeys = ['attributes', 'old'];

    
  /**
   * Set log name
   *
   * @param  string $logName
   * @return ActivityLog
   */
  public function setLogName(string $logName)
  {
    $this->logName = $logName;

    return $this;
  }
  
  /**
   * Set submit empty logs
   *
   * @param  bool $submitEmptyLogs
   * @return ActivityLog
   */
  public function setSubmitEmptyLogs(bool $submitEmptyLogs)
  {
    $this->submitEmptyLogs = $submitEmptyLogs;

    return $this;
  }
  
  /**
   * Set log only dirty
   *
   * @param  bool $logOnlyDirty
   * @return ActivityLog
   */
  public function setLogOnlyDirty(bool $logOnlyDirty)  
  {
    $this->logOnlyDirty = $logOnlyDirty;

    return $this;
  }
  
  /**
   * Set performed on
   *
   * @param  Model $performedOn
   * @return ActivityLog
   */
  public function setPerformedOn(Model $performedOn)
  {
    $this->performedOn = $performedOn;

    return $this;
  }
  
  /**
   * Set caused by
   *
   * @param  Model $causedBy
   * @return ActivityLog
   */
  public function setCausedBy($causedBy)
  {
    $this->causedBy = $causedBy;

    return $this;
  }
  
  /**
   * Set with properties
   *
   * @param  array $withProperties
   * @return ActivityLog
   */
  public function setWithProperties(array $withProperties)
  {
    $propertyKeys = array_keys($withProperties);
    if(count(array_diff($propertyKeys, $this->allowedPropertyKeys)) > 0){
      $allowedPropertyKeysString = implode(', ', $this->allowedPropertyKeys);
      throw new InvalidPropertyLogKeyException("The property key is invalid, the keys must be $allowedPropertyKeysString");
    }

    $this->withProperties = $withProperties;

    return $this;
  }
  
  /**
   * Set description
   *
   * @param  string $description
   * @return ActivityLog
   */
  public function setDescription(string $description)
  {
    $this->description = $description;

    return $this;
  }
  
  /**
   * Insert activity log 
   *
   * @return Activity
   */
  public function save()
  {
    $properties = $this->withProperties;
    $oldProperties = $properties['old'] ?? null;
    $newProperties = $properties['attributes'] ?? null;

    if($this->submitEmptyLogs == false && count($newProperties) == 0 && is_null($oldProperties)){
      return;
    }
    
    if(!is_null($oldProperties) && !is_null($newProperties)){
      $changes = Util::arrayRecursiveDiff($oldProperties, $newProperties);
      foreach($oldProperties as $oldPropertyKey => $oldPropertyValue){
        $newPropertyValue = $newProperties[$oldPropertyKey] ?? null;
        if(is_array($oldPropertyValue) && is_array($newPropertyValue)){
          $changesFromOld = Util::arrayRecursiveDiff($oldPropertyValue, $newPropertyValue);
          $changesFromNew = Util::arrayRecursiveDiff($newPropertyValue, $oldPropertyValue);
          if(count($changesFromOld) !== count($changesFromNew)){
            $changes[$oldPropertyKey] = array_merge($changesFromOld, $changesFromNew);
          }
        }
      }

      if($this->submitEmptyLogs == false && count($changes) == 0){
        return;
      }
      if($this->logOnlyDirty == true ){
        $properties['old'] = collect($properties['old'])->only(array_keys($changes))->all();
        $properties['attributes'] =  collect($properties['attributes'])->only(array_keys($changes))->all();
      }
    }

    return activity()
        ->performedOn($this->performedOn)
        ->causedBy($this->causedBy)
        ->withProperties($properties)
        ->tap(function(Activity $activity){
          $activity->log_name = $this->logName;
        })
        ->log($this->description);
  }
    
  /**
   * Validate description in array of descriptions
   *
   * @param  string $description
   * @param  array $descriptions
   * @throws InvalidDescriptionLogException
   * @return ActivityLog
   */
  public function validateDescription(string $description, array $descriptions)
  {
    if(!in_array($description, $descriptions, true)){
      $validDescriptionsString = implode(', ', $descriptions);
      throw new InvalidDescriptionLogException("The description $description is invalid, it must be $validDescriptionsString");
    };

    return $this;
  }
}