<?php
/*********************************************************************
 *
 * $Id: yocto_colorled.php 12324 2013-08-13 15:10:31Z mvuilleu $
 *
 * Implements yFindColorLed(), the high-level API for ColorLed functions
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
//--- (YColorLed definitions)
if(!defined('Y_LOGICALNAME_INVALID')) define('Y_LOGICALNAME_INVALID', Y_INVALID_STRING);
if(!defined('Y_ADVERTISEDVALUE_INVALID')) define('Y_ADVERTISEDVALUE_INVALID', Y_INVALID_STRING);
if(!defined('Y_RGBCOLOR_INVALID')) define('Y_RGBCOLOR_INVALID', Y_INVALID_UNSIGNED);
if(!defined('Y_HSLCOLOR_INVALID')) define('Y_HSLCOLOR_INVALID', Y_INVALID_UNSIGNED);
if(!defined('Y_RGBMOVE_INVALID')) define('Y_RGBMOVE_INVALID', null);
if(!defined('Y_HSLMOVE_INVALID')) define('Y_HSLMOVE_INVALID', null);
if(!defined('Y_RGBCOLORATPOWERON_INVALID')) define('Y_RGBCOLORATPOWERON_INVALID', Y_INVALID_UNSIGNED);

if(!defined('CLASS_YMOVE')) {
    define('CLASS_YMOVE',true);
    class YMove extends YAggregate {
        public $target = 0;
        public $ms = 0;
        public $moving = 0;
    };
}
//--- (end of YColorLed definitions)

/**
 * YColorLed Class: ColorLed function interface
 * 
 * Yoctopuce application programming interface
 * allows you to drive a color led using RGB coordinates as well as HSL coordinates.
 * The module performs all conversions form RGB to HSL automatically. It is then
 * self-evident to turn on a led with a given hue and to progressively vary its
 * saturation or lightness. If needed, you can find more information on the
 * difference between RGB and HSL in the section following this one.
 */
class YColorLed extends YFunction
{
    //--- (YColorLed implementation)
    const LOGICALNAME_INVALID = Y_INVALID_STRING;
    const ADVERTISEDVALUE_INVALID = Y_INVALID_STRING;
    const RGBCOLOR_INVALID = Y_INVALID_UNSIGNED;
    const HSLCOLOR_INVALID = Y_INVALID_UNSIGNED;
    const RGBCOLORATPOWERON_INVALID = Y_INVALID_UNSIGNED;

    /**
     * Returns the logical name of the RGB led.
     * 
     * @return a string corresponding to the logical name of the RGB led
     * 
     * On failure, throws an exception or returns Y_LOGICALNAME_INVALID.
     */
    public function get_logicalName()
    {   $json_val = $this->_getAttr("logicalName");
        return (is_null($json_val) ? Y_LOGICALNAME_INVALID : $json_val);
    }

    /**
     * Changes the logical name of the RGB led. You can use yCheckLogicalName()
     * prior to this call to make sure that your parameter is valid.
     * Remember to call the saveToFlash() method of the module if the
     * modification must be kept.
     * 
     * @param newval : a string corresponding to the logical name of the RGB led
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
     * Returns the current value of the RGB led (no more than 6 characters).
     * 
     * @return a string corresponding to the current value of the RGB led (no more than 6 characters)
     * 
     * On failure, throws an exception or returns Y_ADVERTISEDVALUE_INVALID.
     */
    public function get_advertisedValue()
    {   $json_val = $this->_getAttr("advertisedValue");
        return (is_null($json_val) ? Y_ADVERTISEDVALUE_INVALID : $json_val);
    }

    /**
     * Returns the current RGB color of the led.
     * 
     * @return an integer corresponding to the current RGB color of the led
     * 
     * On failure, throws an exception or returns Y_RGBCOLOR_INVALID.
     */
    public function get_rgbColor()
    {   $json_val = $this->_getAttr("rgbColor");
        return (is_null($json_val) ? Y_RGBCOLOR_INVALID : intval($json_val));
    }

    /**
     * Changes the current color of the led, using a RGB color. Encoding is done as follows: 0xRRGGBB.
     * 
     * @param newval : an integer corresponding to the current color of the led, using a RGB color
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_rgbColor($newval)
    {
        $rest_val = sprintf("0x%06x", $newval);
        return $this->_setAttr("rgbColor",$rest_val);
    }

    /**
     * Returns the current HSL color of the led.
     * 
     * @return an integer corresponding to the current HSL color of the led
     * 
     * On failure, throws an exception or returns Y_HSLCOLOR_INVALID.
     */
    public function get_hslColor()
    {   $json_val = $this->_getAttr("hslColor");
        return (is_null($json_val) ? Y_HSLCOLOR_INVALID : intval($json_val));
    }

