<?php
/*********************************************************************
 *
 * $Id: yocto_api.php 12326 2013-08-13 15:52:20Z mvuilleu $
 *
 * High-level programming interface, common to all modules
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
 *  THE SOFTWARE AND DOCUMENTATION ARE PROVIDED "AS IS" WITHOUT
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

define('Y_INVALID_UNSIGNED', -1);
define('Y_INVALID_SIGNED',   -2147483648);
define('Y_INVALID_FLOAT',    -66666666.66666666);
define('Y_INVALID_STRING',   '!INVALID!');
        
//--- (generated code: YModule definitions)
// Yoctopuce error codes, also used by default as function return value
define('YAPI_SUCCESS',                 0);     // everything worked allright
define('YAPI_NOT_INITIALIZED',         -1);    // call yInitAPI() first !
define('YAPI_INVALID_ARGUMENT',        -2);    // one of the arguments passed to the function is invalid
define('YAPI_NOT_SUPPORTED',           -3);    // the operation attempted is (currently) not supported
define('YAPI_DEVICE_NOT_FOUND',        -4);    // the requested device is not reachable
define('YAPI_VERSION_MISMATCH',        -5);    // the device firmware is incompatible with this API version
define('YAPI_DEVICE_BUSY',             -6);    // the device is busy with another task and cannot answer
define('YAPI_TIMEOUT',                 -7);    // the device took too long to provide an answer
define('YAPI_IO_ERROR',                -8);    // there was an I/O problem while talking to the device
define('YAPI_NO_MORE_DATA',            -9);    // there is no more data to read from
define('YAPI_EXHAUSTED',               -10);   // you have run out of a limited ressource, check the documentation
define('YAPI_DOUBLE_ACCES',            -11);   // you have two process that try to acces to the same device
define('YAPI_UNAUTHORIZED',            -12);   // unauthorized access to password-protected device
define('YAPI_RTC_NOT_READY',           -13);   // real-time clock has not been initialized (or time was lost)

if(!defined('Y_PERSISTENTSETTINGS_LOADED')) define('Y_PERSISTENTSETTINGS_LOADED', 0);
if(!defined('Y_PERSISTENTSETTINGS_SAVED')) define('Y_PERSISTENTSETTINGS_SAVED', 1);
if(!defined('Y_PERSISTENTSETTINGS_MODIFIED')) define('Y_PERSISTENTSETTINGS_MODIFIED', 2);
if(!defined('Y_PERSISTENTSETTINGS_INVALID')) define('Y_PERSISTENTSETTINGS_INVALID', -1);
if(!defined('Y_BEACON_OFF')) define('Y_BEACON_OFF', 0);
if(!defined('Y_BEACON_ON')) define('Y_BEACON_ON', 1);
if(!defined('Y_BEACON_INVALID')) define('Y_BEACON_INVALID', -1);
if(!defined('Y_USBBANDWIDTH_SIMPLE')) define('Y_USBBANDWIDTH_SIMPLE', 0);
if(!defined('Y_USBBANDWIDTH_DOUBLE')) define('Y_USBBANDWIDTH_DOUBLE', 1);
if(!defined('Y_USBBANDWIDTH_INVALID')) define('Y_USBBANDWIDTH_INVALID', -1);
if(!defined('Y_PRODUCTNAME_INVALID')) define('Y_PRODUCTNAME_INVALID', Y_INVALID_STRING);
if(!defined('Y_SERIALNUMBER_INVALID')) define('Y_SERIALNUMBER_INVALID', Y_INVALID_STRING);
if(!defined('Y_LOGICALNAME_INVALID')) define('Y_LOGICALNAME_INVALID', Y_INVALID_STRING);
if(!defined('Y_PRODUCTID_INVALID')) define('Y_PRODUCTID_INVALID', Y_INVALID_UNSIGNED);
if(!defined('Y_PRODUCTRELEASE_INVALID')) define('Y_PRODUCTRELEASE_INVALID', Y_INVALID_UNSIGNED);
if(!defined('Y_FIRMWARERELEASE_INVALID')) define('Y_FIRMWARERELEASE_INVALID', Y_INVALID_STRING);
if(!defined('Y_LUMINOSITY_INVALID')) define('Y_LUMINOSITY_INVALID', Y_INVALID_UNSIGNED);
if(!defined('Y_UPTIME_INVALID')) define('Y_UPTIME_INVALID', Y_INVALID_UNSIGNED);
if(!defined('Y_USBCURRENT_INVALID')) define('Y_USBCURRENT_INVALID', Y_INVALID_UNSIGNED);
if(!defined('Y_REBOOTCOUNTDOWN_INVALID')) define('Y_REBOOTCOUNTDOWN_INVALID', Y_INVALID_SIGNED);
//--- (end of generated code: YModule definitions)

define('Y_FUNCTIONDESCRIPTOR_INVALID',   '!INVALID!');
define('Y_HARDWAREID_INVALID',           '!INVALID!');
define('Y_FUNCTIONID_INVALID',           '!INVALID!');
define('Y_FRIENDLYNAME_INVALID',         '!INVALID!');

// yInitAPI constants (not really useful in PHP, but defined for code portability)
define('Y_DETECT_NONE',                  0);
define('Y_DETECT_USB',                   1);
define('Y_DETECT_NET',                   2);
define('Y_DETECT_ALL',                   Y_DETECT_USB | Y_DETECT_NET);

// Maximum device request timeout
define('YAPI_BLOCKING_REQUEST_TIMEOUT',  30000);

// 
// Class used to report exceptions within Yocto-API
// Do not instantiate directly
//
class YAPI_Exception extends Exception {}

// Pseudo class used to create structures in PHP
class YAggregate {}

//
// Structure used internally to report results of a query. It only uses public attributes.
// Do not instantiate directly
//
class YAPI_YReq
{
    public $errorType;
    public $errorMsg;
    public $result;

    function __construct($int_errType, $str_errMsg, $obj_result)
    {
        $this->errorType = $int_errType;
        $this->errorMsg = $str_errMsg;
        $this->result = $obj_result;
    }
}

//
// YTcpHub Class (used internally)
//
// Instances of this class represent a VirtualHub or a networked Yoctopuce device
// to which we can connect to get access to device functions. For historical reasons,
// this class is mostly used like a structure, rather than a real object.
//
class YTcpHub
{
    // attributes
    public $rooturl;                    // root url of the hub (without auth parameters)
    public $streamaddr;                 // stream address of the hub ("tcp://addr:port")
    public $notifurl;                   // notification file used by this hub
    public $notifReq;                   // notification request, or null if not open
    public $notifPos;                   // absolute position in notification stream
    public $devListValidity;            // default validity of updateDeviceList
    public $devListExpires;             // timestamp of next useful updateDeviceList
    public $devListReq;                 // updateDeviceList request, or null if not open
    public $serialByYdx;                // serials by hub-specific devYdx
    public $retryDelay;                 // delay before reconnecting in case of error
    public $retryExpires;               // timestamp of next reconnection attempt
    public $missing;                    // list of missing devices during updateDeviceList
    public $writeProtected;             // true if an adminPassword is set
    public $user;                       // user for authentication
    public $callbackCache;              // pre-parsed cache for callback-based API
    protected $realm;                   // hub authentication realm
    protected $pwd;                     // password for authentication
    protected $nonce;                   // lasPrint(t received nonce
    protected $opaque;                  // last received opaque
    protected $ha1;                     // our authentication ha1 string
    protected $nc;                      // nounce usage count
    
    function __construct($rooturl, $auth)
    {
        $this->rooturl = $rooturl;
        $this->streamaddr = str_replace('http://', 'tcp://', $rooturl);
        $colon = strpos($auth, ':');
        if($colon === false) {
            $this->user = $auth;
            $this->pwd = '';
        } else {
            $this->user = substr($auth, 0, $colon);
            $this->pwd = substr($auth, $colon+1);
        }        
        $this->notifurl = 'not.byn';
        $this->notifHandle = null;
        $this->notifPos = -1;
        $this->devListValidity = 500;
        $this->devListExpires = 0;
        $this->serialByYdx = Array();
        $this->retryDelay = 15;
        $this->retryExpires = 0;
        $this->writeProtected = false;
    }
    

    function verfiyStreamAddr(&$errmsg='')
    {
        if($this->streamaddr == 'tcp://CALLBACK/') {
            $data = file_get_contents('php://input','rb');
            if($data=="") {
                $errmsg ="RegisterHub(callback) used without posting YoctoAPI data";
                Print("\n!YoctoAPI:$errmsg\n");
                $this->callbackCache = Array();
                return -1;
            } else {
                $this->callbackCache = json_decode($data,true);
                if(is_null($this->callbackCache)) {
                    $errmsg ="invalid data:[\n$data\n]";
                    Print("\n!YoctoAPI:$errmsg\n");
                    $this->callbackCache = Array();
                    return -1;
                }
            }
        } else {
            $this->callbackCache = NULL;            
        }
        return 0;
    }
    // Update the hub internal variables according
    // to a received header with WWW-Authenticate
    function parseWWWAuthenticate($header)
    {
        $pos = stripos($header, "\r\nWWW-Authenticate:");
        if($pos === false) return;
        $header = substr($header, $pos+19);
        $eol = strpos($header, "\r");
        if($eol !== false) {
            $header = substr($header, 0, $eol);
        }
        $tags = null;
        if(preg_match_all('~(?<tag>\w+)="(?<value>[^"]*)"~m', $header, $tags) == false) {
            return;
        }
        $this->realm = '';
        $this->qop = '';
        $this->nonce = '';
        $this->opaque = '';
        for($i = 0; $i < sizeof($tags['tag']); $i++) {
            if($tags['tag'][$i] == "realm") {
                $this->realm = $tags['value'][$i];
            } else if($tags['tag'][$i] == "qop") {
                $this->qop = $tags['value'][$i];
            } else if($tags['tag'][$i] == "nonce") {
                $this->nonce = $tags['value'][$i];
            } else if($tags['tag'][$i] == "opaque") {
                $this->opaque = $tags['value'][$i];
            }
        }
        $this->nc = 0;
        $this->ha1 = md5($this->user.':'.$this->realm.':'.$this->pwd);
    }

    // Return an Authorization header for a given request
    function getAuthorization($request)
    {
        if($this->user == '' || $this->realm == '') return '';
        $this->nc++;
        $pos = strpos($request, ' ');
        $method = substr($request, 0, $pos);
        $uri = substr($request, $pos+1);
        $nc = sprintf("%08x", $this->nc);
        $cnonce = sprintf("%08x", mt_rand(0,0x7fffffff));
        $ha1 = $this->ha1;
        $ha2 = md5("{$method}:{$uri}");
        $nonce = $this->nonce;
        $response = md5("{$ha1}:{$nonce}:{$nc}:{$cnonce}:auth:{$ha2}");
        $res = 'Authorization: Digest username="'.$this->user.'", realm="'.$this->realm.'",'.
               ' nonce="'.$this->nonce.'", uri="'.$uri.'", qop=auth, nc='.$nc.','.
               ' cnonce="'.$cnonce.'", response="'.$response.'", opaque="'.$this->opaque.'"';
        return "$res\r\n";
    }
    
    // Return true if a hub is just a virtual cache (for callback mode)
    function isCachedHub()
    {
        return !is_null($this->callbackCache);
    }
    
    // Execute a query for cached hub (for callback mode)
    function cachedQuery($str_query, $str_body)
    {
        if(substr($str_query, 0, 5) == 'POST ') {
            $boundary = '???';
            $endb = strpos($str_body, "\r");
            if(substr($str_body, 0, 2)=='--' && $endb > 2 && $endb < 20) {
                $boundary = substr($str_body, 2, $endb-2);
            }
            Printf("\n@YoctoAPI:$str_query %d:%s\n%s", strlen($str_body), $boundary, $str_body);
            return "OK\r\n\r\n";
        }
        if(substr($str_query, 0, 4) != 'GET ') 
            return NULL;
        if(strpos($str_query, '?') === FALSE ||
           strpos($str_query, '/logs.txt') !== FALSE ||
           strpos($str_query, '/logger.json') !== FALSE ||
           strpos($str_query, '/ping.txt') !== FALSE ||
           strpos($str_query, '/files.json?a=dir') !== FALSE) {
            // read request, load from cache
            $parts = explode(' ',$str_query);
            $url = $parts[1];
            $getmodule = (strpos($url, 'api/module.json') !== FALSE);
            if($getmodule) {
                $url = str_replace('api/module.json','api.json',$url);
            }
            if(!isset($this->callbackCache[$url])) {
                Print("\n!YoctoAPI:$url is not preloaded, adding to list");
                Print("\n@YoctoAPI:+$url\n");
                return NULL;
            }
            // Print("\n[$url found]\n");
            $jsonres = $this->callbackCache[$url];
            if($getmodule) $jsonres = $jsonres['module'];
            if(strpos($str_query, '.json') !== FALSE) {
                $jsonres = json_encode($jsonres);
            }
            return "OK\r\n\r\n$jsonres";
        } else {
            // change request, print to output stream
            Print("\n@YoctoAPI:$str_query\n");
            return "OK\r\n\r\n";
        }
    }
}

//
// YTcpReq Class (used internally)
//
// Instances of this class represent an open TCP connection to a HTTP socket.
// The class handles digest authorization transparently.
//
class YTcpReq
{
    // attributes
    public $hub;                        // the YTcpHub to which we connect
    public $async;                      // true if the request is async
    public $skt;                        // stream socket
    public $request;                    // request to be sent
    public $reqbody;                    // request body to send, if any
    public $boundary;                   // request body boundary, if used
    public $meta;                       // HTTP headers received in reply
    public $reply;                      // reply buffer
    public $retryCount;                 // number of retries for this request
    // the following attributes should not be taken for granted unless eof() returns true
    public $errorType;                  // status of current connection
    public $errorMsg;                   // last error message
    public $reqcnt;
        
    public static $totalTcpReqs = 0;
    
    function __construct($hub, $request, $async, $reqbody='')
    {
        $pos = strpos($request, "\r");
        if($pos !== false) {
            $request = substr($request, 0, $pos);
        }
        $boundary = '';
        if($reqbody != '') {
            do {
                $boundary = sprintf("Zz%06xzZ", mt_rand(0,0xffffff)); 
            } while(strpos($reqbody, $boundary) !== false);
            $reqbody = "--{$boundary}\r\n{$reqbody}\r\n--{$boundary}--\r\n";
        }
        $this->hub = $hub;
        $this->async = $async;
        $this->request = trim($request);
        $this->reqbody = $reqbody;
        $this->boundary = $boundary;
        $this->meta = '';
        $this->reply = '';
        $this->retryCount = 0;
        $this->errorType = YAPI_IO_ERROR;
        $this->errorMsg = 'could not open connection';
        $this->reqcnt = ++YTcpReq::$totalTcpReqs;
    }    
    
    function eof()
    {
        if(!is_null($this->skt)) {
            // there is still activity going on
            return false;
        }
        if($this->meta != '' && $this->errorType == YAPI_SUCCESS) {
            // connection was done and ended successfully
            return true;
        }
        if($this->retryCount > 3) {
            // connection permanently failed
            return true;
        }
        // connection is expected to be reopened
        return false;        
    }
    
    function newsocket(&$errno, &$errstr, $timeout)
    {
        // for now, use client socket only since server sockets
        // for callbacks are not reliably available on a public server
        return @stream_socket_client($this->hub->streamaddr, $errno, $errstr, $timeout);
    }
    
    function process(&$errmsg = '')
    {
        if($this->eof()) {
            if($this->errorType != YAPI_SUCCESS) {
                $errmsg = $this->errorMsg;
            }
            return $this->errorType;
        }
        if(is_null($this->skt)) {
            // need to reopen connection
            if($this->hub->isCachedHub()) {
                // special handling for "connection-less" callback mode
                $data = $this->hub->cachedQuery($this->request, $this->reqbody);
                if(is_null($data)) {
                    $this->errorType = YAPI_NOT_SUPPORTED;
                    $this->errorMsg = "query is not available in callback mode";                    
                    $this->retryCount = 99;                                
                    return YAPI_SUCCESS; // will propagate error later if needed
                }
                $skt = fopen('data:text/plain;base64,'.base64_encode($data), 'rb');
                if ($skt === false) {
                    $this->errorType = YAPI_IO_ERROR;
                    $this->errorMsg = "failed to open data stream ($errno): $errstr";
                    $this->retryCount = 99;                                
                    return YAPI_SUCCESS; // will propagate error later if needed
                }
                stream_set_blocking($skt, 0);
                $this->skt = $skt;
            } else {
                $skt = $this->newsocket($errno, $errstr, YAPI_BLOCKING_REQUEST_TIMEOUT / 1000);
                if ($skt === false) {
                    $this->errorType = YAPI_IO_ERROR;
                    $this->errorMsg = "failed to open socket ($errno): $errstr";
                    $this->retryCount++;
                    return YAPI_SUCCESS; // will retry later
                }
                stream_set_blocking($skt, 0);
                $request = $this->request . " \r\n" . // no HTTP/1.1 suffix for light queries
                   $this->hub->getAuthorization($this->request);
                if($this->boundary != '') {
                    $request .= "Content-Type: multipart/form-data; boundary={$this->boundary}\r\n";
                }            
                $request .= "Connection: close\r\n\r\n";
                $reqlen = strlen($request);
                if (fwrite($skt, $request, $reqlen) != $reqlen) {
                    fclose($skt);
                    $this->errorType = YAPI_IO_ERROR;
                    $this->errorMsg = "failed to write to socket";
                    $this->retryCount++;
                    return YAPI_SUCCESS; // will retry later                
                }
                $this->skt = $skt;
            }
        } else {
            // read anything available on current socket, and process authentication headers
            while(true) {
                $data = fread($this->skt, 8192);
                //Printf("[read %d bytes]\n",strlen($data));
                if(strlen($data) == 0) break;
                if($this->reply == '' && strpos($this->meta, "\r\n\r\n") === false) {
                    $this->meta .= $data;
                    $eoh = strpos($this->meta, "\r\n\r\n");
                    if($eoh !== false) {
                        // fully received header
                        $this->reply = substr($this->meta, $eoh+4);
                        $this->meta = substr($this->meta, 0, $eoh+4);
                        $firstline = substr($this->meta, 0, strpos($this->meta, "\r"));
                        if(substr($firstline, 0, 12) == 'HTTP/1.1 401') {
                            // authentication required
                            $this->errorType = YAPI_UNAUTHORIZED;
                            $this->errorMsg = "Authentication required";
                            fclose($this->skt);
                            $this->skt = null;
                            $this->hub->parseWWWAuthenticate($this->meta);
                            if($this->hub->user != '') {
                                $this->meta = '';
                                $this->reply = '';
                                $this->retryCount++;
                            } else {
                                $this->retryCount = 99;                                
                            }
                            return YAPI_SUCCESS; // will propagate error later if needed
                        }
                    }
                } else {
                    $this->reply .= $data;
                }
                // so far so good
                $this->errorType = YAPI_SUCCESS;
            }
            // write request body, if any, once header is fully received
            if($this->reqbody != '' && strpos($this->meta, "\r\n\r\n") !== false) {
                $bodylen = strlen($this->reqbody);
                $written = fwrite($this->skt, $this->reqbody, $bodylen);
                if($written > 0) {
                    $this->reqbody = substr($this->reqbody, $written);
                }
            }
            if(feof($this->skt)) {
                fclose($this->skt);
                $this->skt = null;
            }
        }
        return YAPI_SUCCESS;
    }

    function close()
    {
        if($this->skt) fclose($this->skt);
    }
}

// 
// YFunctionType Class (used internally)
//
// Instances of this class stores everything we know about a given type of function:
// Mapping between function logical names and Hardware ID as discovered on hubs,
// and existing instances of YFunction (either already connected or simply requested).
// To keep it simple, this implementation separates completely the name resolution
// mechanism, implemented using the yellow pages, and the storage and retrieval of 
// existing YFunction instances.
//

class YFunctionType
{
    // private attributes, to be used within yocto_api only
    protected $_className;
    protected $_connectedFns;           // functions requested and available, by Hardware Id
    protected $_requestedFns;           // functions requested but not yet known, by any type of name
    protected $_hwIdByName;             // hash table of function Hardware Id by logical name
    protected $_nameByHwId;             // hash table of function logical name by Hardware Id
    protected $_valueByHwId;            // hash table of function advertised value by logical name

    function __construct($str_classname)
    {
        if(ord($str_classname[strlen($str_classname)-1]) <= 57) throw new Exception("Invalid fnction type",-1);
        $this->_className     = $str_classname;
        $this->_connectedFns  = Array();
        $this->_requestedFns  = Array();
        $this->_hwIdByName    = Array();
        $this->_nameByHwId    = Array();
        $this->_valueByHwId   = Array();
    }

    // Index a single function given by HardwareId and logical name; store any advertised value
    // Return true iff there was a logical name discrepency
    public function reindexFunction($str_hwid, $str_name, $str_val)
    {
        $currname = '';
        $res = false;
        if(isset($this->_nameByHwId[$str_hwid])) {
            $currname = $this->_nameByHwId[$str_hwid];
        }
        if($currname == '') {
            if($str_name != '') {
                $this->_nameByHwId[$str_hwid] = $str_name;
                $res = true;
            }
        } else if($currname != $str_name) {
            if($this->_hwIdByName[$currname] == $str_hwid)
                unset($this->_hwIdByName[$currname]);
            if($str_name != '') {
                $this->_nameByHwId[$str_hwid] = $str_name;
            } else {
                unset($this->_nameByHwId[$str_hwid]);
            }
            $res = true;
        }
        if($str_name != '') {
            $this->_hwIdByName[$str_name] = $str_hwid;
        }
        if(!is_null($str_val)) {
            $this->_valueByHwId[$str_hwid] = $str_val;
        } else {
            if(!isset($this->_valueByHwId[$str_hwid])) {
                $this->_valueByHwId[$str_hwid] = '';
            }
        }
        return $res;
    }

    // Forget a disconnected function given by HardwareId
    public function forgetFunction($str_hwid)
    {
        if(isset($this->_nameByHwId[$str_hwid])) {
            $currname = $this->_nameByHwId[$str_hwid];
            if($currname != '' && $this->_hwIdByName[$currname] == $str_hwid) {
                unset($this->_hwIdByName[$currname]);
            }
            unset($this->_nameByHwId[$str_hwid]);
        }
        if(isset($this->_valueByHwId[$str_hwid])) {
            unset($this->_valueByHwId[$str_hwid]);
        }
    }

    // Find the exact Hardware Id of the specified function, if currently connected
    // If device is not known as connected, return a clean error
    // This function will not cause any network access
    public function resolve($str_func)
    {
        // Try to resolve str_func to a known Function instance, if possible, without any device access
        $dotpos = strpos($str_func, '.');
        if($dotpos === false) {
            // First case: str_func is the logicalname of a function
            if(isset($this->_hwIdByName[$str_func])) {
                return new YAPI_YReq(YAPI_SUCCESS, 
                                     'no error', 
                                     $this->_hwIdByName[$str_func]);
            }

            // fallback to assuming that str_func is a logicalname or serial number of a module
            // with an implicit function name (like serial.module for instance)
            $dotpos = strlen($str_func);
            $str_func .= '.'.strtolower($this->_className[0]).substr($this->_className,1);
        }

        // Second case: str_func is in the form: device_id.function_id

        // quick lookup for a known pure hardware id
        if(isset($this->_valueByHwId[$str_func])) {
            return new YAPI_YReq(YAPI_SUCCESS, 
                                 'no error', 
                                 $str_func);
        }
        if($dotpos>0){

            // either the device id is a logical name, or the function is unknown
            $devid = substr($str_func, 0, $dotpos);
            $funcid = substr($str_func, $dotpos+1);
            $dev = YAPI::getDevice($devid);
            if(!$dev) {
                return new YAPI_YReq(YAPI_DEVICE_NOT_FOUND,
                                    "Device [$devid] not online",
                                    null);
            }
            $serial = $dev->getSerialNumber();
            $res = "$serial.$funcid";
            if(isset($this->_valueByHwId[$res])) {
                return new YAPI_YReq(YAPI_SUCCESS,
                                    'no error',
                                    $res);
            }

            // not found neither, may be funcid is a function logicalname
            $nfun = $dev->functionCount();
            for($i = 0; $i < $nfun; $i++) {
                $res = "$serial.".$dev->functionId($i);
                if(isset($this->_nameByHwId[$res])) {
                    $name = $this->_nameByHwId[$res];
                    if($name == $funcid) {
                        return new YAPI_YReq(YAPI_SUCCESS,
                                            'no error',
                                            $res);
                    }
                }
            }
        }else{
            $funcid = substr($str_func, 1);
            // only functionId  (ie ".tempearture")
            foreach(array_keys($this->_connectedFns) as $hwid_str){
                $pos = strpos($hwid_str, '.');
                $function = substr($hwid_str, $pos+1);
                //print("search for $funcid in {$this->_className} $function\n");
                if($function == $funcid){
                    return new YAPI_YReq(YAPI_SUCCESS,
                                        'no error',
                                        $hwid_str);
                }
            }
        }

        return new YAPI_YReq(YAPI_DEVICE_NOT_FOUND, 
                             "No function [$funcid] found on device [$serial]", 
                             null);
    }

    public function getFriendlyName($str_func)
    {
        $resolved = $this->resolve($str_func);
        if($resolved->errorType != YAPI_SUCCESS) {
            return $resolved;
        }

        if($this->_className =="Module"){
            $friend =$resolved->result;
            if($this->_nameByHwId[$resolved->result]!='')
                $friend = $this->_nameByHwId[$resolved->result];
            return new YAPI_YReq(YAPI_SUCCESS,
                                'no error',
                                $friend);
        }else{
            $pos = strpos($resolved->result,'.');
            $serial_mod = substr($resolved->result,0,$pos);
            $friend_mod_full = YAPI::getFriendlyNameFunction("Module", $serial_mod)->result;
            $friend_mod = substr($friend_mod_full,0,strpos($friend_mod_full,'.'));
            $friend_func = substr($resolved->result,$pos+1);
            if(isset($this->_nameByHwId[$resolved->result]) && $this->_nameByHwId[$resolved->result]!= '')
                $friend_func = $this->_nameByHwId[$resolved->result];
            return new YAPI_YReq(YAPI_SUCCESS,
                                'no error',
                                $friend_mod.'.'.$friend_func);
        }
    }


    // Retrieve a function object by hardware id, updating the indexes on the fly if needed
    public function setFunction($str_func, $obj_func)
    {
        $funres = $this->resolve($str_func);
        if($funres->errorType == YAPI_SUCCESS) {
            // the function has been located on a device
            $this->_connectedFns[$funres->result] = $obj_func;
        } else {
            // the function is still abstract
            $this->_requestedFns[$str_func] = $obj_func;
        }
    }

    // Retrieve a function object by hardware id, updating the indexes on the fly if needed
    public function getFunction($str_func)
    {
        $funres = $this->resolve($str_func);
        if($funres->errorType == YAPI_SUCCESS) {
            // the function has been located on a device
            if(isset($this->_connectedFns[$funres->result]))
                return $this->_connectedFns[$funres->result];

            if(isset($this->_requestedFns[$str_func])) {
                $req_fn = $this->_requestedFns[$str_func];
                $this->_connectedFns[$funres->result] = $req_fn;
                unset($this->_requestedFns[$str_func]);
                return $req_fn;
            }
        } else {
            // the function is still abstract
            if(isset($this->_requestedFns[$str_func]))
                return $this->_requestedFns[$str_func];
        }
        return null;
    }

    // Retrieve a function advertised value by hardware id
    public function setFunctionValue($str_hwid, $str_pubval)
    {
        if(isset($this->_connectedFns[$str_hwid])) {
            $conn_fn = $this->_connectedFns[$str_hwid];
            if(!is_null($conn_fn->_getValueCallback())) {
                if(!isset($this->_valueByHwId[$str_hwid]) ||
                   $this->_valueByHwId[$str_hwid] != $str_pubval) {
                    YAPI::addValueEvent($conn_fn, $str_pubval);
                }
            }
        }
        $this->_valueByHwId[$str_hwid] = $str_pubval;
    }

    // Retrieve a function advertised value by hardware id
    public function getFunctionValue($str_hwid)
    {
        return $this->_valueByHwId[$str_hwid];
    }

    // Find the the hardwareId of the first instance of a given function class
    public function getFirstHardwareId()
    {
        foreach($this->_valueByHwId as $res => $value) return $res;
        return null;
    }

    // Find the hardwareId for the next instance of a given function class
    public function getNextHardwareId($str_hwid)
    {
        foreach($this->_valueByHwId as $iter_hwid => $value) {
            if($str_hwid == "!")
                return $iter_hwid;
            if($str_hwid == $iter_hwid)
                $str_hwid = "!";
        }
        return null; // no more instance found
    }
}

// 
// YDevice Class (used internally)
//
// This class is used to store everything we know about connected Yocto-Devices.
// Instances are created when devices are discovered in the white pages
// (or registered manually, for root hubs) and then used to keep track of
// device naming changes. When a device or a function is renamed, this
// object forces the local indexes to be immediately updated, even if not
// yet fully propagated through the yellow pages of the device hub.
//
// In order to regroup multiple function queries on the same physical device,
// this class implements a device-wide API string cache (agnostic of API content).
// This is in addition to the function-specific cache implemented in YFunction.
//

class YDevice
{
    // private attributes, to be used within yocto_api only
    protected $_rootUrl;
    protected $_serialNumber;
    protected $_logicalName;
    protected $_productName;
    protected $_productId;
    protected $_beacon;
    protected $_devYdx;
    protected $_cache;
    protected $_functions;
    protected $_ongoingReq;
    public    $_lastErrorType;
    public    $_lastErrorMsg;

    function __construct($str_rooturl, $obj_wpRec=null, $obj_ypRecs=null)
    {
        $this->_rootUrl       = $str_rooturl;
        $this->_serialNumber  = '';
        $this->_logicalName   = '';
        $this->_productName   = '';
        $this->_productId     = 0;
        $this->_beacon        = 0;
        $this->_devYdx        = -1;
        $this->_cache         = Array('_expiration' => 0, '_json' => '');
        $this->_functions     = Array();
        $this->_lastErrorType = YAPI_SUCCESS;
        $this->_lastErrorMsg  = 'no error';

        if(!is_null($obj_wpRec)) {
            // preload values from white pages, if provided
            $this->_serialNumber = $obj_wpRec['serialNumber'];
            $this->_logicalName  = $obj_wpRec['logicalName'];
            $this->_productName  = $obj_wpRec['productName'];
            $this->_productId    = $obj_wpRec['productId'];
            $this->_beacon       = $obj_wpRec['beacon'];
            $this->_devYdx       = (isset($obj_wpRec['index']) ? $obj_wpRec['index'] : -1);
            $this->_updateFromYP($obj_ypRecs);
            YAPI::reindexDevice($this);
        } else {
            // preload values from device directly
            $this->refresh();
        }
    }

    // Throw an exception, keeping track of it in the object itself
    protected function _throw($int_errType, $str_errMsg, $obj_retVal)
    {
        $this->_lastErrorType = $int_errType;
        $this->_lastErrorMsg = $str_errMsg;

        if(YAPI::$exceptionsDisabled) {
            return $obj_retVal;
        }
        // throw an exception
        throw new YAPI_Exception($str_errMsg, $int_errType);
    }

    // Update device cache and YAPI function lists from yp records
    protected function _updateFromYP($obj_ypRecs)
    {
        $funidx = 0;
        foreach($obj_ypRecs as  $ypRec) {
            foreach($ypRec as $rec) {
                $hwid = $rec['hardwareId'];
                $dotpos = strpos($hwid, '.');
                if(substr($hwid, 0, $dotpos) == $this->_serialNumber) {
                    if(isset($rec['index'])) {
                        $funydx = $rec['index'];
                    } else {
                        $funydx = $funidx;
                    }
                    $this->_functions[$funydx] = Array(substr($hwid, $dotpos+1), $rec["logicalName"]);
                }
            }
        }
    }

    // Return the root URL used to access a device (including the trailing slash)
    public function getRootUrl()
    {
        return $this->_rootUrl;
    }

    // Return the serial number of the device, as found during discovery
    public function getSerialNumber()
    {
        return $this->_serialNumber;
    }

    // Return the logical name of the device, as found during discovery
    public function getLogicalName()
    {
        return $this->_logicalName;
    }

    // Return the product name of the device, as found during discovery
    public function getProductName()
    {
        return $this->_productName;
    }

    // Return the product Id of the device, as found during discovery
    public function getProductId()
    {
        return $this->_productId;
    }

    // Return the beacon state of the device, as found during discovery
    public function getBeacon()
    {
        return $this->_beacon;
    }

    // Return the hub-specific devYdx of the device, as found during discovery
    public function getDevYdx()
    {
        return $this->_devYdx;
    }

    // Return a string that describes the device (serial number, logical name or root URL)
    public function describe()
    {
        $res = $this->_rootUrl;
        if($this->_serialNumber != '') {
            $res = $this->_serialNumber;
            if($this->_logicalName != '') {
                $res .= ' ('.($this->_logicalName).')';
            }
        }
        return $this->_productName.' '.$res;
    }

    // Prepare to run a request on a device (finish any async device before if needed
    // (called by devRequest)
    public function prepRequest($tcpreq)
    {
        if(!is_null($this->_ongoingReq)) {
            while(!$this->_ongoingReq->eof()) {
                YAPI::_handleEvents_internal(100);
            }
        }
        $this->_ongoingReq = $tcpreq;
    }
    
    // Get the whole REST API string for a device, from cache if possible
    public function requestAPI()
    {
        if($this->_cache['_expiration'] > YAPI::GetTickCount()) {
            return new YAPI_YReq(YAPI_SUCCESS, 'no error', $this->_cache['_json']);
        }
        $yreq = YAPI::devRequest($this->_rootUrl, 'GET /api.json');
        if($yreq->errorType != YAPI_SUCCESS) return $yreq;
        $this->_cache['_expiration'] = YAPI::GetTickCount() + YAPI::$defaultCacheValidity;
        $this->_cache['_json'] = $yreq->result;
        return $yreq;
    }

    // Reload a device API (store in cache), and update YAPI function lists accordingly
    // Intended to be called within UpdateDeviceList only
    public function refresh()
    {
        $yreq = $this->requestAPI();
        if($yreq->errorType != YAPI_SUCCESS) {
            return $this->_throw($yreq->errorType, $yreq->errorMsg, $yreq->errorType);
        }
        $loadval = json_decode($yreq->result, true);
        if(json_last_error() != JSON_ERROR_NONE) {
            return $this->_throw(YAPI_IO_ERROR, 'Request failed, could not parse API result for '.$this->_rootUrl,
                                 YAPI_IO_ERROR);
        }
        $this->_cache['_expiration'] = YAPI::GetTickCount() + YAPI::$defaultCacheValidity;
        $this->_cache['_json'] = $yreq->result;

        $func;
        $reindex = false;
        if($this->_productName == "") {
            // parse module and function names for the first time
            foreach($loadval as $func => $iface) {    
                if($func == 'module') {
                    $this->_serialNumber = $iface['serialNumber'];
                    $this->_logicalName  = $iface['logicalName'];
                    $this->_productName  = $iface['productName'];
                    $this->_productId    = $iface['productId'];
                    $this->_beacon       = $iface['beacon'];
                } else if($func == 'services') {
                    $this->_updateFromYP($iface['yellowPages']);
                }
            }
            $reindex = true;
        } else {
            // parse module and refresh names if needed
            foreach($loadval as $func => $iface) {    
                if($func == 'module') {
                    if($this->_logicalName != $iface['logicalName']) {
                        $this->_logicalName = $iface['logicalName'];
                        $reindex = true;
                    }
                    $this->_beacon = $iface['beacon'];
                } else if($func != 'services') {
                    if(isset($iface[$func]['logicalName'])) 
                        $name = $iface[$func]['logicalName'];
                    else
                        $name = $this->_logicalName;
                    if(isset($iface[$func]['advertisedValue'])) {
                        $pubval = $iface[$func]['advertisedValue'];
                        YAPI::setFunctionValue($this->_serialNumber.'.'.$func, $pubval);
                    }
                    foreach($this->_functions as $funydx => $fundef) {
                        if($fundef[0] == $func) {
                            if($fundef[1] != $name) {
                                $this->_functions[$funydx][1] = $name;
                                $reindex = true;
                            }
                            break;
                        }
                    }
                }
            }
        }
        if($reindex) {
            YAPI::reindexDevice($this);
        }
        return YAPI_SUCCESS;
    }

    // Force the REST API string in cache to expire immediately
    public function dropCache()
    {
        $this->_cache['_expiration'] = 0;
    }

    /**
     * Returns the number of functions (beside the "module" interface) available on the module.
     * 
     * @return the number of functions on the module
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function functionCount()
    {
        return sizeof($this->_functions);
    }

    /**
     * Retrieves the hardware identifier of the <i>n</i>th function on the module.
     * 
     * @param functionIndex : the index of the function for which the information is desired, starting at
     * 0 for the first function.
     * 
     * @return a string corresponding to the unambiguous hardware identifier of the requested module function
     * 
     * On failure, throws an exception or returns an empty string.
     */
    public function functionId($int_functionIndex)
    {
        if($int_functionIndex < sizeof($this->_functions)) {
            return $this->_functions[$int_functionIndex][0];
        }
        return '';
    }

    /**
     * Retrieves the logical name of the <i>n</i>th function on the module.
     * 
     * @param functionIndex : the index of the function for which the information is desired, starting at
     * 0 for the first function.
     * 
     * @return a string corresponding to the logical name of the requested module function
     * 
     * On failure, throws an exception or returns an empty string.
     */
    public function functionName($int_functionIndex)
    {
        if($int_functionIndex < sizeof($this->_functions)) {
            return $this->_functions[$int_functionIndex][1];
        }
        return '';
    }

    /**
     * Retrieves the advertised value of the <i>n</i>th function on the module.
     * 
     * @param functionIndex : the index of the function for which the information is desired, starting at
     * 0 for the first function.
     * 
     * @return a short string (up to 6 characters) corresponding to the advertised value of the requested
     * module function
     * 
     * On failure, throws an exception or returns an empty string.
     */
    public function functionValue($int_functionIndex)
    {
        if($int_functionIndex < sizeof($this->_functions)) {
            return YAPI::getFunctionValue($this->_serialNumber.'.'.$this->_functions[$int_functionIndex][0]);
        }
        return '';
    }
}

