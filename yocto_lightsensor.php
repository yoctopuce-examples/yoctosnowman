<?php
/*********************************************************************
 *
 * $Id: yocto_lightsensor.php 12324 2013-08-13 15:10:31Z mvuilleu $
 *
 * Implements yFindLightSensor(), the high-level API for LightSensor functions
 *
 * - - - - - - - - - License information: - - - - - - - - - 
 *
 *  Copyright (C) 2011 and beyond by Yoctopuce Sarl, Switzerland.
 *
 *  Yoctopuce Sarl (hereafter Licensor) grants to you a perpetual
 *  non-exclusive license to use, modify, copy and integrate this
 *  file into your software for the sole purpose of interfacing 
 *  with Yoctopuce products. 
 *
 *  You may reproduce and distribute copies of this file in 
 *  source or object form, as long as the sole purpose of this
 *  code is to interface with Yoctopuce products. You must retain 
 *  this notice in the distributed source file.
 *
 *  You should refer to Yoctopuce General Terms and Conditions
 *  for additional information regarding your rights and 
 *  obligations.
 *
 *  THE SOFTWARE AND DOCUMENTATION ARE PROVIDED 'AS IS' WITHOUT
 *  WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING 
 *  WITHOUT LIMITATION, ANY WARRANTY OF MERCHANTABILITY, FITNESS 
 *  FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT. IN NO
 *  EVENT SHALL LICENSOR BE LIABLE FOR ANY INCIDENTAL, SPECIAL,
 *  INDIRECT OR CONSEQUENTIAL DAMAGES, LOST PROFITS OR LOST DATA, 
 *  COST OF PROCUREMENT OF SUBSTITUTE GOODS, TECHNOLOGY OR 
 *  SERVICES, ANY CLAIMS BY THIRD PARTIES (INCLUDING BUT NOT 
 *  LIMITED TO ANY DEFENSE THEREOF), ANY CLAIMS FOR INDEMNITY OR
 *  CONTRIBUTION, OR OTHER SIMILAR COSTS, WHETHER ASSERTED ON THE
 *  BASIS OF CONTRACT, TORT (INCLUDING NEGLIGENCE), BREACH OF
 *  WARRANTY, OR OTHERWISE.
 *
 *********************************************************************/


//--- (return codes)
//--- (end of return codes)
//--- (YLightSensor definitions)
if(!defined('Y_LOGICALNAME_INVALID')) define('Y_LOGICALNAME_INVALID', Y_INVALID_STRING);
if(!defined('Y_ADVERTISEDVALUE_INVALID')) define('Y_ADVERTISEDVALUE_INVALID', Y_INVALID_STRING);
if(!defined('Y_UNIT_INVALID')) define('Y_UNIT_INVALID', Y_INVALID_STRING);
if(!defined('Y_CURRENTVALUE_INVALID')) define('Y_CURRENTVALUE_INVALID', Y_INVALID_FLOAT);
if(!defined('Y_LOWESTVALUE_INVALID')) define('Y_LOWESTVALUE_INVALID', Y_INVALID_FLOAT);
if(!defined('Y_HIGHESTVALUE_INVALID')) define('Y_HIGHESTVALUE_INVALID', Y_INVALID_FLOAT);
if(!defined('Y_CURRENTRAWVALUE_INVALID')) define('Y_CURRENTRAWVALUE_INVALID', Y_INVALID_FLOAT);
if(!defined('Y_CALIBRATIONPARAM_INVALID')) define('Y_CALIBRATIONPARAM_INVALID', Y_INVALID_STRING);
if(!defined('Y_RESOLUTION_INVALID')) define('Y_RESOLUTION_INVALID', Y_INVALID_FLOAT);
//--- (end of YLightSensor definitions)

/**
 * YLightSensor Class: LightSensor function interface
 * 
 * The Yoctopuce application programming interface allows you to read an instant
 * measure of the sensor, as well as the minimal and maximal values observed.
 */
class YLightSensor extends YFunction
{
    //--- (YLightSensor implementation)
    const LOGICALNAME_INVALID = Y_INVALID_STRING;
    const ADVERTISEDVALUE_INVALID = Y_INVALID_STRING;
    const UNIT_INVALID = Y_INVALID_STRING;
    const CURRENTVALUE_INVALID = Y_INVALID_FLOAT;
    const LOWESTVALUE_INVALID = Y_INVALID_FLOAT;
    const HIGHESTVALUE_INVALID = Y_INVALID_FLOAT;
    const CURRENTRAWVALUE_INVALID = Y_INVALID_FLOAT;
    const CALIBRATIONPARAM_INVALID = Y_INVALID_STRING;
    const RESOLUTION_INVALID = Y_INVALID_FLOAT;
    public  $_calibrationOffset = 0;

    /**
     * Returns the logical name of the light sensor.
     * 
     * @return a string corresponding to the logical name of the light sensor
     * 
     * On failure, throws an exception or returns Y_LOGICALNAME_INVALID.
     */
    public function get_logicalName()
    {   $json_val = $this->_getAttr("logicalName");
        return (is_null($json_val) ? Y_LOGICALNAME_INVALID : $json_val);
    }

