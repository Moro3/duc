<?php

class MY_Input extends CI_Input
{

       function __construct()
       {
          parent::__construct();
       }

     /**
	* Sanitize Globals
	*
	* This function does the following:
	*
	* Unsets $_GET data (if query strings are not enabled)
	*
	* Unsets all globals if register_globals is enabled
	*
	* Standardizes newline characters to \n
	*
	* @access	private
	* @return	void
	*/
	function _sanitize_globals()
	{
		// Would kind of be "wrong" to unset any of these GLOBALS
		$protected = array('_SERVER', '_GET', '_POST', '_FILES', '_REQUEST', '_SESSION', '_ENV', 'GLOBALS', 'HTTP_RAW_POST_DATA',
							'system_folder', 'application_folder', 'BM', 'EXT', 'CFG', 'URI', 'RTR', 'OUT', 'IN');

		// Unset globals for security.
		// This is effectively the same as register_globals = off
		foreach (array($_GET, $_POST, $_COOKIE, $_SERVER, $_FILES, $_ENV, (isset($_SESSION) && is_array($_SESSION)) ? $_SESSION : array()) as $global)
		{
			if ( ! is_array($global))
			{
				if ( ! in_array($global, $protected))
				{
					unset($GLOBALS[$global]);
				}
			}
			else
			{
				foreach ($global as $key => $val)
				{
					if ( ! in_array($key, $protected))
					{
						unset($GLOBALS[$key]);
					}

					if (is_array($val))
					{
						foreach($val as $k => $v)
						{
							if ( ! in_array($k, $protected))
							{
								unset($GLOBALS[$k]);
							}
						}
					}
				}
			}
		}

		// Is $_GET data allowed? If not we'll set the $_GET to an empty array
		if ($this->_allow_get_array == FALSE)
		{
			$_GET = array();
		}
		else
		{


            if (is_array($_GET) AND count($_GET) > 0)
            {
                foreach($_GET as $key => $val)
                {
                    $_GET[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
                }
            }

		}

		// Clean $_POST Data
		$_POST = $this->_clean_input_data($_POST);

		// Clean $_COOKIE Data
		// Also get rid of specially treated cookies that might be set by a server
		// or silly application, that are of no use to a CI application anyway
		// but that when present will trip our 'Disallowed Key Characters' alarm
		// http://www.ietf.org/rfc/rfc2109.txt
		// note that the key names below are single quoted strings, and are not PHP variables
		unset($_COOKIE['$Version']);
		unset($_COOKIE['$Path']);
		unset($_COOKIE['$Domain']);
		$_COOKIE = $this->_clean_input_data($_COOKIE);

		log_message('debug', "Global POST and COOKIE data sanitized");
	}

} 