// 
// YAPI Context
//
// This class provides the high-level entry points to access Functions, stores
// an indexes instances of the Device object and of FunctionType collections.
//

class YAPI
{
//--- (generated code: return codes)
    const SUCCESS               = 0;       // everything worked allright
    const NOT_INITIALIZED       = -1;      // call yInitAPI() first !
    const INVALID_ARGUMENT      = -2;      // one of the arguments passed to the function is invalid
    const NOT_SUPPORTED         = -3;      // the operation attempted is (currently) not supported
    const DEVICE_NOT_FOUND      = -4;      // the requested device is not reachable
    const VERSION_MISMATCH      = -5;      // the device firmware is incompatible with this API version
    const DEVICE_BUSY           = -6;      // the device is busy with another task and cannot answer
    const TIMEOUT               = -7;      // the device took too long to provide an answer
    const IO_ERROR              = -8;      // there was an I/O problem while talking to the device
    const NO_MORE_DATA          = -9;      // there is no more data to read from
    const EXHAUSTED             = -10;     // you have run out of a limited ressource, check the documentation
    const DOUBLE_ACCES          = -11;     // you have two process that try to acces to the same device
    const UNAUTHORIZED          = -12;     // unauthorized access to password-protected device
    const RTC_NOT_READY         = -13;     // real-time clock has not been initialized (or time was lost)
//--- (end of generated code: return codes)