    /**
     * Changes the logical name of the light sensor. You can use yCheckLogicalName()
     * prior to this call to make sure that your parameter is valid.
     * Remember to call the saveToFlash() method of the module if the
     * modification must be kept.
     * 
     * @param newval : a string corresponding to the logical name of the light sensor
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_logicalName($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("logicalName",$rest_val);
    }

    /**
     * Returns the current value of the light sensor (no more than 6 characters).
     * 
     * @return a string corresponding to the current value of the light sensor (no more than 6 characters)
     * 
     * On failure, throws an exception or returns Y_ADVERTISEDVALUE_INVALID.
     */
    public function get_advertisedValue()
    {   $json_val = $this->_getAttr("advertisedValue");
        return (is_null($json_val) ? Y_ADVERTISEDVALUE_INVALID : $json_val);
    }

    /**
     * Returns the measuring unit for the measured value.
     * 
     * @return a string corresponding to the measuring unit for the measured value
     * 
     * On failure, throws an exception or returns Y_UNIT_INVALID.
     */
    public function get_unit()
    {   $json_val = $this->_getFixedAttr("unit");
        return (is_null($json_val) ? Y_UNIT_INVALID : $json_val);
    }

    public function set_currentValue($newval)
    {
        $rest_val = strval(round($newval*65536.0));
        return $this->_setAttr("currentValue",$rest_val);
    }

    /**
     * Returns the current measured value.
     * 
     * @return a floating point number corresponding to the current measured value
     * 
     * On failure, throws an exception or returns Y_CURRENTVALUE_INVALID.
     */
    public function get_currentValue()
    {   $json_val = $this->_getAttr("currentValue");
        if(isset($this->_cache['calibrationParam'])) {
            $res = YAPI::applyCalibration($this);
            if($res != Y_CURRENTVALUE_INVALID) return $res;
        }
        return (is_null($json_val) ? Y_CURRENTVALUE_INVALID : round($json_val/6553.6) / 10);
    }

    /**
     * Changes the sensor-specific calibration parameter so that the current value
     * matches a desired target (linear scaling).
     * 
     * @param calibratedVal : the desired target value.
     * 
     * Remember to call the saveToFlash() method of the module if the
     * modification must be kept.
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function calibrate($float_calibratedVal)
    {
        $rest_val = strval(round($float_calibratedVal*65536.0));
        return $this->_setAttr("currentValue",$rest_val);
    }

    /**
     * Changes the recorded minimal value observed.
     * 
     * @param newval : a floating point number corresponding to the recorded minimal value observed
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_lowestValue($newval)
    {
        $rest_val = strval(round($newval*65536.0));
        return $this->_setAttr("lowestValue",$rest_val);
    }

    /**
     * Returns the minimal value observed.
     * 
     * @return a floating point number corresponding to the minimal value observed
     * 
     * On failure, throws an exception or returns Y_LOWESTVALUE_INVALID.
     */
    public function get_lowestValue()
    {   $json_val = $this->_getAttr("lowestValue");
        return (is_null($json_val) ? Y_LOWESTVALUE_INVALID : round($json_val/6553.6) / 10);
    }

    /**
     * Changes the recorded maximal value observed.
     * 
     * @param newval : a floating point number corresponding to the recorded maximal value observed
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_highestValue($newval)
    {
        $rest_val = strval(round($newval*65536.0));
        return $this->_setAttr("highestValue",$rest_val);
    }

    /**
     * Returns the maximal value observed.
     * 
     * @return a floating point number corresponding to the maximal value observed
     * 
     * On failure, throws an exception or returns Y_HIGHESTVALUE_INVALID.
     */
    public function get_highestValue()
    {   $json_val = $this->_getAttr("highestValue");
        return (is_null($json_val) ? Y_HIGHESTVALUE_INVALID : round($json_val/6553.6) / 10);
    }

    /**
     * Returns the unrounded and uncalibrated raw value returned by the sensor.
     * 
     * @return a floating point number corresponding to the unrounded and uncalibrated raw value returned by the sensor
     * 
     * On failure, throws an exception or returns Y_CURRENTRAWVALUE_INVALID.
     */
    public function get_currentRawValue()
    {   $json_val = $this->_getAttr("currentRawValue");
        return (is_null($json_val) ? Y_CURRENTRAWVALUE_INVALID : $json_val/65536.0);
    }

    public function get_calibrationParam()
    {   $json_val = $this->_getAttr("calibrationParam");
        return (is_null($json_val) ? Y_CALIBRATIONPARAM_INVALID : $json_val);
    }

    public function set_calibrationParam($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("calibrationParam",$rest_val);
    }