    /**
     * Changes the current color of the led, using a color HSL. Encoding is done as follows: 0xHHSSLL.
     * 
     * @param newval : an integer corresponding to the current color of the led, using a color HSL
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_hslColor($newval)
    {
        $rest_val = sprintf("0x%06x", $newval);
        return $this->_setAttr("hslColor",$rest_val);
    }

    public function get_rgbMove()
    {   $json_val = $this->_getAttr("rgbMove");
        return (is_null($json_val) ? Y_RGBMOVE_INVALID : new YMove($json_val));
    }

    public function set_rgbMove($newval)
    {
        $rest_val = strval($newval["target"]).":".strval($newval["ms"]);
        return $this->_setAttr("rgbMove",$rest_val);
    }

    /**
     * Performs a smooth transition in the RGB color space between the current color and a target color.
     * 
     * @param rgb_target  : desired RGB color at the end of the transition
     * @param ms_duration : duration of the transition, in millisecond
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function rgbMove($int_rgb_target,$int_ms_duration)
    {
        $rest_val = strval($int_rgb_target).":".strval($int_ms_duration);
        return $this->_setAttr("rgbMove",$rest_val);
    }

    public function get_hslMove()
    {   $json_val = $this->_getAttr("hslMove");
        return (is_null($json_val) ? Y_HSLMOVE_INVALID : new YMove($json_val));
    }

    public function set_hslMove($newval)
    {
        $rest_val = strval($newval["target"]).":".strval($newval["ms"]);
        return $this->_setAttr("hslMove",$rest_val);
    }

    /**
     * Performs a smooth transition in the HSL color space between the current color and a target color.
     * 
     * @param hsl_target  : desired HSL color at the end of the transition
     * @param ms_duration : duration of the transition, in millisecond
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function hslMove($int_hsl_target,$int_ms_duration)
    {
        $rest_val = strval($int_hsl_target).":".strval($int_ms_duration);
        return $this->_setAttr("hslMove",$rest_val);
    }

    /**
     * Returns the configured color to be displayed when the module is turned on.
     * 
     * @return an integer corresponding to the configured color to be displayed when the module is turned on
     * 
     * On failure, throws an exception or returns Y_RGBCOLORATPOWERON_INVALID.
     */
    public function get_rgbColorAtPowerOn()
    {   $json_val = $this->_getAttr("rgbColorAtPowerOn");
        return (is_null($json_val) ? Y_RGBCOLORATPOWERON_INVALID : intval($json_val));
    }

    /**
     * Changes the color that the led will display by default when the module is turned on.
     * This color will be displayed as soon as the module is powered on.
     * Remember to call the saveToFlash() method of the module if the
     * change should be kept.
     * 
     * @param newval : an integer corresponding to the color that the led will display by default when the
     * module is turned on
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_rgbColorAtPowerOn($newval)
    {
        $rest_val = sprintf("0x%06x", $newval);
        return $this->_setAttr("rgbColorAtPowerOn",$rest_val);
    }

    public function logicalName()
    { return get_logicalName(); }

    public function setLogicalName($newval)
    { return set_logicalName($newval); }

    public function advertisedValue()
    { return get_advertisedValue(); }

    public function rgbColor()
    { return get_rgbColor(); }

    public function setRgbColor($newval)
    { return set_rgbColor($newval); }

    public function hslColor()
    { return get_hslColor(); }

    public function setHslColor($newval)
    { return set_hslColor($newval); }

    public function setRgbMove($newval)
    { return set_rgbMove($newval); }

    public function setHslMove($newval)
    { return set_hslMove($newval); }

    public function rgbColorAtPowerOn()
    { return get_rgbColorAtPowerOn(); }

    public function setRgbColorAtPowerOn($newval)
    { return set_rgbColorAtPowerOn($newval); }

    /**
     * Continues the enumeration of RGB leds started using yFirstColorLed().
     * 
     * @return a pointer to a YColorLed object, corresponding to
     *         an RGB led currently online, or a null pointer
     *         if there are no more RGB leds to enumerate.
     */
    public function nextColorLed()
    {   $next_hwid = YAPI::getNextHardwareId($this->_className, $this->_func);
        if($next_hwid == null) return null;
        return yFindColorLed($next_hwid);
    }

    /**
     * Retrieves an RGB led for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     * 
     * This function does not require that the RGB led is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method YColorLed.isOnline() to test if the RGB led is
     * indeed online at a given time. In case of ambiguity when looking for
     * an RGB led by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     * 
     * @param func : a string that uniquely characterizes the RGB led
     * 
     * @return a YColorLed object allowing you to drive the RGB led.
     */
    public static function FindColorLed($str_func)
    {   $obj_func = YAPI::getFunction('ColorLed', $str_func);
        if($obj_func) return $obj_func;
        return new YColorLed($str_func);
    }

    /**
     * Starts the enumeration of RGB leds currently accessible.
     * Use the method YColorLed.nextColorLed() to iterate on
     * next RGB leds.
     * 
     * @return a pointer to a YColorLed object, corresponding to
     *         the first RGB led currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstColorLed()
    {   $next_hwid = YAPI::getFirstHardwareId('ColorLed');
        if($next_hwid == null) return null;
        return self::FindColorLed($next_hwid);
    }

    //--- (end of YColorLed implementation)

    function __construct($str_func)
    {
        //--- (YColorLed constructor)
        parent::__construct('ColorLed', $str_func);
        //--- (end of YColorLed constructor)
    }
};

//--- (ColorLed functions)

/**
 * Retrieves an RGB led for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 * 
 * This function does not require that the RGB led is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method YColorLed.isOnline() to test if the RGB led is
 * indeed online at a given time. In case of ambiguity when looking for
 * an RGB led by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 * 
 * @param func : a string that uniquely characterizes the RGB led
 * 
 * @return a YColorLed object allowing you to drive the RGB led.
 */
function yFindColorLed($str_func)
{
    return YColorLed::FindColorLed($str_func);
}

/**
 * Starts the enumeration of RGB leds currently accessible.
 * Use the method YColorLed.nextColorLed() to iterate on
 * next RGB leds.
 * 
 * @return a pointer to a YColorLed object, corresponding to
 *         the first RGB led currently online, or a null pointer
 *         if there are none.
 */
function yFirstColorLed()
{
    return YColorLed::FirstColorLed();
}

//--- (end of ColorLed functions)
?>