    // yInitAPI constants (not really useful in JavaScript)
    const DETECT_NONE           = 0;
    const DETECT_USB            = 1;
    const DETECT_NET            = 2;
    const DETECT_ALL            = 3;

    protected static $_hubs;           // array of root urls
    protected static $_devs;           // hash table of devices, by serial number
    protected static $_snByUrl;        // serial number for each device, by URL
    protected static $_snByName;       // serial number for each device, by name
    protected static $_fnByType;       // functions by type
    protected static $_lastErrorType;
    protected static $_lastErrorMsg;
    protected static $_firstArrival;
    protected static $_pendingCallbacks;
    protected static $_arrivalCallback;
    protected static $_namechgCallback;
    protected static $_removalCallback;
    protected static $_pendingValues;
    protected static $_pendingRequests;
    protected static $_calibHandlers;
    protected static $_decExp;

    // PUBLIC GLOBAL SETTINGS

    // Default cache validity (in [ms]) before reloading data from device. This saves a lots of trafic.
    // Note that a value under 2 ms makes little sense since a USB bus itself has a 2ms roundtrip period
    public static $defaultCacheValidity = 5;

    // Switch to turn off exceptions and use return codes instead, for source-code compatibility
    // with languages without exception support like C
    public static $exceptionsDisabled = false; // set to true if you want error codes instead of exceptions

    public static function _init()
    {
        // private
        self::$_hubs = Array();
        self::$_devs = Array();
        self::$_snByUrl = Array();
        self::$_snByName = Array();
        self::$_fnByType = Array();     
        self::$_lastErrorType = YAPI_SUCCESS;
        self::$_lastErrorMsg = 'no error';
        self::$_firstArrival = true;
        self::$_pendingCallbacks = Array();
        self::$_arrivalCallback = null;
        self::$_namechgCallback = null;
        self::$_removalCallback = null;
        self::$_pendingValues = Array();
        self::$_pendingRequests = Array();

        self::$_decExp = Array( 
            1.0e-6, 1.0e-5, 1.0e-4, 1.0e-3, 1.0e-2, 1.0e-1, 1.0, 
            1.0e1, 1.0e2, 1.0e3, 1.0e4, 1.0e5, 1.0e6, 1.0e7, 1.0e8, 1.0e9);
        
        self::$_fnByType['Module'] = new YFunctionType('Module');

        register_shutdown_function('YAPI::flushConnections');
    }

    // Throw an exception, keeping track of it in the object itself
    protected static function _throw($int_errType, $str_errMsg, $obj_retVal)
    {
        self::$_lastErrorType = $int_errType;
        self::$_lastErrorMsg = $str_errMsg;

        if(self::$exceptionsDisabled) {
            return $obj_retVal;
        }
        // throw an exception
        throw new YAPI_Exception($str_errMsg, $int_errType);
    }

    // Update the list of known devices internally
    public static function _updateDeviceList_internal($bool_forceupdate, $bool_invokecallbacks)
    {
        if(self::$_firstArrival && $bool_invokecallbacks && !is_null(self::$_arrivalCallback)) {
            $bool_forceupdate = true;
        }
        $now = self::GetTickCount();
        if($bool_forceupdate) {
            foreach(self::$_hubs as $hub) {
                $hub->devListExpires = $now;
            }
        }

        // Prepare to scan all expired hubs
        $hubs = Array();
        foreach(self::$_hubs as $hub) {
            if($hub->devListExpires <= $now) {
                $tcpreq = new YTcpReq($hub, 'GET /api.json', false);
                self::$_pendingRequests[] = $tcpreq;
                $hubs[] = $hub;
                $hub->devListReq = $tcpreq;
                $hub->missing = Array();
            }
        }
        
        // assume all device as unpluged, unless proved wrong
        foreach(self::$_devs as $serial => $dev) {
            $rooturl = $dev->getRootUrl();
            foreach($hubs as $hub) {
                $huburl = $hub->rooturl;
                if(substr($rooturl,0,strlen($huburl)) == $huburl) {
                    $hub->missing[$serial] = true;
                }
            }
        }
        
        // Wait until all hubs are complete, and process replies as they come
        $timeout = self::GetTickCount() + YAPI_BLOCKING_REQUEST_TIMEOUT;
        while(self::GetTickCount() < $timeout) {
            self::_handleEvents_internal(100);
            $alldone = true;
            foreach ($hubs as $hub) {
                $req = $hub->devListReq;
                if(!$req->eof()) {
                    $alldone = false;
                    continue;
                }
                if($req->errorType != YAPI_SUCCESS) {
                    // report problems later
                    continue;
                }
                $loadval = json_decode($req->reply, true);
                if (!$loadval) {
                    $req->errorType = YAPI_IO_ERROR;
                    continue;
                }
                if (!isset($loadval['services']) || !isset($loadval['services']['whitePages'])) {
                    $req->errorType = YAPI_INVALID_ARGUMENT;
                    continue;
                }
                if (isset($loadval['network']) && isset($loadval['network']['adminPassword'])) {
                    $hub->writeProtected = ($loadval['network']['adminPassword'] != '');
                }
                $whitePages = $loadval['services']['whitePages'];
                // Reindex all functions from yellow pages
                $refresh = Array();
                $yellowPages = $loadval["services"]["yellowPages"];
                foreach ($yellowPages as $classname => $obj_yprecs) {
                    if (!isset(self::$_fnByType[$classname])) {
                        self::$_fnByType[$classname] = new YFunctionType($classname);
                    }
                    $ftype = self::$_fnByType[$classname];
                    foreach ($obj_yprecs as $key => $yprec) {
                        $hwid = $yprec["hardwareId"];
                        if ($ftype->reindexFunction($hwid, $yprec["logicalName"], $yprec["advertisedValue"])) {
                            // logical name discrepency detected, force a refresh from device
                            $serial = substr($hwid, 0, strpos($hwid, '.'));
                            $refresh[$serial] = true;
                        }
                    }
                }
                // Reindex all devices from white pages
                foreach ($whitePages as $devkey => $devinfo) {
                    $serial = $devinfo['serialNumber'];
                    $rooturl = substr($devinfo['networkUrl'], 0, -3);
                    if ($rooturl[0] == '/')
                        $rooturl = $hub->rooturl . substr($rooturl, 1);
                    $currdev = null;
                    if (isset(self::$_devs[$serial])) {
                        $currdev = self::$_devs[$serial];
                        if (!is_null(self::$_arrivalCallback) && self::$_firstArrival) {
                            self::$_pendingCallbacks[] = "+$serial";
                        }
                    }
                    if (isset($devinfo['index'])) {
                        $devydx = $devinfo['index'];
                        $hub->serialByYdx[$devydx] = $serial;
                    }
                    if (!isset(self::$_devs[$serial])) {
                        // Add new device
                        new YDevice($rooturl, $devinfo, $loadval["services"]["yellowPages"]);
                        if (!is_null(self::$_arrivalCallback)) {
                            self::$_pendingCallbacks[] = "+$serial";
                        }
                    } else if ($currdev->getLogicalName() != $devinfo['logicalName']) {
                        // Reindex device from its own data
                        $currdev->refresh();
                        if (!is_null(self::$_namechgCallback)) {
                            self::$_pendingCallbacks[] = "/$serial";
                        }
                    } else if (isset($refresh[$serial]) || $currdev->getRootUrl() != $rooturl ||
                            $currdev->getBeacon() != $devinfo['beacon']) {
                        // Reindex device from its own data in case of discrepency
                        $currdev->refresh();
                    }
                    $hub->missing[$serial] = false;
                }

                // Keep track of all unplugged devices on this hub
                foreach ($hub->missing as $serial => $missing) {
                    if ($missing) {
                        if (!is_null(self::$_removalCallback)) {
                            self::$_pendingCallbacks[] = "-$serial";
                        } else {
                            self::forgetDevice(self::$_devs[$serial]);
                        }
                    }
                }

                // enable monitoring for this hub if not yet done
                self::monitorEvents($hub);
                $hub->devListExpires = $now + $hub->devListValidity;
            }
            if($alldone) break;
        }

        // after processing all hubs, invoke pending callbacks if required
        if($bool_invokecallbacks) {
            $nbevents = sizeof(self::$_pendingCallbacks);
            for($i = 0; $i < $nbevents; $i++) {
                $evt = self::$_pendingCallbacks[$i];
                $serial = substr($evt,1);
                switch(substr($evt,0,1)) {
                case '+': 
                    if(!is_null(self::$_arrivalCallback)) {
                        $cb = self::$_arrivalCallback;
                        $cb(yFindModule($serial.".module"));
                    }
                    break;
                case '/': 
                    if(!is_null(self::$_namechgCallback)) {
                        $cb = self::$_namechgCallback;
                        $cb(yFindModule($serial.".module"));
                    }
                    break;
                case '-': 
                    if(!is_null(self::$_removalCallback)) {
                        $cb = self::$_removalCallback;
                        $cb(yFindModule($serial.".module"));
                    }
                    self::forgetDevice(self::$_devs[$serial]);
                    break;
                }
            }
            self::$_pendingCallbacks = array_slice(self::$_pendingCallbacks, $nbevents);
            if(!is_null(self::$_arrivalCallback) && self::$_firstArrival) {
                self::$_firstArrival = false;
            }
        }

        // report any error seen during scan
        foreach ($hubs as $hub) {
            $req = $hub->devListReq;
            if($req->errorType != YAPI_SUCCESS) {
                return new YAPI_YReq($req->errorType, 
                                     'Error while scanning '.$hub->rooturl.': '.$req->errorMsg, 
                                     $req->errorType);
            }
        }        
        return new YAPI_YReq(YAPI_SUCCESS, "no error", YAPI_SUCCESS);
    }