    /**
     * Configures error correction data points, in particular to compensate for
     * a possible perturbation of the measure caused by an enclosure. It is possible
     * to configure up to five correction points. Correction points must be provided
     * in ascending order, and be in the range of the sensor. The device will automatically
     * perform a linear interpolation of the error correction between specified
     * points. Remember to call the saveToFlash() method of the module if the
     * modification must be kept.
     * 
     * For more information on advanced capabilities to refine the calibration of
     * sensors, please contact support@yoctopuce.com.
     * 
     * @param rawValues : array of floating point numbers, corresponding to the raw
     *         values returned by the sensor for the correction points.
     * @param refValues : array of floating point numbers, corresponding to the corrected
     *         values for the correction points.
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function calibrateFromPoints($floatArr_rawValues,$floatArr_refValues)
    {
        $rest_val = $this->_encodeCalibrationPoints($floatArr_rawValues,$floatArr_refValues);
        return $this->_setAttr("calibrationParam",$rest_val);
    }

    public function loadCalibrationPoints(&$floatArrRef_rawValues,&$floatArrRef_refValues)
    {
        return $this->_decodeCalibrationPoints($floatArrRef_rawValues,$floatArrRef_refValues);
    }

    /**
     * Returns the resolution of the measured values. The resolution corresponds to the numerical precision
     * of the values, which is not always the same as the actual precision of the sensor.
     * 
     * @return a floating point number corresponding to the resolution of the measured values
     * 
     * On failure, throws an exception or returns Y_RESOLUTION_INVALID.
     */
    public function get_resolution()
    {   $json_val = $this->_getAttr("resolution");
        return (is_null($json_val) ? Y_RESOLUTION_INVALID : ($json_val > 100 ? 1.0 / round(65536.0/$json_val) : 0.001 / round(67.0/$json_val)));
    }

    public function logicalName()
    { return get_logicalName(); }

    public function setLogicalName($newval)
    { return set_logicalName($newval); }

    public function advertisedValue()
    { return get_advertisedValue(); }

    public function unit()
    { return get_unit(); }

    public function setCurrentValue($newval)
    { return set_currentValue($newval); }

    public function currentValue()
    { return get_currentValue(); }

    public function setLowestValue($newval)
    { return set_lowestValue($newval); }

    public function lowestValue()
    { return get_lowestValue(); }

    public function setHighestValue($newval)
    { return set_highestValue($newval); }

    public function highestValue()
    { return get_highestValue(); }

    public function currentRawValue()
    { return get_currentRawValue(); }

    public function calibrationParam()
    { return get_calibrationParam(); }

    public function setCalibrationParam($newval)
    { return set_calibrationParam($newval); }

    public function resolution()
    { return get_resolution(); }

    /**
     * Continues the enumeration of light sensors started using yFirstLightSensor().
     * 
     * @return a pointer to a YLightSensor object, corresponding to
     *         a light sensor currently online, or a null pointer
     *         if there are no more light sensors to enumerate.
     */
    public function nextLightSensor()
    {   $next_hwid = YAPI::getNextHardwareId($this->_className, $this->_func);
        if($next_hwid == null) return null;
        return yFindLightSensor($next_hwid);
    }

    /**
     * Retrieves a light sensor for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     * 
     * This function does not require that the light sensor is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method YLightSensor.isOnline() to test if the light sensor is
     * indeed online at a given time. In case of ambiguity when looking for
     * a light sensor by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     * 
     * @param func : a string that uniquely characterizes the light sensor
     * 
     * @return a YLightSensor object allowing you to drive the light sensor.
     */
    public static function FindLightSensor($str_func)
    {   $obj_func = YAPI::getFunction('LightSensor', $str_func);
        if($obj_func) return $obj_func;
        return new YLightSensor($str_func);
    }

    /**
     * Starts the enumeration of light sensors currently accessible.
     * Use the method YLightSensor.nextLightSensor() to iterate on
     * next light sensors.
     * 
     * @return a pointer to a YLightSensor object, corresponding to
     *         the first light sensor currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstLightSensor()
    {   $next_hwid = YAPI::getFirstHardwareId('LightSensor');
        if($next_hwid == null) return null;
        return self::FindLightSensor($next_hwid);
    }

    //--- (end of YLightSensor implementation)

    function __construct($str_func)
    {
        //--- (YLightSensor constructor)
        parent::__construct('LightSensor', $str_func);
        //--- (end of YLightSensor constructor)
    }
};

//--- (LightSensor functions)

/**
 * Retrieves a light sensor for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 * 
 * This function does not require that the light sensor is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method YLightSensor.isOnline() to test if the light sensor is
 * indeed online at a given time. In case of ambiguity when looking for
 * a light sensor by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 * 
 * @param func : a string that uniquely characterizes the light sensor
 * 
 * @return a YLightSensor object allowing you to drive the light sensor.
 */
function yFindLightSensor($str_func)
{
    return YLightSensor::FindLightSensor($str_func);
}

/**
 * Starts the enumeration of light sensors currently accessible.
 * Use the method YLightSensor.nextLightSensor() to iterate on
 * next light sensors.
 * 
 * @return a pointer to a YLightSensor object, corresponding to
 *         the first light sensor currently online, or a null pointer
 *         if there are none.
 */
function yFirstLightSensor()
{
    return YLightSensor::FirstLightSensor();
}

//--- (end of LightSensor functions)
?>