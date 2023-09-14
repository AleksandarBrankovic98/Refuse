<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\MigrationCenterAPI;

class AssetFrame extends \Google\Collection
{
  protected $collection_key = 'performanceSamples';
  /**
   * @var string[]
   */
  public $attributes;
  /**
   * @var string[]
   */
  public $labels;
  protected $performanceSamplesType = PerformanceSample::class;
  protected $performanceSamplesDataType = 'array';
  /**
   * @var string
   */
  public $reportTime;
  /**
   * @var string
   */
  public $traceToken;
  protected $virtualMachineDetailsType = VirtualMachineDetails::class;
  protected $virtualMachineDetailsDataType = '';

  /**
   * @param string[]
   */
  public function setAttributes($attributes)
  {
    $this->attributes = $attributes;
  }
  /**
   * @return string[]
   */
  public function getAttributes()
  {
    return $this->attributes;
  }
  /**
   * @param string[]
   */
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  /**
   * @return string[]
   */
  public function getLabels()
  {
    return $this->labels;
  }
  /**
   * @param PerformanceSample[]
   */
  public function setPerformanceSamples($performanceSamples)
  {
    $this->performanceSamples = $performanceSamples;
  }
  /**
   * @return PerformanceSample[]
   */
  public function getPerformanceSamples()
  {
    return $this->performanceSamples;
  }
  /**
   * @param string
   */
  public function setReportTime($reportTime)
  {
    $this->reportTime = $reportTime;
  }
  /**
   * @return string
   */
  public function getReportTime()
  {
    return $this->reportTime;
  }
  /**
   * @param string
   */
  public function setTraceToken($traceToken)
  {
    $this->traceToken = $traceToken;
  }
  /**
   * @return string
   */
  public function getTraceToken()
  {
    return $this->traceToken;
  }
  /**
   * @param VirtualMachineDetails
   */
  public function setVirtualMachineDetails(VirtualMachineDetails $virtualMachineDetails)
  {
    $this->virtualMachineDetails = $virtualMachineDetails;
  }
  /**
   * @return VirtualMachineDetails
   */
  public function getVirtualMachineDetails()
  {
    return $this->virtualMachineDetails;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AssetFrame::class, 'Google_Service_MigrationCenterAPI_AssetFrame');