    public static function _handleEvents_internal($int_maxwait)
    {
        $something_done = false;

        // start event monitoring if needed
        foreach(self::$_hubs as $hub) {
            $req = $hub->notifReq;
            if($req) {
                if($req->eof()) {
                    Printf("Event channel at eof, reopen\n");
                    $something_done = true;
                    $hub->notifReq = $req = null;
                    self::monitorEvents($hub);
                }
            } else if($hub->retryExpires > 0 && $hub->retryExpires <= self::GetTickCount()) {
                Printf("RetryExpires, calling monitorEvents\n");
                $something_done = true;
                self::monitorEvents($hub);
            }
        }

        // monitor all pending requests
        $streams = Array();
        foreach(self::$_pendingRequests as $req) {
            if(is_null($req->skt)) {
                $req->process();
            }
            if(!is_null($req->skt)) {
                $streams[] = $req->skt;
            }
        }

        if(sizeof($streams) == 0) {
            usleep($int_maxwait*1000);
            return false;
        }
        $wr = NULL; $ex = NULL;
        stream_select($streams, $wr, $ex, 0, $int_maxwait*1000);
        for($idx = 0; $idx < sizeof(self::$_pendingRequests); $idx++) {
            $req = self::$_pendingRequests[$idx];
            $hub = $req->hub;
            // generic request processing
            $req->process();
            if($req->eof()) {
                array_splice(self::$_pendingRequests, $idx, 1);
            }
            // handle notification channel
            if ($req === $hub->notifReq) {
                $pos = strpos($req->reply, "\n");
                while($pos !== false) {
                    $ev = trim(substr($req->reply, 0, $pos));
                    $req->reply = substr($req->reply, $pos+1);
                    $pos = strpos($req->reply, "\n");
                    if (strlen($ev) >= 3 && substr($ev, 0, 1) == 'y') {
                        // function value ydx (tiny notification)
                        $hub->devListValidity = 10000;
                        $hub->retryDelay = 15;
                        if ($hub->notifPos >= 0) {
                            $hub->notifPos += strlen($ev) + 1;
                        }
                        $devydx = ord($ev[1]) - 65; // from 'A'
                        $funydx = ord($ev[2]) - 48; // from '0'
                        if ($funydx >= 64) { // high bit of devydx is on second character
                            $funydx -= 64;
                            $devydx += 128;
                        }
                        if (isset($hub->serialByYdx[$devydx])) {
                            $serial = $hub->serialByYdx[$devydx];
                            if(isset(self::$_devs[$serial])) {
                                $funcid = self::$_devs[$serial]->functionId($funydx);
                                if ($funcid != "") {
                                    $value = substr($ev, 3);
                                    $value = explode("\0", $value);
                                    YAPI::setFunctionValue($serial . '.' . $funcid, $value[0]);
                                }
                            }
                        }
                    } else if (strlen($ev) > 5 && substr($ev, 0, 4) == 'YN01') {
                        $hub->devListValidity = 10000;
                        $hub->retryDelay = 15;
                        if ($hub->notifPos >= 0) {
                            $hub->notifPos += strlen($ev) + 1;
                        }
                        $notype = substr($ev, 4, 1);
                        if ($notype == '@') {
                            $hub->notifPos = intVal(substr($ev, 5));
                        } else
                            switch (intVal($notype)) {
                                case 0: // device name change, or arrival
                                case 2: // device plug/unplug
                                case 4: // function name change
                                case 8: // function name change (ydx)
                                    $hub->devListExpires = 0;
                                    break;
                                case 5: // function value (long notification)
                                    $parts = explode(',', substr($ev, 5));
                                    $value = explode("\0", $parts[2]);
                                    YAPI::setFunctionValue($parts[0] . '.' . $parts[1], $value[0]);
                                    break;
                            }
                    } else {
                        // oops, bad notification ? be safe until a good one comes
                        $hub->devListValidity = 500;
                        $hub->devListExpires = 0;
                        $hub->notifPos = -1;
                    }
                }
            }
        }

        return $something_done;
    }

    public static function flushConnections()
    {
        foreach(self::$_pendingRequests as $req) {
            if($req->async) {
                while(!$req->eof()) {
                    self::_handleEvents_internal(200);
                }
            }
        }
    }

    public static function monitorEvents($hub)
    {
        if(!is_null($hub->notifReq)) return;
        if($hub->retryExpires > self::GetTickCount()) return;
        if($hub->isCachedHub()) return;

        $url = $hub->notifurl.'?len=0';
        if($hub->notifPos >= 0) $url .= '&abs='.$hub->notifPos;
        $req = new YTcpReq($hub, 'GET /'.$url, false);
        if($req->process($errmsg) != YAPI_SUCCESS) {
            if($hub->retryDelay == 0) {
                $hub->retryDelay = 15;
            } else if($hub->retryDelay < 15000) {
                $hub->retryDelay = 2 * $hub->retryDelay;
            }
            $hub->retryExpires = self::GetTickCount() + $hub->retryDelay;
            return;
        }
        self::$_pendingRequests[] = $req;
        $hub->notifReq = $req;
    }

    // Convert Yoctopuce 16-bit decimal floats to standard double-precision floats
    //
    public static function decimalToDouble($val)
    {
        $negate = false;
        
        if($val == 0) return 0.0;
        if($val > 32767) {
            $negate = true;
            $val = 65536-$val;            
        } else if($val < 0) {
            $negate = true;
            $val = -$val;
        }
        $res = ($val & 2047) * self::$_decExp[$val >> 11];
    
        return ($negate ? -$res : $res);
    }

    // Convert standard double-precision floats to Yoctopuce 16-bit decimal floats
    //
    public static function doubleToDecimal($val)
    {
        $negate = false;
    
        if($val == 0.0) {
            return 0;
        }
        if($val < 0) {
            $negate = true;
            $val = -$val;
        }
        $comp = $val / 1999.0;
        $decpow = 0;
        while($comp > self::$_decExp[$decpow] && $decpow < 15) {
            $decpow++;
        }
        $mant = $val / $decExp[$decpow];
        if($decpow == 15 && $mant > 2047.0) {
            $res = (15 << 11) + 2047; // overflow
        } else {
            $res = ($decpow << 11) + round($mant);
        }
        return ($negate ? -$res : $res);
    }

    // Return a the calibration handler for a given type
    public static function getCalibrationHandler($calibType)
    {
        if(!isset(self::$_calibHandlers[strVal($calibType)])) {
            return null;
        }
        return self::$_calibHandlers[strVal($calibType)];
    }

    // Compute the currentValue for the provided function, using the currentRawValue,
    // the calibrationParam and the proper registered calibration handler
    public static function applyCalibration($obj_yfunc)
    {
        $rawVal = $obj_yfunc->get_currentRawValue();
        if($rawVal == Y_INVALID_FLOAT) {
            return Y_INVALID_FLOAT;
        }
        $params = $obj_yfunc->get_calibrationParam();
        if($params == '' || $params == '0') {
            return $rawVal;
        }
        $params = explode(',', $params);
        if(sizeof($params) < 11) {
            return Y_INVALID_FLOAT;
        }
        $ctyp = intVal($params[0]);
        if($ctyp == 0) {
            return $rawVal;
        }
        $handler = self::getCalibrationHandler($params[0]);
        if(is_null($handler)) {
            return Y_INVALID_FLOAT;            
        }
        $resol = $obj_yfunc->get_resolution();
        if($resol == Y_INVALID_FLOAT) {
            return Y_INVALID_FLOAT;
        }
        $iParams = Array();
        $rawPt = Array();
        $calPt = Array();
        for($i = 1; $i < sizeof($params); $i += 2) {
            $iParams[$i-1] = intVal($params[$i]);
            $iParams[$i]   = intVal($params[$i+1]);
            if($ctyp <= 10) {
                $rawPt[$i>>1]  = ($iParams[$i-1]+$obj_yfunc->_calibrationOffset) * $resol;
                $calPt[$i>>1]  = ($iParams[$i]+$obj_yfunc->_calibrationOffset) * $resol;
            } else {
                $rawPt[$i>>1]  = YAPI::decimalToDouble($iParams[$i-1]);
                $calPt[$i>>1]  = YAPI::decimalToDouble($iParams[$i]);
            }
        }
        return call_user_func($handler, $rawVal, $ctyp, $iParams, $rawPt, $calPt);
    }
    
    // Return a Device object for a specified URL, serial number or logical device name
    // This function will not cause any network access
    public static function getDevice($str_device)
    {
        $dev = null;

        if(substr($str_device, 0, 7) == 'http://') {
            if(isset(self::$_snByUrl[$str_device])) {
                $serial = self::$_snByUrl[$str_device];
                if(isset(self::$_devs[$serial])) {
                    $dev = self::$_devs[$serial];
                }
            }
        } else {
            // lookup by serial
            if(isset(self::$_devs[$str_device])) {
                $dev = self::$_devs[$str_device];
            } else {
                // fallback to lookup by logical name
                if(isset(self::$_snByName[$str_device])) {
                    $serial = self::$_snByName[$str_device];
                    $dev = self::$_devs[$serial];
                }
            }
        }
        return $dev;
    }

    // Return the class name for a given function ID or full Hardware Id
    // Also make sure that the function type is registered in the API
    public static function functionClass($str_funcid)
    {
        $dotpos = strpos($str_funcid, '.');
        if($dotpos !== false) $str_funcid = substr($str_funcid, $dotpos+1);
        $classlen = strlen($str_funcid);
        while(ord($str_funcid[$classlen-1]) <= 57) $classlen--;
        $classname = strtoupper($str_funcid[0]).substr($str_funcid,1,$classlen-1);
        if(!isset(self::$_fnByType[$classname])) {
            self::$_fnByType[$classname] = new YFunctionType($classname);
        }

        return $classname;
    }

    // Reindex a device in YAPI after a name change detected by device refresh
    public static function reindexDevice($obj_dev)
    {
        $rootUrl = $obj_dev->getRootUrl();
        $serial = $obj_dev->getSerialNumber();
        $lname = $obj_dev->getLogicalName();
        self::$_devs[$serial] = $obj_dev;
        self::$_snByUrl[$rootUrl] = $serial;
        if($lname != '') self::$_snByName[$lname] = $serial;
        self::$_fnByType['Module']->reindexFunction("$serial.module", $lname, null);
        $count = $obj_dev->functionCount();
        for($i = 0; $i < $count; $i++) {
            $funcid = $obj_dev->functionId($i);
            $funcname = $obj_dev->functionName($i);
            $classname = self::functionClass($funcid);
            self::$_fnByType[$classname]->reindexFunction("$serial.$funcid", $funcname, null);
        }
    }

    // Remove a device from YAPI after an unplug detected by device refresh
    public static function forgetDevice($obj_dev)
    {
        $rootUrl = $obj_dev->getRootUrl();
        $serial = $obj_dev->getSerialNumber();
        $lname = $obj_dev->getLogicalName();
        unset(self::$_devs[$serial]);
        unset(self::$_snByUrl[$rootUrl]);
        if(isset(self::$_snByName[$lname]) && self::$_snByName[$lname] == $serial) {
            unset(self::$_snByName[$lname]);
        }
        self::$_fnByType['Module']->forgetFunction("$serial.module");
        $count = $obj_dev->functionCount();
        for($i = 0; $i < $count; $i++) {
            $funcid = $obj_dev->functionId($i);
            $classname = self::functionClass($funcid);
            self::$_fnByType[$classname]->forgetFunction("$serial.$funcid");
        }
    }

    // Find the best known identifier (hardware Id) for a given function
    public static function resolveFunction($str_className, $str_func)
    {
        if(!isset(self::$_fnByType[$str_className]))
            self::$_fnByType[$str_className] = new YFunctionType($str_className);
        return self::$_fnByType[$str_className]->resolve($str_func);
    }

    // return a firendly name for of a given function
    public static function getFriendlyNameFunction($str_className, $str_func)
    {
        if(!isset(self::$_fnByType[$str_className]))
            self::$_fnByType[$str_className] = new YFunctionType($str_className);
        return self::$_fnByType[$str_className]->getFriendlyName($str_func);
    }


    // Retrieve a function object by hardware id, updating the indexes on the fly if needed
    public static function setFunction($str_className, $str_func, $obj_func)
    {
        if(!isset(self::$_fnByType[$str_className]))
            self::$_fnByType[$str_className] = new YFunctionType($str_className);
        return self::$_fnByType[$str_className]->setFunction($str_func, $obj_func);
    }

    // Retrieve a function object by hardware id, updating the indexes on the fly if needed
    public static function getFunction($str_className, $str_func)
    {
        if(is_null(self::$_hubs)) self::_init();

        if(!isset(self::$_fnByType[$str_className]))
            self::$_fnByType[$str_className] = new YFunctionType($str_className);
        return self::$_fnByType[$str_className]->getFunction($str_func);
    }

    // Set a function advertised value by hardware id
    public static function setFunctionValue($str_hwid, $str_pubval)
    {
        $classname = self::functionClass($str_hwid);
        return self::$_fnByType[$classname]->setFunctionValue($str_hwid, $str_pubval);
    }

    // Retrieve a function advertised value by hardware id
    public static function getFunctionValue($str_hwid)
    {
        $classname = self::functionClass($str_hwid);
        return self::$_fnByType[$classname]->getFunctionValue($str_hwid);
    }

    // Queue a function value event
    public static function addValueEvent($obj_func, $str_newval)
    {
        self::$_pendingValues[] = Array($obj_func, $str_newval);
    }

    // Find the hardwareId for the first instance of a given function class
    public static function getFirstHardwareId($str_className)
    {
        if(is_null(self::$_hubs)) self::_init();

        if(!isset(self::$_fnByType[$str_className]))
            self::$_fnByType[$str_className] = new YFunctionType($str_className);
        return self::$_fnByType[$str_className]->getFirstHardwareId();
    }

    // Find the hardwareId for the next instance of a given function class
    public static function getNextHardwareId($str_className, $str_hwid)
    {        
        return self::$_fnByType[$str_className]->getNextHardwareId($str_hwid);
    }

    // Perform an HTTP request on a device, by URL or identifier. 
    // When loading the REST API from a device by identifier, the device cache will be used
    // Return a strucure including errorType, errorMsg and result
    public static function devRequest($str_device, $str_request, $async=false, $body='')
    {
        $lines = explode("\n", $str_request);
        $dev = null; 
        $baseUrl = $str_device;
        if(substr($str_device, 0, 7) == 'http://') {
            if(substr($baseUrl, -1) != '/') $baseUrl .= '/';
            if(isset(self::$_snByUrl[$baseUrl])) {
                $serial = self::$_snByUrl[$baseUrl];
                if(isset(self::$_devs[$serial])) {
                    $dev = self::$_devs[$serial];
                }
            }
        } else {
            $dev = self::getDevice($str_device);
            if(!$dev) {
                return new YAPI_YReq(YAPI_DEVICE_NOT_FOUND,
                                     "Device [$str_device] not online",
                                     null);
            }
            // use the device cache when loading the whole API
            if($lines[0] == 'GET /api.json') {
                return $dev->requestAPI();
            }
            $baseUrl = $dev->getRootUrl();
        }
        // map str_device to a URL
        $words = explode(' ', $lines[0]);
        if(sizeof($words) < 2) {
            return new YAPI_YReq(YAPI_INVALID_ARGUMENT, 
                                 'Invalid request, not enough words; expected a method name and a URL', 
                                 null);
        } else if(sizeof($words) > 2) {
            return new YAPI_YReq(YAPI_INVALID_ARGUMENT, 
                                 'Invalid request, too many words; make sure the URL is URI-encoded', 
                                 null);
        }
        $method = $words[0];
        $devUrl = $words[1];
        if(substr($devUrl,0,1) == '/') $devUrl = substr($devUrl, 1);
        $baseUrl = str_replace('http://', '', $baseUrl);
        $pos = strpos($baseUrl, '/');
        if($pos !== false) {
            $devUrl = substr($baseUrl, $pos).$devUrl;
            $baseUrl = substr($baseUrl, 0, $pos);
        } else {
            $devUrl = "/$devUrl";
        }
        $rooturl = "http://$baseUrl/";
        if(!isset(self::$_hubs[$rooturl])) {
            return new YAPI_YReq(YAPI_DEVICE_NOT_FOUND, 'No hub registered on '.$baseUrl, null);
        }
        $hub = self::$_hubs[$rooturl];
        if($async && $hub->writeProtected && $hub->user != 'admin') {
            // async query, make sure the hub is not write-protected
            return new YAPI_YReq(YAPI_UNAUTHORIZED, 
                                    'Access denied: admin credentials required', 
                                    null);                                
        }
        $tcpreq = new YTcpReq($hub, "$method $devUrl", $async, $body);
        if(!is_null($dev)) {
            $dev->prepRequest($tcpreq);
        }
        if($tcpreq->process() != YAPI_SUCCESS) {
            return new YAPI_YReq($tcpreq->errorType, $tcpreq->errorMsg, null);
        }
        self::$_pendingRequests[] = $tcpreq;
        if(!$async) {
            // normal query, wait for completion until timeout
            $timeout = YAPI::GetTickCount() + YAPI_BLOCKING_REQUEST_TIMEOUT;
            do {
                self::_handleEvents_internal(100);
            } while(!$tcpreq->eof() && YAPI::GetTickCount() < $timeout);
            if(!$tcpreq->eof()) {
                $tcpreq->close();
                return new YAPI_YReq(YAPI_TIMEOUT, 
                                     'Timeout waiting for device reply', 
                                     null);
            }
            if ($tcpreq->errorType == YAPI_UNAUTHORIZED) {
                return new YAPI_YReq(YAPI_UNAUTHORIZED, 
                                     'Access denied, authorization required', 
                                     null);                
            } else if ($tcpreq->errorType != YAPI_SUCCESS) {
                return new YAPI_YReq($tcpreq->errorType, 
                                     'Network error while reading from device', 
                                     null);
            }
            if(strpos($tcpreq->meta, "OK\r\n") === 0) {
                return new YAPI_YReq(YAPI_SUCCESS, 
                                     'no error', 
                                     $tcpreq->reply);
            }
            if(!preg_match('/^HTTP[^ ]* (?P<status>\d+) (?P<statusmsg>.)+$/', $tcpreq->meta, $matches)) {
                return new YAPI_YReq(YAPI_IO_ERROR, 
                                     'Unexpected HTTP response header: '.$tcpreq->meta, 
                                     null);
            }
            if($matches['status'] != '200' && $matches['status'] != '304') {
                return new YAPI_YReq(YAPI_IO_ERROR, 
                                     'Received HTTP status '.$matches['status'].' ('.$matches['statusmsg'].')', 
                                     null);
            }
        }
        
        return new YAPI_YReq(YAPI_SUCCESS, 
                             'no error', 
                             $tcpreq->reply);
    }

    // Load and parse the REST API for a function given by class name and identifier, possibly applying changes
    // Device cache will be preloaded when loading function "module" and leveraged for other modules
    // Return a strucure including errorType, errorMsg and result
    public static function funcRequest($str_className, $str_func, $str_extra)
    {
        $resolve = self::resolveFunction($str_className, $str_func);
        if($resolve->errorType != YAPI_SUCCESS) {
            if($resolve->errorType == YAPI_DEVICE_NOT_FOUND && sizeof(self::$_hubs) == 0) { 
                // when USB is supported, check if no USB device is connected before outputing this message
                $resolve->errorMsg = "Impossible to contact any device because no hub has been registered";
            } else {
                $resolve = self::_updateDeviceList_internal(true, false);
                if($resolve->errorType != YAPI_SUCCESS) {
                    return $resolve;
                }
                $resolve = self::resolveFunction($str_className, $str_func);
            }
            if($resolve->errorType != YAPI_SUCCESS) {
                return $resolve;
            }
        }
        $str_func = $resolve->result;
        $dotpos = strpos($str_func, '.');
        $devid = substr($str_func,0,$dotpos);
        $funcid = substr($str_func,$dotpos+1);
        $dev = self::getDevice($devid);
        if(!$dev) {
            // try to force a device list update to check if the device arrived in between
            $resolve = self::_updateDeviceList_internal(true, false);
            if($resolve->errorType != YAPI_SUCCESS) {
                return $resolve;
            }
            $dev = self::getDevice($devid);
            if(!$dev == null) {
                return new YAPI_YReq(YAPI_DEVICE_NOT_FOUND, 
                                     "Device [$devid] not online", 
                                     null);
            }
        }
        $loadval = false;
        if($str_extra == '') {
            // use a cached API string, without reloading unless module is requested
            $yreq = $dev->requestAPI();
            if(!is_null($yreq)) {
                if($yreq->errorType != YAPI_SUCCESS) return $yreq;
                $loadval = json_decode($yreq->result, true);
                $loadval = $loadval[$funcid];
            }
        } else {
            $dev->dropCache();
        }
        if(!$loadval) {
            // request specified function only to minimize traffic
            if($str_extra == "") {
                $httpreq = "GET /api/{$funcid}.json";
                $yreq = self::devRequest($devid, $httpreq);
                if($yreq->errorType != YAPI_SUCCESS) return $yreq;
                $loadval = json_decode($yreq->result, true);
            } else {
                $httpreq = "GET /api/{$funcid}{$str_extra}";
                $yreq = self::devRequest($devid, $httpreq, true);
                return $yreq;
            }
        }
        if(!$loadval) {
            return new YAPI_YReq(YAPI_IO_ERROR, 
                                 "Request failed, could not parse API value for function $str_func", 
                                 null);
        }
        $yreq->result = $loadval;
        return $yreq;
    }

    // Perform an HTTP request on a device and return the result string
    // Throw an exception (or return YAPI_ERROR_STRING on error)
    public static function HTTPRequest($str_device, $str_request)
    {
        $res = self::devRequest($str_device, $str_request);
        if($res->errorType != YAPI_SUCCESS) {
            return self::_throw($res->errorType, $res->errorMsg, null);
        }
        return $res->result;
    }

    /**
     * Returns the version identifier for the Yoctopuce library in use.
     * The version is a string in the form "Major.Minor.Build",
     * for instance "1.01.5535". For languages using an external
     * DLL (for instance C#, VisualBasic or Delphi), the character string
     * includes as well the DLL version, for instance
     * "1.01.5535 (1.01.5439)".
     * 
     * If you want to verify in your code that the library version is
     * compatible with the version that you have used during development,
     * verify that the major number is strictly equal and that the minor
     * number is greater or equal. The build number is not relevant
     * with respect to the library compatibility.
     * 
     * @return a character string describing the library version.
     */
    public static function GetAPIVersion()
    {
        return "1.01.12553";
    }

    /**
     * Initializes the Yoctopuce programming library explicitly.
     * It is not strictly needed to call yInitAPI(), as the library is
     * automatically  initialized when calling yRegisterHub() for the
     * first time.
     * 
     * When Y_DETECT_NONE is used as detection mode,
     * you must explicitly use yRegisterHub() to point the API to the
     * VirtualHub on which your devices are connected before trying to access them.
     * 
     * @param mode : an integer corresponding to the type of automatic
     *         device detection to use. Possible values are
     *         Y_DETECT_NONE, Y_DETECT_USB, Y_DETECT_NET,
     *         and Y_DETECT_ALL.
     * @param errmsg : a string passed by reference to receive any error message.
     * 
     * @return YAPI_SUCCESS when the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public static function InitAPI($int_mode, &$str_errmsg='')
    {
        if(is_null(self::$_hubs)) self::_init();

        return YAPI_SUCCESS;
    }

    /**
     * Frees dynamically allocated memory blocks used by the Yoctopuce library.
     * It is generally not required to call this function, unless you
     * want to free all dynamically allocated memory blocks in order to
     * track a memory leak for instance.
     * You should not call any other library function after calling
     * yFreeAPI(), or your program will crash.
     */
    public static function FreeAPI()
    {
        // clear all caches
        self::_init();
    }

    /**
     * Disables the use of exceptions to report runtime errors.
     * When exceptions are disabled, every function returns a specific
     * error value which depends on its type and which is documented in
     * this reference manual.
     */
    public static function DisableExceptions()
    {
        if(is_null(self::$_hubs)) self::_init();

        self::$exceptionsDisabled = true;
    }

    /**
     * Re-enables the use of exceptions for runtime error handling.
     * Be aware than when exceptions are enabled, every function that fails
     * triggers an exception. If the exception is not caught by the user code,
     * it  either fires the debugger or aborts (i.e. crash) the program.
     * On failure, throws an exception or returns a negative error code.
     */
    public static function EnableExceptions()
    {
        if(is_null(self::$_hubs)) self::_init();

        self::$exceptionsDisabled = false;
    }

    private static function _parseRegisteredURL($str_url, &$rooturl, &$auth)
    {
        if(substr($str_url, 0, 7) == 'http://') {
            $str_url = substr($str_url, 7);
        }
        while(substr($str_url, -1) == '/') {
            $str_url = substr($str_url, 0, -1);
        }
        $authpos = strpos($str_url, '@');
        if ($authpos === false) {
            $auth = '';
        } else {
            $auth = substr($str_url, 0, $authpos);
            $str_url = substr($str_url, $authpos+1);
        }
        if(strcasecmp(substr($str_url,0,8),"callback")==0) {
            $rooturl = "http://".strtoupper($str_url)."/";
        } else {
            if(strpos($str_url, ':') === false) {
                $str_url .= ':4444';
            }
            $rooturl = "http://{$str_url}/";
        }
    }

    /**
     * Setup the Yoctopuce library to use modules connected on a given machine.
     * When using Yoctopuce modules through the VirtualHub gateway,
     * you should provide as parameter the address of the machine on which the
     * VirtualHub software is running (typically "http://127.0.0.1:4444",
     * which represents the local machine).
     * When you use a language which has direct access to the USB hardware,
     * you can use the pseudo-URL "usb" instead.
     * 
     * Be aware that only one application can use direct USB access at a
     * given time on a machine. Multiple access would cause conflicts
     * while trying to access the USB modules. In particular, this means
     * that you must stop the VirtualHub software before starting
     * an application that uses direct USB access. The workaround
     * for this limitation is to setup the library to use the VirtualHub
     * rather than direct USB access.
     * 
     * If acces control has been activated on the VirtualHub you want to
     * reach, the URL parameter should look like:
     * 
     * http://username:password@adresse:port
     * 
     * @param url : a string containing either "usb" or the
     *         root URL of the hub to monitor
     * @param errmsg : a string passed by reference to receive any error message.
     * 
     * @return YAPI_SUCCESS when the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public static function RegisterHub($str_url,&$str_errmsg='')
    {
        if(is_null(self::$_hubs)) self::_init();

        self::_parseRegisteredURL($str_url, $rooturl, $auth);
        
        // Test hub
        $tcphub = new YTcpHub($rooturl, $auth);
        if($tcphub->verfiyStreamAddr($str_errmsg)<0){
            return self::_throw(YAPI_IO_ERROR, $str_errmsg, YAPI_IO_ERROR);
        }
        $tcpreq = new YTcpReq($tcphub, "GET /api/module.json", false);
        if($tcpreq->process($str_errmsg) != YAPI_SUCCESS) {
            return self::_throw($tcpreq->errorType, $str_errmsg, $tcpreq->errorType);
        }
        self::$_pendingRequests[] = $tcpreq;
        $timeout = YAPI::GetTickCount() + YAPI_BLOCKING_REQUEST_TIMEOUT;
        do {
            self::_handleEvents_internal(100);
        } while (!$tcpreq->eof() && YAPI::GetTickCount() < $timeout);
        if (!$tcpreq->eof()) {
            $tcpreq->close();
            $str_errmsg = 'Timeout waiting for device reply';
            return self::_throw(YAPI_TIMEOUT,$str_errmsg , YAPI_TIMEOUT);
        }
        if ($tcpreq->errorType == YAPI_UNAUTHORIZED) {
            $str_errmsg = 'Access denied, authorization required';
            return self::_throw(YAPI_UNAUTHORIZED, $str_errmsg, YAPI_UNAUTHORIZED);
        } else if ($tcpreq->errorType != YAPI_SUCCESS) {
            $str_errmsg = 'Network error while testing hub';
            return self::_throw($tcpreq->errorType, $str_errmsg, $tcpreq->errorType);
        }
        
        // Add hub to known list
        if(!isset(self::$_hubs[$rooturl])) {
            self::$_hubs[$rooturl] = $tcphub;
        }

        // Register device list
        $yreq = self::_updateDeviceList_internal(true, false);
        if($yreq->errorType != YAPI_SUCCESS) {
            $str_errmsg = $yreq->errorMsg;
            return self::_throw($yreq->errorType, $yreq->errorMsg, $yreq->errorType);
        }
        
        return YAPI_SUCCESS;
    }

    /**
     *
     */
    public static function PreregisterHub($str_url,&$str_errmsg='')
    {
        if(is_null(self::$_hubs)) self::_init();
        
        self::_parseRegisteredURL($str_url, $rooturl, $auth);

        // Add hub to known list
        if(!isset(self::$_hubs[$rooturl])) {
            self::$_hubs[$rooturl] = new YTcpHub($rooturl, $auth);
            if(self::$_hubs[$rooturl]->verfiyStreamAddr($str_errmsg)<0){
                return self::_throw(YAPI_IO_ERROR, $str_errmsg, YAPI_IO_ERROR);
            }
        }

        return YAPI_SUCCESS;
    }


    /**
     * Setup the Yoctopuce library to no more use modules connected on a previously
     * registered machine with RegisterHub.
     * 
     * @param url : a string containing either "usb" or the
     *         root URL of the hub to monitor
     */

    public static function UnregisterHub($str_url)
    {
        if (is_null(self::$_hubs))
            return;

        $str_url = self::_cleanRegistredURL($str_url);
        $new_hubs = array();
        for ($i = 0; $i < sizeof(self::$_hubs); $i++) {
            if (self::$_hubs[$i]['rooturl'] == $str_url) {
                // remove all connected devices
                foreach (self::$_hubs[$i]['serialByYdx'] as $serial) {
                    self::forgetDevice(self::$_devs[$serial]);
                }
            } else {
                $new_hubs[] = self::$_hubs[$i];
            }
        }
        self::$_hubs = $new_hubs;
    }


    /**
     * Triggers a (re)detection of connected Yoctopuce modules.
     * The library searches the machines or USB ports previously registered using
     * yRegisterHub(), and invokes any user-defined callback function
     * in case a change in the list of connected devices is detected.
     * 
     * This function can be called as frequently as desired to refresh the device list
     * and to make the application aware of hot-plug events.
     * 
     * @param errmsg : a string passed by reference to receive any error message.
     * 
     * @return YAPI_SUCCESS when the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public static function UpdateDeviceList(&$str_errmsg='')
    {
        $yreq = self::_updateDeviceList_internal(false, true);
        if($yreq->errorType != YAPI_SUCCESS) {
            $errmsg = $yreq->errorMsg;
            return self::_throw($yreq->errorType, $yreq->errorMsg, $yreq->errorType);
        }
        return YAPI_SUCCESS;
    }

    /**
     * Maintains the device-to-library communication channel.
     * If your program includes significant loops, you may want to include
     * a call to this function to make sure that the library takes care of
     * the information pushed by the modules on the communication channels.
     * This is not strictly necessary, but it may improve the reactivity
     * of the library for the following commands.
     * 
     * This function may signal an error in case there is a communication problem
     * while contacting a module.
     * 
     * @param errmsg : a string passed by reference to receive any error message.
     * 
     * @return YAPI_SUCCESS when the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public static function HandleEvents(&$str_errmsg='')
    {
        // monitor hubs for events
        while(self::_handleEvents_internal(0));

        // handle pending events
        $nEvents = sizeof(self::$_pendingValues);
        for($i = 0; $i < $nEvents; $i++) {
            $evt = self::$_pendingValues[$i];
            if(!is_null($evt[0]->_getValueCallback())) {
                $cb = $evt[0]->_getValueCallback();
                $cb($evt[0], $evt[1]);
            }
        }
        self::$_pendingValues = array_slice(self::$_pendingValues, $nEvents);

        return YAPI_SUCCESS;
    }

    /**
     * Pauses the execution flow for a specified duration.
     * This function implements a passive waiting loop, meaning that it does not
     * consume CPU cycles significatively. The processor is left available for
     * other threads and processes. During the pause, the library nevertheless
     * reads from time to time information from the Yoctopuce modules by
     * calling yHandleEvents(), in order to stay up-to-date.
     * 
     * This function may signal an error in case there is a communication problem
     * while contacting a module.
     * 
     * @param ms_duration : an integer corresponding to the duration of the pause,
     *         in milliseconds.
     * @param errmsg : a string passed by reference to receive any error message.
     * 
     * @return YAPI_SUCCESS when the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public static function Sleep($int_ms_duration, &$str_errmsg='')
    {
        $end = YAPI::GetTickCount() + $int_ms_duration;
        self::HandleEvents($obj_errmsg);
        $remain = $end - YAPI::GetTickCount();
        while($remain > 0) {
            if($remain > 999) $remain = 999;
            self::_handleEvents_internal($remain);
            self::HandleEvents($obj_errmsg);
            $remain = $end - YAPI::GetTickCount();
        }

        return YAPI_SUCCESS;
    }

    /**
     * Returns the current value of a monotone millisecond-based time counter.
     * This counter can be used to compute delays in relation with
     * Yoctopuce devices, which also uses the milisecond as timebase.
     * 
     * @return a long integer corresponding to the millisecond counter.
     */
    public static function GetTickCount()
    {
        return round(microtime(true) * 1000);
    }

    /**
     * Checks if a given string is valid as logical name for a module or a function.
     * A valid logical name has a maximum of 19 characters, all among
     * A..Z, a..z, 0..9, _, and -.
     * If you try to configure a logical name with an incorrect string,
     * the invalid characters are ignored.
     * 
     * @param name : a string containing the name to check.
     * 
     * @return true if the name is valid, false otherwise.
     */
    public static function CheckLogicalName($str_name)
    {
        if($str_name == '') return true;
        if(!$str_name) return false;
        if(strlen($str_name) > 19) return false;
        return preg_match('/^[A-Za-z0-9_\-]*$/', $str_name);
    }

    /**
     * Register a callback function, to be called each time
     * a device is pluged. This callback will be invoked while yUpdateDeviceList
     * is running. You will have to call this function on a regular basis.
     * 
     * @param arrivalCallback : a procedure taking a YModule parameter, or null
     *         to unregister a previously registered  callback.
     */
    public static function RegisterDeviceArrivalCallback($func_arrivalCallback)
    {
        self::$_arrivalCallback = $func_arrivalCallback;
    }

    /**
     * Register a device logical name change callback
     */
    public static function RegisterDeviceChangeCallback($func_changeCallback)
    {
        self::$_namechgCallback = $func_changeCallback;        
    }

    /**
     * Register a callback function, to be called each time
     * a device is unpluged. This callback will be invoked while yUpdateDeviceList
     * is running. You will have to call this function on a regular basis.
     * 
     * @param removalCallback : a procedure taking a YModule parameter, or null
     *         to unregister a previously registered  callback.
     */
    public static function RegisterDeviceRemovalCallback($func_removalCallback)
    {
        self::$_removalCallback = $func_removalCallback;
    }

    // Register a new value calibration handler for a given calibration type
    //
    public static function RegisterCalibrationHandler($int_calibrationType, $func_calibrationHandler)
    {
        self::$_calibHandlers[$int_calibrationType] = $func_calibrationHandler;
    }

    // Standard value calibration handler (n-point linear error correction)
    //
    public static function LinearCalibrationHandler($float_rawValue, $int_calibType, $arr_calibParams,
                                                    $arr_calibRawValues, $arr_calibRefValues)
    {
        // calibration types n=1..10 are meant for linear calibration using n points
        $npt = min($int_calibType % 10, sizeof($arr_calibRawValues), sizeof($arr_calibRefValues));
        $x   = $arr_calibRawValues[0];
        $adj = $arr_calibRefValues[0] - $x;
        $i   = 0;

        while($float_rawValue > $arr_calibRawValues[$i] && ++$i < $npt) {
            $x2   = $x;
            $adj2 = $adj;

            $x   = $arr_calibRawValues[$i];
            $adj = $arr_calibRefValues[$i] - $x;

            if($float_rawValue < $x && $x > $x2) {
                $adj = $adj2 + ($adj - $adj2) * ($float_rawValue - $x2) / ($x - $x2);
            }
        }
        return $float_rawValue + $adj;
    }

}

/**
 * YFunction Class (virtual class, used internally)
 *
 * This is the parent class for all public objects representing device functions documented in
 * the high-level programming API. This abstract class does all the real job, but without 
 * knowledge of the specific function attributes.
 *
 * Instantiating a child class of YFunction does not cause any communication.
 * The instance simply keeps track of its function identifier, and will dynamically bind
 * to a matching device at the time it is really beeing used to read or set an attribute.
 * In order to allow true hot-plug replacement of one device by another, the binding stay
 * dynamic through the life of the object.
 *
 * The YFunction class implements a generic high-level cache for the attribute values of
 * the specified function, pre-parsed from the REST API string.
 */
class YFunction
{
    const FUNCTIONDESCRIPTOR_INVALID = '!INVALID!';
    const HARDWAREID_INVALID = '!INVALID!';

    protected $_className;
    protected $_func;
    protected $_lastErrorType;
    protected $_lastErrorMsg;
    protected $_cache;
    protected $_userDate;
    protected $_valueCallback;

    function __construct($str_classname, $str_func)
    {
        // private
        $this->_className     = $str_classname;
        $this->_func          = $str_func;
        $this->_lastErrorType = YAPI_SUCCESS;
        $this->_lastErrorMsg  = 'no error';
        $this->_cache         = Array('_expiration' => 0);
        $this->_userData      = NULL;
        $this->_valueCallback = NULL;

        YAPI::setFunction($str_classname, $str_func, $this);
    }

    // Throw an exception, keeping track of it in the object itself
    protected function _throw($int_errType, $str_errMsg, $obj_retVal)
    {
        $this->_lastErrorType = $int_errType;
        $this->_lastErrorMsg = $str_errMsg;

        if(YAPI::$exceptionsDisabled) {
            return $obj_retVal;
        }
        // throw an exception
        throw new YAPI_Exception($str_errMsg, $int_errType);
    }

    /**
     * Returns a short text that describes the function in the form TYPE(NAME)=SERIAL&#46;FUNCTIONID.
     * More precisely,
     * TYPE       is the type of the function,
     * NAME       it the name used for the first access to the function,
     * SERIAL     is the serial number of the module if the module is connected or "unresolved", and
     * FUNCTIONID is  the hardware identifier of the function if the module is connected.
     * For example, this method returns Relay(MyCustomName.relay1)=RELAYLO1-123456.relay1 if the
     * module is already connected or Relay(BadCustomeName.relay1)=unresolved if the module has
     * not yet been connected. This method does not trigger any USB or TCP transaction and can therefore be used in
     * a debugger.
     * 
     * @return a string that describes the function
     *         (ex: Relay(MyCustomName.relay1)=RELAYLO1-123456.relay1)
     */
    public function describe()
    {
        $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS && $resolve->result != $this->_func) {
            return $this->_className."({$this->_func})=unresolved";
        }
        return $this->_className."({$this->_func})={$resolve->result}";
    }

    /**
     * Returns the unique hardware identifier of the function in the form SERIAL&#46;FUNCTIONID.
     * The unique hardware identifier is composed of the device serial
     * number and of the hardware identifier of the function. (for example RELAYLO1-123456.relay1)
     * 
     * @return a string that uniquely identifies the function (ex: RELAYLO1-123456.relay1)
     * 
     * On failure, throws an exception or returns  Y_HARDWAREID_INVALID.
     */
    public function get_hardwareId()
    {
        $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) {
            $this->isOnline();
            $resolve = YAPI::resolveFunction($this->_className, $this->_func);
            if($resolve->errorType != YAPI_SUCCESS) {
                return $this->_throw($resolve->errorType, $resolve->errorMsg, Y_HARDWAREID_INVALID);
            }
        }
        return $resolve->result;
    }

    /**
     * Returns the hardware identifier of the function, without reference to the module. For example
     * relay1
     * 
     * @return a string that identifies the function (ex: relay1)
     * 
     * On failure, throws an exception or returns  Y_FUNCTIONID_INVALID.
     */
    public function get_functionId()
    {
        $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) {
            $this->isOnline();
            $resolve = YAPI::resolveFunction($this->_className, $this->_func);
            if($resolve->errorType != YAPI_SUCCESS) {
                return $this->_throw($resolve->errorType, $resolve->errorMsg, Y_FUNCTIONID_INVALID);
            }
        }
        return substr($resolve->result,strpos($resolve->result,'.')+1);
    }

        /**
         * Returns a global identifier of the function in the format MODULE_NAME&#46;FUNCTION_NAME.
         * The returned string uses the logical names of the module and of the function if they are defined,
         * otherwise the serial number of the module and the hardware identifier of the function
         * (for exemple: MyCustomName.relay1)
         * 
         * @return a string that uniquely identifies the function using logical names
         *         (ex: MyCustomName.relay1)
         * 
         * On failure, throws an exception or returns  Y_FRIENDLYNAME_INVALID.
         */
    public function get_friendlyName()
    {
        $resolve = YAPI::getFriendlyNameFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) {
            $this->isOnline();
            $resolve = YAPI::getFriendlyNameFunction($this->_className, $this->_func);
            if($resolve->errorType != YAPI_SUCCESS) {
                return $this->_throw($resolve->errorType, $resolve->errorMsg, Y_FRIENDLYNAME_INVALID);
            }
        }
        return $resolve->result;
    }


    // Return the value of an attribute from function cache, after reloading it from device if needed
    // Note: the function cache is a typed (parsed) cache, contrarily to the agnostic device cache
    protected function _getAttr($str_attr)
    {
        if($this->_cache['_expiration'] <= YAPI::GetTickCount()) {
            // no valid cached value, reload from device
            if($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) return null;
        }
        if(!isset($this->_cache[$str_attr])) {
            $this->_throw(YAPI_VERSION_MISMATCH, 'No such attribute $str_attr in function', null);
        }
        return $this->_cache[$str_attr];
    }

    // Return the value of an attribute from function cache, after loading it from device if never done
    protected function _getFixedAttr($str_attr)
    {
        if($this->_cache['_expiration'] == 0) {
            // no cached value, load from device
            if($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) return null;
        }
        if(!isset($this->_cache[$str_attr])) {
            $this->_throw(YAPI_VERSION_MISMATCH, "No such attribute $str_attr in function", null);
        }
        return $this->_cache[$str_attr];
    }

    // Change the value of an attribute on a device, and update cache on the fly
    // Note: the function cache is a typed (parsed) cache, contrarily to the agnostic device cache
    protected function _setAttr($str_attr, $str_newval)
    {
        if(!isset($str_newval)) {
            $this->_throw(YAPI_INVALID_ARGUMENT, "Undefined value to set for attribute $str_attr", null);
        }
        // urlencode according to RFC 3986 instead of php default RFC 1738
        $safecodes = array('%21','%23','%24','%27','%28','%29','%2A','%2C','%2F','%3A','%3B','%40','%3F','%5B','%5D');
        $safechars = array('!',  "#",  "$",  "'",  "(",  ")",  '*',  ",",  "/",  ":",  ";",  "@",  "?",  "[",  "]");
        $attrname = str_replace($safecodes, $safechars, urlencode($str_attr));
        $extra = "/$attrname?$attrname=".str_replace($safecodes, $safechars, urlencode($str_newval));
        $yreq = YAPI::funcRequest($this->_className, $this->_func, $extra);
        if($this->_cache['_expiration'] != 0){
            $this->_cache['_expiration'] = YAPI::GetTickCount();
        }
        if($yreq->errorType != YAPI_SUCCESS) {
            return $this->_throw($yreq->errorType, $yreq->errorMsg, $yreq->errorType);
        }
        return YAPI_SUCCESS;
    }

    // Execute an arbitrary HTTP GET request on the device and return the binary content
    //
    protected function _download($str_path)
    {
        // get the device serial number
        $devid = $this->module()->get_serialNumber();
        if($devid == Y_SERIALNUMBER_INVALID) {
            return '';
        }
        $yreq = YAPI::devRequest($devid, "GET /$str_path");
        if($yreq->errorType != YAPI_SUCCESS) {
            return $this->_throw($yreq->errorType, $yreq->errorMsg, '');
        }
        return $yreq->result;
    }    

    // Upload a file to the filesystem, to the specified full path name.
    // If a file already exists with the same path name, its content is overwritten.
    //
    protected function _upload($str_path, $bin_content)
    {
        // get the device serial number
        $devid = $this->module()->get_serialNumber();
        if($devid == Y_SERIALNUMBER_INVALID) {
            return $this->get_errorType();
        }
        if(is_array($bin_content)) {
            $bin_content = call_user_func_array('pack', array_merge(array("C*"), $bin_content));
        }
        $httpreq = 'POST /upload.html';
    	$body = "Content-Disposition: form-data; name=\"$str_path\"; filename=\"api\"\r\n".
                "Content-Type: application/octet-stream\r\n".
                "Content-Transfer-Encoding: binary\r\n\r\n".$bin_content;
        $yreq = YAPI::devRequest($devid, $httpreq, true, $body);
        if($yreq->errorType != YAPI_SUCCESS) {
            return $yreq->errorType;
        }
        return YAPI_SUCCESS;        
    }    

    // Get a value from a JSON buffer
    //
    protected function _json_get_key($str_jsonbuff, $str_key)
    {
        $loadval = json_decode($str_jsonbuff, true);
        if(isset($loadval[$str_key])) {
            return $loadval[$str_key];
        }
        return "";
    }
    
    // Get an array of strings from a JSON buffer
    //
    protected function _json_get_array($str_jsonbuff)
    {
        $loadval = json_decode($str_jsonbuff, true);
        $res = Array();
        foreach($loadval as $record) {
            $res[] = json_encode($record);
        }
        return $res;
    }
    
    // Encode calibration points into fixed-point 16-bit integers
    //
    protected function _encodeCalibrationPoints($arr_rawValues, $arr_refValues)
    {
        $npt = (sizeof($arr_rawValues) < sizeof($arr_refValues) ? sizeof($arr_rawValues) : sizeof($arr_refValues));
        if($npt == 0) {
            return '0';
        }
        $params = $this->get_calibrationParam();
        if($params == '') {
            $caltype = 10 + $npt;
        } else {
            $params = explode(',', $params);
            $caltype = intVal($params[0]);
            if($caltype <= 10) {
                $caltype = $npt;
            } else {
                $caltype = 10+$npt;
            }
        }
        $res = "$caltype";
        if($caltype <= 10) {
            // 16-bit fixed-point encoding
            $resol = $this->get_resolution();
            if($resol == Y_INVALID_FLOAT) {
                return '';
            }
            $minRaw = 0;
            $cnt = 0;
            for($i = 0; $i < $npt; $i++) {
                $rawVal = round($arr_rawValues[$i] / $resol - $this->_calibrationOffset);
                if($rawVal >= $minRaw && $rawVal < 65536) {
                    $refVal = round($arr_refValues[$i] / $resol - $this->_calibrationOffset);
                    if($refVal >= 0 && $refVal < 65536) {
                        $res .= ",$rawVal,$refVal";
                        $minRaw = $rawVal+1;
                        $cnt++;
                    }
                }
            }
        } else {
            // 16-bit floating-point decimal encoding
            for($i = 0; $i < $npt; $i++) {
                $rawVal = YAPI::doubleToDecimal($arr_rawValues[$i]);
                $refVal = YAPI::doubleToDecimal($arr_refValues[$i]);
                $res .= ",$rawVal,$refVal";
            }            
        }
    
        return $res;
    }

    // Decode calibration points from fixed-point 16-bit integers
    //
    protected function _decodeCalibrationPoints(&$rawPt, &$calPt)
    {
        $rawPt = Array();
        $calPt = Array();
        
        $params = $this->get_calibrationParam();
        if($params == '' || $params == '0') return YAPI_SUCCESS;
        $params = explode(',', $params);
        if(sizeof($params) < 11) {
            return YAPI_NOT_SUPPORTED;
        }
        $resol = $this->get_resolution();
        if($resol == Y_INVALID_FLOAT) {
            return YAPI_NOT_SUPPORTED;
        }
        $ctyp = intVal($params[0]);
        $nval = ($ctyp <= 20 ? 2*($nval % 10) : 99);
        for($i = 1; $i < sizeof($params) && $i < $nval; $i += 2) {
            $rawval = intVal($params[$i]);
            $calval = intVal($params[$i+1]);
            if(ctyp <= 10) {
                $rawPt[$i>>1]  = ($rawval + $this->_calibrationOffset) * $resol;
                $calPt[$i>>1]  = ($calval + $this->_calibrationOffset) * $resol;
            } else {
                $rawPt[$i>>1]  = YAPI::decimalToDouble($rawval);
                $calPt[$i>>1]  = YAPI::decimalToDouble($calval);                
            }
        }
        return YAPI_SUCCESS;
    }
    
    public function _getValueCallback()
    {
        return $this->_valueCallback;
    }

    /**
     * Checks if the function is currently reachable, without raising any error.
     * If there is a cached value for the function in cache, that has not yet
     * expired, the device is considered reachable.
     * No exception is raised if there is an error while trying to contact the
     * device hosting the requested function.
     * 
     * @return true if the function can be reached, and false otherwise
     */
    public function isOnline()
    {
        // A valid value in cache means that the device is online
        if($this->_cache['_expiration'] > YAPI::GetTickCount()) return true;

        // Check that the function is available without throwing exceptions
        $yreq = YAPI::funcRequest($this->_className, $this->_func, '');
        if($yreq->errorType != YAPI_SUCCESS) {
            return false;
        }
        // save result in cache anyway
        $loadval = $yreq->result;
        $loadval['_expiration'] = YAPI::GetTickCount() + YAPI::$defaultCacheValidity;
        $this->_cache = $loadval;

        return true;
    }

    /**
     * Returns the numerical error code of the latest error with this function.
     * This method is mostly useful when using the Yoctopuce library with
     * exceptions disabled.
     * 
     * @return a number corresponding to the code of the latest error that occured while
     *         using this function object
     */
    public function get_errorType()
    {
        return $this->_lastErrorType;
    }
    public function errorType()
    {
        return $this->_lastErrorType;
    }
    public function errType()
    {
        return $this->_lastErrorType;
    }

    /**
     * Returns the error message of the latest error with this function.
     * This method is mostly useful when using the Yoctopuce library with
     * exceptions disabled.
     * 
     * @return a string corresponding to the latest error message that occured while
     *         using this function object
     */
    public function get_errorMessage()
    {
        return $this->_lastErrorMsg;
    }
    public function errorMessage()
    {
        return $this->_lastErrorMsg;
    }
    public function errMessage()
    {
        return $this->_lastErrorMsg;
    }

    /**
     * Preloads the function cache with a specified validity duration.
     * By default, whenever accessing a device, all function attributes
     * are kept in cache for the standard duration (5 ms). This method can be
     * used to temporarily mark the cache as valid for a longer period, in order
     * to reduce network trafic for instance.
     * 
     * @param msValidity : an integer corresponding to the validity attributed to the
     *         loaded function parameters, in milliseconds
     * 
     * @return YAPI_SUCCESS when the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function load($int_msValidity)
    {
        $yreq = YAPI::funcRequest($this->_className, $this->_func, '');
        if($yreq->errorType != YAPI_SUCCESS) {
            return $this->_throw($yreq->errorType, $yreq->errorMsg, $yreq->errorType);
        }
        $loadval = $yreq->result;
        $loadval['_expiration'] = YAPI::GetTickCount() + $int_msValidity;
        $this->_cache = $loadval;

        return YAPI_SUCCESS;
    }

    /**
     * Gets the YModule object for the device on which the function is located.
     * If the function cannot be located on any module, the returned instance of
     * YModule is not shown as on-line.
     * 
     * @return an instance of YModule
     */
    public function get_module()
    {
        // try to resolve the function name to a device id without query
        $hwid = $this->_func;
        if(strpos($hwid, '.') === FALSE) {
            $resolve = YAPI::resolveFunction($this->_className, $this->_func);
            if($resolve->errorType == YAPI_SUCCESS) $hwid = $resolve->result;
        }
        $dotidx = strpos($hwid, '.');
        if($dotidx !== FALSE) {
            // resolution worked
            return yFindModule(substr($hwid, 0, $dotidx));
        }

        // device not resolved for now, force a communication for a last chance resolution
        if($this->load(YAPI::$defaultCacheValidity) == YAPI_SUCCESS) {
            $resolve = YAPI::resolveFunction($this->_className, $this->_func);
            if($resolve->errorType == YAPI_SUCCESS) $hwid = $resolve->result;
        }
        $dotidx = strpos($hwid, '.');
        if($dotidx !== FALSE) {
            // resolution worked
            return yFindModule(substr($hwid, 0, $dotidx));
        }
        // return a true yFindModule object even if it is not a module valid for communicating
        return yFindModule('module_of_'.$this->_className.'_'.$this->_func);
    }
    public function module() 
    { return $this->get_module(); }

    /**
     * Returns a unique identifier of type YFUN_DESCR corresponding to the function.
     * This identifier can be used to test if two instances of YFunction reference the same
     * physical function on the same physical device.
     * 
     * @return an identifier of type YFUN_DESCR.
     * 
     * If the function has never been contacted, the returned value is Y_FUNCTIONDESCRIPTOR_INVALID.
     */
    public function get_functionDescriptor()
    {
        // try to resolve the function name to a device id without query
        $hwid = $this->_func;
        if(strpos($hwid, '.') === FALSE) {
            $resolve = YAPI::resolveFunction($this->_className, $this->_func);
            if($resolve->errorType != YAPI_SUCCESS) $hwid = $resolve->result;
        }
        $dotidx = strpos($hwid, '.');
        if($dotidx !== FALSE) {
            // resolution worked
            return $hwid;
        }
        return Y_FUNCTIONDESCRIPTOR_INVALID;
    }
    public function getFunctionDescriptor()
    { return $this->get_functionDescriptor(); }

    /**
     * Returns the value of the userData attribute, as previously stored using method
     * set_userData.
     * This attribute is never touched directly by the API, and is at disposal of the caller to
     * store a context.
     * 
     * @return the object stored previously by the caller.
     */
    public function get_userData()
    {
        return $this->_userData;
    }
    public function userData()
    {
        return $this->_userData;
    }

    /**
     * Stores a user context provided as argument in the userData attribute of the function.
     * This attribute is never touched by the API, and is at disposal of the caller to store a context.
     * 
     * @param data : any kind of object to be stored
     * @noreturn
     */
    public function set_userData($data)
    {
        $this->_userData = $data;
    }
    public function setUserData($data)
    {
        $this->_userData = $data;
    }

    /**
     * Registers the callback function that is invoked on every change of advertised value.
     * The callback is invoked only during the execution of ySleep or yHandleEvents.
     * This provides control over the time when the callback is triggered. For good responsiveness, remember to call
     * one of these two functions periodically. To unregister a callback, pass a null pointer as argument.
     * 
     * @param callback : the callback function to call, or a null pointer. The callback function should take two
     *         arguments: the function object of which the value has changed, and the character string describing
     *         the new advertised value.
     * @noreturn
     */
    public function registerValueCallback($func_callback)
    {
        $this->_valueCallback = $func_callback;
        if(!is_null($func_callback) && $func_callback != "" && $this->isOnline()) {
            $newval = $this->get_advertisedValue();
            if($newval != '' && $newval != '!INVALID!') {
                $func_callback($this, $newval);
            }
        }
    }
}

/**
 * YModule Class: Module control interface
 * 
 * This interface is identical for all Yoctopuce USB modules.
 * It can be used to control the module global parameters, and
 * to enumerate the functions provided by each module.
 */
class YModule extends YFunction
{
    // Return the internal device object hosting the function
    protected function _getDev()
    {
        $devid = $this->_func;
        $dotidx = strpos($devid, '.');
        if($dotidx !== false) $devid = substr($devid, 0, $dotidx);
        $dev = YAPI::getDevice($devid);
        if(is_null($dev)) {
            $this->_throw(YAPI_DEVICE_NOT_FOUND, "Device [$devid] is not online", null);
        }
        return $dev;
    }

    // Return the number of functions (beside "module") available on the device
    public function functionCount()
    {
        $dev = $this->_getDev();
        return $dev->functionCount();
    }

    // Retrieve the Hardware Id of the nth function (beside "module") in the device
    public function functionId($int_functionIndex)
    {
        $dev = $this->_getDev();
        return $dev->functionId($int_functionIndex);
    }
    // Retrieve the name of the nth function (beside "module") in the device
    public function functionName($int_functionIndex)
    {
        $devid = $this->_func;
        $dotidx = strpos($devid, '.');
        if($dotidx !== FALSE) $devid = substr($devid, 0, $dotidx);
        $dev = YAPI::getDevice($devid);
        return $dev->functionName($int_functionIndex);
    }

    // Retrieve the advertised value of the nth function (beside "module") in the device
    public function functionValue($int_functionIndex)
    {
        $dev = $this->_getDev();
        return $dev->functionValue($int_functionIndex);
    }

    //--- (generated code: YModule implementation)
    const PRODUCTNAME_INVALID = Y_INVALID_STRING;
    const SERIALNUMBER_INVALID = Y_INVALID_STRING;
    const LOGICALNAME_INVALID = Y_INVALID_STRING;
    const PRODUCTID_INVALID = Y_INVALID_UNSIGNED;
    const PRODUCTRELEASE_INVALID = Y_INVALID_UNSIGNED;
    const FIRMWARERELEASE_INVALID = Y_INVALID_STRING;
    const PERSISTENTSETTINGS_LOADED = 0;
    const PERSISTENTSETTINGS_SAVED = 1;
    const PERSISTENTSETTINGS_MODIFIED = 2;
    const PERSISTENTSETTINGS_INVALID = -1;
    const LUMINOSITY_INVALID = Y_INVALID_UNSIGNED;
    const BEACON_OFF = 0;
    const BEACON_ON = 1;
    const BEACON_INVALID = -1;
    const UPTIME_INVALID = Y_INVALID_UNSIGNED;
    const USBCURRENT_INVALID = Y_INVALID_UNSIGNED;
    const REBOOTCOUNTDOWN_INVALID = Y_INVALID_SIGNED;
    const USBBANDWIDTH_SIMPLE = 0;
    const USBBANDWIDTH_DOUBLE = 1;
    const USBBANDWIDTH_INVALID = -1;

    /**
     * Returns the commercial name of the module, as set by the factory.
     * 
     * @return a string corresponding to the commercial name of the module, as set by the factory
     * 
     * On failure, throws an exception or returns Y_PRODUCTNAME_INVALID.
     */
    public function get_productName()
    {   $dev = $this->_getDev();
        $json_val = ($dev == null ? null : $dev->getProductName());
        return (is_null($json_val) ? Y_PRODUCTNAME_INVALID : $json_val);
    }

    /**
     * Returns the serial number of the module, as set by the factory.
     * 
     * @return a string corresponding to the serial number of the module, as set by the factory
     * 
     * On failure, throws an exception or returns Y_SERIALNUMBER_INVALID.
     */
    public function get_serialNumber()
    {   $dev = $this->_getDev();
        $json_val = ($dev == null ? null : $dev->getSerialNumber());
        return (is_null($json_val) ? Y_SERIALNUMBER_INVALID : $json_val);
    }

    /**
     * Returns the logical name of the module.
     * 
     * @return a string corresponding to the logical name of the module
     * 
     * On failure, throws an exception or returns Y_LOGICALNAME_INVALID.
     */
    public function get_logicalName()
    {   $dev = $this->_getDev();
        if(!is_null($dev) && $this->_cache['_expiration'] <= YAPI::GetTickCount())
            return $dev->getLogicalName();
        $json_val = $this->_getAttr("logicalName");
        return (is_null($json_val) ? Y_LOGICALNAME_INVALID : $json_val);
    }

    /**
     * Changes the logical name of the module. You can use yCheckLogicalName()
     * prior to this call to make sure that your parameter is valid.
     * Remember to call the saveToFlash() method of the module if the
     * modification must be kept.
     * 
     * @param newval : a string corresponding to the logical name of the module
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_logicalName($newval)
    {
        $rest_val = $newval;
        $res = $this->_setAttr("logicalName",$rest_val);
        $dev = $this->_getDev();
        if(!is_null($dev)) $dev->refresh();
        return $res;
    }

    /**
     * Returns the USB device identifier of the module.
     * 
     * @return an integer corresponding to the USB device identifier of the module
     * 
     * On failure, throws an exception or returns Y_PRODUCTID_INVALID.
     */
    public function get_productId()
    {   $dev = $this->_getDev();
        $json_val = ($dev == null ? null : $dev->getProductId());
        return (is_null($json_val) ? Y_PRODUCTID_INVALID : intval($json_val));
    }

    /**
     * Returns the hardware release version of the module.
     * 
     * @return an integer corresponding to the hardware release version of the module
     * 
     * On failure, throws an exception or returns Y_PRODUCTRELEASE_INVALID.
     */
    public function get_productRelease()
    {   $json_val = $this->_getFixedAttr("productRelease");
        return (is_null($json_val) ? Y_PRODUCTRELEASE_INVALID : intval($json_val));
    }

    /**
     * Returns the version of the firmware embedded in the module.
     * 
     * @return a string corresponding to the version of the firmware embedded in the module
     * 
     * On failure, throws an exception or returns Y_FIRMWARERELEASE_INVALID.
     */
    public function get_firmwareRelease()
    {   $json_val = $this->_getAttr("firmwareRelease");
        return (is_null($json_val) ? Y_FIRMWARERELEASE_INVALID : $json_val);
    }

    /**
     * Returns the current state of persistent module settings.
     * 
     * @return a value among Y_PERSISTENTSETTINGS_LOADED, Y_PERSISTENTSETTINGS_SAVED and
     * Y_PERSISTENTSETTINGS_MODIFIED corresponding to the current state of persistent module settings
     * 
     * On failure, throws an exception or returns Y_PERSISTENTSETTINGS_INVALID.
     */
    public function get_persistentSettings()
    {   $json_val = $this->_getAttr("persistentSettings");
        return (is_null($json_val) ? Y_PERSISTENTSETTINGS_INVALID : intval($json_val));
    }

    public function set_persistentSettings($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("persistentSettings",$rest_val);
    }

    /**
     * Saves current settings in the nonvolatile memory of the module.
     * Warning: the number of allowed save operations during a module life is
     * limited (about 100000 cycles). Do not call this function within a loop.
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function saveToFlash()
    {
        $rest_val = '1';
        return $this->_setAttr("persistentSettings",$rest_val);
    }

    /**
     * Reloads the settings stored in the nonvolatile memory, as
     * when the module is powered on.
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function revertFromFlash()
    {
        $rest_val = '0';
        return $this->_setAttr("persistentSettings",$rest_val);
    }

    /**
     * Returns the luminosity of the  module informative leds (from 0 to 100).
     * 
     * @return an integer corresponding to the luminosity of the  module informative leds (from 0 to 100)
     * 
     * On failure, throws an exception or returns Y_LUMINOSITY_INVALID.
     */
    public function get_luminosity()
    {   $json_val = $this->_getAttr("luminosity");
        return (is_null($json_val) ? Y_LUMINOSITY_INVALID : intval($json_val));
    }

    /**
     * Changes the luminosity of the module informative leds. The parameter is a
     * value between 0 and 100.
     * Remember to call the saveToFlash() method of the module if the
     * modification must be kept.
     * 
     * @param newval : an integer corresponding to the luminosity of the module informative leds
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_luminosity($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("luminosity",$rest_val);
    }

    /**
     * Returns the state of the localization beacon.
     * 
     * @return either Y_BEACON_OFF or Y_BEACON_ON, according to the state of the localization beacon
     * 
     * On failure, throws an exception or returns Y_BEACON_INVALID.
     */
    public function get_beacon()
    {   $dev = $this->_getDev();
        if(!is_null($dev) && $this->_cache['_expiration'] <= YAPI::GetTickCount())
            return $dev->getBeacon();
        $json_val = $this->_getAttr("beacon");
        return (is_null($json_val) ? Y_BEACON_INVALID : intval($json_val));
    }

    /**
     * Turns on or off the module localization beacon.
     * 
     * @param newval : either Y_BEACON_OFF or Y_BEACON_ON
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_beacon($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("beacon",$rest_val);
    }

    /**
     * Returns the number of milliseconds spent since the module was powered on.
     * 
     * @return an integer corresponding to the number of milliseconds spent since the module was powered on
     * 
     * On failure, throws an exception or returns Y_UPTIME_INVALID.
     */
    public function get_upTime()
    {   $json_val = $this->_getAttr("upTime");
        return (is_null($json_val) ? Y_UPTIME_INVALID : intval($json_val));
    }

    /**
     * Returns the current consumed by the module on the USB bus, in milli-amps.
     * 
     * @return an integer corresponding to the current consumed by the module on the USB bus, in milli-amps
     * 
     * On failure, throws an exception or returns Y_USBCURRENT_INVALID.
     */
    public function get_usbCurrent()
    {   $json_val = $this->_getAttr("usbCurrent");
        return (is_null($json_val) ? Y_USBCURRENT_INVALID : intval($json_val));
    }

    /**
     * Returns the remaining number of seconds before the module restarts, or zero when no
     * reboot has been scheduled.
     * 
     * @return an integer corresponding to the remaining number of seconds before the module restarts, or zero when no
     *         reboot has been scheduled
     * 
     * On failure, throws an exception or returns Y_REBOOTCOUNTDOWN_INVALID.
     */
    public function get_rebootCountdown()
    {   $json_val = $this->_getAttr("rebootCountdown");
        return (is_null($json_val) ? Y_REBOOTCOUNTDOWN_INVALID : intval($json_val));
    }

    public function set_rebootCountdown($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("rebootCountdown",$rest_val);
    }

    /**
     * Schedules a simple module reboot after the given number of seconds.
     * 
     * @param secBeforeReboot : number of seconds before rebooting
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function reboot($int_secBeforeReboot)
    {
        $rest_val = strval($int_secBeforeReboot);
        return $this->_setAttr("rebootCountdown",$rest_val);
    }

    /**
     * Schedules a module reboot into special firmware update mode.
     * 
     * @param secBeforeReboot : number of seconds before rebooting
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function triggerFirmwareUpdate($int_secBeforeReboot)
    {
        $rest_val = strval(-$int_secBeforeReboot);
        return $this->_setAttr("rebootCountdown",$rest_val);
    }

    /**
     * Returns the number of USB interfaces used by the module.
     * 
     * @return either Y_USBBANDWIDTH_SIMPLE or Y_USBBANDWIDTH_DOUBLE, according to the number of USB
     * interfaces used by the module
     * 
     * On failure, throws an exception or returns Y_USBBANDWIDTH_INVALID.
     */
    public function get_usbBandwidth()
    {   $json_val = $this->_getAttr("usbBandwidth");
        return (is_null($json_val) ? Y_USBBANDWIDTH_INVALID : intval($json_val));
    }

    /**
     * Changes the number of USB interfaces used by the module. You must reboot the module
     * after changing this setting.
     * 
     * @param newval : either Y_USBBANDWIDTH_SIMPLE or Y_USBBANDWIDTH_DOUBLE, according to the number of
     * USB interfaces used by the module
     * 
     * @return YAPI_SUCCESS if the call succeeds.
     * 
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_usbBandwidth($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("usbBandwidth",$rest_val);
    }

    /**
     * Downloads the specified built-in file and returns a binary buffer with its content.
     * 
     * @param pathname : name of the new file to load
     * 
     * @return a binary buffer with the file content
     * 
     * On failure, throws an exception or returns an empty content.
     */
    public function download($str_pathname)
    {
        return $this->_download($str_pathname);
        
    }

    /**
     * Returns the icon of the module. The icon is a PNG image and does not
     * exceeds 1536 bytes.
     * 
     * @return a binary buffer with module icon, in png format.
     */
    public function get_icon2d()
    {
        return $this->_download('icon2d.png');
        
    }

    /**
     * Returns a string with last logs of the module. This method return only
     * logs that are still in the module.
     * 
     * @return a string with last logs of the module.
     */
    public function get_lastLogs()
    {
        // $content is a bin;
        $content = $this->_download('logs.txt');
        return $content;
        
    }

    public function productName()
    { return get_productName(); }

    public function serialNumber()
    { return get_serialNumber(); }

    public function logicalName()
    { return get_logicalName(); }

    public function setLogicalName($newval)
    { return set_logicalName($newval); }

    public function productId()
    { return get_productId(); }

    public function productRelease()
    { return get_productRelease(); }

    public function firmwareRelease()
    { return get_firmwareRelease(); }

    public function persistentSettings()
    { return get_persistentSettings(); }

    public function setPersistentSettings($newval)
    { return set_persistentSettings($newval); }

    public function luminosity()
    { return get_luminosity(); }

    public function setLuminosity($newval)
    { return set_luminosity($newval); }

    public function beacon()
    { return get_beacon(); }

    public function setBeacon($newval)
    { return set_beacon($newval); }

    public function upTime()
    { return get_upTime(); }

    public function usbCurrent()
    { return get_usbCurrent(); }

    public function rebootCountdown()
    { return get_rebootCountdown(); }

    public function setRebootCountdown($newval)
    { return set_rebootCountdown($newval); }

    public function usbBandwidth()
    { return get_usbBandwidth(); }

    public function setUsbBandwidth($newval)
    { return set_usbBandwidth($newval); }

    /**
     * Continues the module enumeration started using yFirstModule().
     * 
     * @return a pointer to a YModule object, corresponding to
     *         the next module found, or a null pointer
     *         if there are no more modules to enumerate.
     */
    public function nextModule()
    {   $next_hwid = YAPI::getNextHardwareId($this->_className, $this->_func);
        if($next_hwid == null) return null;
        return yFindModule($next_hwid);
    }

    /**
     * Allows you to find a module from its serial number or from its logical name.
     * 
     * This function does not require that the module is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method YModule.isOnline() to test if the module is
     * indeed online at a given time. In case of ambiguity when looking for
     * a module by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     * 
     * @param func : a string containing either the serial number or
     *         the logical name of the desired module
     * 
     * @return a YModule object allowing you to drive the module
     *         or get additional information on the module.
     */
    public static function FindModule($str_func)
    {   $obj_func = YAPI::getFunction('Module', $str_func);
        if($obj_func) return $obj_func;
        return new YModule($str_func);
    }

    /**
     * Starts the enumeration of modules currently accessible.
     * Use the method YModule.nextModule() to iterate on the
     * next modules.
     * 
     * @return a pointer to a YModule object, corresponding to
     *         the first module currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstModule()
    {   $next_hwid = YAPI::getFirstHardwareId('Module');
        if($next_hwid == null) return null;
        return self::FindModule($next_hwid);
    }

    //--- (end of generated code: YModule implementation)

    function __construct($str_func)
    {
        //--- (generated code: YModule constructor)
        parent::__construct('Module', $str_func);
        //--- (end of generated code: YModule constructor)
    }
};

/**
 * Returns the version identifier for the Yoctopuce library in use.
 * The version is a string in the form "Major.Minor.Build",
 * for instance "1.01.5535". For languages using an external
 * DLL (for instance C#, VisualBasic or Delphi), the character string
 * includes as well the DLL version, for instance
 * "1.01.5535 (1.01.5439)".
 * 
 * If you want to verify in your code that the library version is
 * compatible with the version that you have used during development,
 * verify that the major number is strictly equal and that the minor
 * number is greater or equal. The build number is not relevant
 * with respect to the library compatibility.
 * 
 * @return a character string describing the library version.
 */
function yGetAPIVersion()
{
    return YAPI::GetAPIVersion();
}

/**
 * Initializes the Yoctopuce programming library explicitly.
 * It is not strictly needed to call yInitAPI(), as the library is
 * automatically  initialized when calling yRegisterHub() for the
 * first time.
 * 
 * When Y_DETECT_NONE is used as detection mode,
 * you must explicitly use yRegisterHub() to point the API to the
 * VirtualHub on which your devices are connected before trying to access them.
 * 
 * @param mode : an integer corresponding to the type of automatic
 *         device detection to use. Possible values are
 *         Y_DETECT_NONE, Y_DETECT_USB, Y_DETECT_NET,
 *         and Y_DETECT_ALL.
 * @param errmsg : a string passed by reference to receive any error message.
 * 
 * @return YAPI_SUCCESS when the call succeeds.
 * 
 * On failure, throws an exception or returns a negative error code.
 */
function yInitAPI($int_mode=0,&$str_errmsg="") 
{
    return YAPI::InitAPI($int_mode,$str_errmsg);
}

/**
 * Frees dynamically allocated memory blocks used by the Yoctopuce library.
 * It is generally not required to call this function, unless you
 * want to free all dynamically allocated memory blocks in order to
 * track a memory leak for instance.
 * You should not call any other library function after calling
 * yFreeAPI(), or your program will crash.
 */
function yFreeAPI() 
{
    YAPI::FreeAPI();
}

/**
 * Disables the use of exceptions to report runtime errors.
 * When exceptions are disabled, every function returns a specific
 * error value which depends on its type and which is documented in
 * this reference manual.
 */
function yDisableExceptions() 
{
    YAPI::DisableExceptions();
}

/**
 * Re-enables the use of exceptions for runtime error handling.
 * Be aware than when exceptions are enabled, every function that fails
 * triggers an exception. If the exception is not caught by the user code,
 * it  either fires the debugger or aborts (i.e. crash) the program.
 * On failure, throws an exception or returns a negative error code.
 */
function yEnableExceptions() 
{
    YAPI::EnableExceptions();
}

/**
 * Setup the Yoctopuce library to use modules connected on a given machine.
 * When using Yoctopuce modules through the VirtualHub gateway,
 * you should provide as parameter the address of the machine on which the
 * VirtualHub software is running (typically "http://127.0.0.1:4444",
 * which represents the local machine).
 * When you use a language which has direct access to the USB hardware,
 * you can use the pseudo-URL "usb" instead.
 * 
 * Be aware that only one application can use direct USB access at a
 * given time on a machine. Multiple access would cause conflicts
 * while trying to access the USB modules. In particular, this means
 * that you must stop the VirtualHub software before starting
 * an application that uses direct USB access. The workaround
 * for this limitation is to setup the library to use the VirtualHub
 * rather than direct USB access.
 * 
 * If acces control has been activated on the VirtualHub you want to
 * reach, the URL parameter should look like:
 * 
 * http://username:password@adresse:port
 * 
 * @param url : a string containing either "usb" or the
 *         root URL of the hub to monitor
 * @param errmsg : a string passed by reference to receive any error message.
 * 
 * @return YAPI_SUCCESS when the call succeeds.
 * 
 * On failure, throws an exception or returns a negative error code.
 */
function yRegisterHub($str_url,&$str_errmsg="")
{
    return YAPI::RegisterHub($str_url,$str_errmsg);
}


/**
 * 
 */
function yPreregisterHub($str_url,&$str_errmsg="")
{
    return YAPI::PreregisterHub($str_url,$str_errmsg);
}

/**
 * Setup the Yoctopuce library to no more use modules connected on a previously
 * registered machine with RegisterHub.
 * 
 * @param url : a string containing either "usb" or the
 *         root URL of the hub to monitor
 */
function yUnregisterHub($str_url)
{
    return YAPI::UnregisterHub($str_url);
}




/**
 * Triggers a (re)detection of connected Yoctopuce modules.
 * The library searches the machines or USB ports previously registered using
 * yRegisterHub(), and invokes any user-defined callback function
 * in case a change in the list of connected devices is detected.
 * 
 * This function can be called as frequently as desired to refresh the device list
 * and to make the application aware of hot-plug events.
 * 
 * @param errmsg : a string passed by reference to receive any error message.
 * 
 * @return YAPI_SUCCESS when the call succeeds.
 * 
 * On failure, throws an exception or returns a negative error code.
 */
function yUpdateDeviceList(&$str_errmsg="")
{
    return YAPI::UpdateDeviceList($str_errmsg);
}

/**
 * Maintains the device-to-library communication channel.
 * If your program includes significant loops, you may want to include
 * a call to this function to make sure that the library takes care of
 * the information pushed by the modules on the communication channels.
 * This is not strictly necessary, but it may improve the reactivity
 * of the library for the following commands.
 * 
 * This function may signal an error in case there is a communication problem
 * while contacting a module.
 * 
 * @param errmsg : a string passed by reference to receive any error message.
 * 
 * @return YAPI_SUCCESS when the call succeeds.
 * 
 * On failure, throws an exception or returns a negative error code.
 */
function yHandleEvents(&$str_errmsg="")
{ 
    return YAPI::HandleEvents($str_errmsg); 
}

/**
 * Pauses the execution flow for a specified duration.
 * This function implements a passive waiting loop, meaning that it does not
 * consume CPU cycles significatively. The processor is left available for
 * other threads and processes. During the pause, the library nevertheless
 * reads from time to time information from the Yoctopuce modules by
 * calling yHandleEvents(), in order to stay up-to-date.
 * 
 * This function may signal an error in case there is a communication problem
 * while contacting a module.
 * 
 * @param ms_duration : an integer corresponding to the duration of the pause,
 *         in milliseconds.
 * @param errmsg : a string passed by reference to receive any error message.
 * 
 * @return YAPI_SUCCESS when the call succeeds.
 * 
 * On failure, throws an exception or returns a negative error code.
 */
function ySleep($int_ms_duration, &$str_errmsg="")
{ 
    return YAPI::Sleep($int_ms_duration, $str_errmsg); 
}

/**
 * Returns the current value of a monotone millisecond-based time counter.
 * This counter can be used to compute delays in relation with
 * Yoctopuce devices, which also uses the milisecond as timebase.
 * 
 * @return a long integer corresponding to the millisecond counter.
 */
function yGetTickCount()
{
    return YAPI::GetTickCount();
}

/**
 * Checks if a given string is valid as logical name for a module or a function.
 * A valid logical name has a maximum of 19 characters, all among
 * A..Z, a..z, 0..9, _, and -.
 * If you try to configure a logical name with an incorrect string,
 * the invalid characters are ignored.
 * 
 * @param name : a string containing the name to check.
 * 
 * @return true if the name is valid, false otherwise.
 */
function yCheckLogicalName($str_name)
{
    return YAPI::CheckLogicalName($str_name);
}

/**
 * Register a callback function, to be called each time
 * a device is pluged. This callback will be invoked while yUpdateDeviceList
 * is running. You will have to call this function on a regular basis.
 * 
 * @param arrivalCallback : a procedure taking a YModule parameter, or null
 *         to unregister a previously registered  callback.
 */
function yRegisterDeviceArrivalCallback($func_arrivalCallback)
{
    return YAPI::RegisterDeviceArrivalCallback($func_arrivalCallback);
}

/**
 * Register a device logical name change callback
 */
function yRegisterDeviceChangeCallback($func_changeCallback)
{
    return YAPI::RegisterDeviceChangeCallback($func_changeCallback);
}

/**
 * Register a callback function, to be called each time
 * a device is unpluged. This callback will be invoked while yUpdateDeviceList
 * is running. You will have to call this function on a regular basis.
 * 
 * @param removalCallback : a procedure taking a YModule parameter, or null
 *         to unregister a previously registered  callback.
 */
function yRegisterDeviceRemovalCallback($func_removalCallback)
{
    return YAPI::RegisterDeviceRemovalCallback($func_removalCallback);
}

// Register a new value calibration handler for a given calibration type
//
function yRegisterCalibrationHandler($int_calibrationType, $func_calibrationHandler)
{
    return YAPI::RegisterCalibrationHandler($int_calibrationType, $func_calibrationHandler);
}

// Standard value calibration handler (n-point linear error correction)
//
function yLinearCalibrationHandler($int_calibType, $float_rawValue, $arr_calibParams,
                                   $arr_calibRawValues, $arr_calibRefValues)
{
    return YAPI::LinearCalibrationHandler($int_calibType, $float_rawValue, $arr_calibParams,
                                          $arr_calibRawValues, $arr_calibRefValues);
}

for($yHdlrIdx = 1; $yHdlrIdx <= 20; $yHdlrIdx++) {
    yRegisterCalibrationHandler($yHdlrIdx, 'yLinearCalibrationHandler');
}
        
//--- (generated code: Module functions)

/**
 * Allows you to find a module from its serial number or from its logical name.
 * 
 * This function does not require that the module is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method YModule.isOnline() to test if the module is
 * indeed online at a given time. In case of ambiguity when looking for
 * a module by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 * 
 * @param func : a string containing either the serial number or
 *         the logical name of the desired module
 * 
 * @return a YModule object allowing you to drive the module
 *         or get additional information on the module.
 */
function yFindModule($str_func)
{
    return YModule::FindModule($str_func);
}

/**
 * Starts the enumeration of modules currently accessible.
 * Use the method YModule.nextModule() to iterate on the
 * next modules.
 * 
 * @return a pointer to a YModule object, corresponding to
 *         the first module currently online, or a null pointer
 *         if there are none.
 */
function yFirstModule()
{
    return YModule::FirstModule();
}

//--- (end of generated code: Module functions)


?>
