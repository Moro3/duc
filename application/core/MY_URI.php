<?php
/*
 | class фиксит проблему с отсутствием переменной окружения PATH_INFO
 | в результате чего не верно интерпретируется строка URI
*/
class MY_URI extends CI_URI{
  /**
	 * Get the URI String
	 *
	 * @access	private
	 * @return	string
	 */
	function _fetch_uri_string()
	{
		if (strtoupper($this->config->item('uri_protocol')) == 'AUTO')
		{
			// If the URL has a question mark then it's simplest to just
			// build the URI string from the zero index of the $_GET array.
			// This avoids having to deal with $_SERVER variables, which
			// can be unreliable in some environments
			if (is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '')
			{
				$this->uri_string = key($_GET);
				return;
			}

			// Is there a PATH_INFO variable?
			// Note: some servers seem to have trouble with getenv() so we'll test it two ways
			$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
			if (trim($path, '/') != '' && $path != "/".SELF)
			{
				$this->uri_string = $path;
				return;
			}

			// No PATH_INFO?... What about QUERY_STRING?
			$path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
			if (trim($path, '/') != '')
			{
				$this->uri_string = $path;
				return;
			}

			// No QUERY_STRING?... Maybe the ORIG_PATH_INFO variable exists?
			$path = (isset($_SERVER['ORIG_PATH_INFO'])) ? $_SERVER['ORIG_PATH_INFO'] : @getenv('ORIG_PATH_INFO');
			if (trim($path, '/') != '' && $path != "/".SELF)
			{
				// remove path and script information so we have good URI data
				$this->uri_string = str_replace($_SERVER['SCRIPT_NAME'], '', $path);
				return;
			}

			// We've exhausted all our options...
			$this->uri_string = '';
		}
		else
		{
			$uri = strtoupper($this->config->item('uri_protocol'));

			if ($uri == 'REQUEST_URI')
			{
				$this->uri_string = $this->_parse_request_uri();
				return;
			}

      elseif($uri == 'PATH_INFO'){
        if(@getenv($uri)){
          $this->uri_string = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
        }else{
          $this->uri_string = $this->_parse_pathinfo_uri();
        }
				return;
			}

			$this->uri_string = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
		}

		// If the URI contains only a slash we'll kill it
		if ($this->uri_string == '/')
		{
			$this->uri_string = '';
		}
	}

	function _parse_pathinfo_uri(){
	  //$request = ltrim($_SERVER['PHP_SELF'],'/');
	  //$script_name = ltrim($_SERVER['SCRIPT_NAME'],'/');
    //$path = ltrim($request,$script_name);
    //$path = str_replace($script_name, '', $request);
    $path_arr = parse_url($_SERVER['REQUEST_URI']);

    if(!empty($path_arr['path']) && $path_arr['path'] != '/'){
      //echo $path_arr['path'];
      return $path_arr['path'];
    }else{
      return '';
    }
	}

	function _filter_uri($str)
	{
		if ($str != '' && $this->config->item('permitted_uri_chars') != '' && $this->config->item('enable_query_strings') == FALSE)
		{
			// preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
			// compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
			if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($this->config->item('permitted_uri_chars'), '-'))."]+$|i", $str))
			//if ( ! preg_match("|^[".preg_quote($this->config->item('permitted_uri_chars'))."]+$|i", rawurlencode($str)))
			//if ( ! preg_match("|^[".preg_quote($this->config->item('permitted_uri_chars'))."]+$|iu", $str))
			{
				show_error('The URI you submitted has disallowed characters.', 400);
			}
		}

		// Convert programatic characters to entities
		$bad	= array('$',		'(',		')',		'%28',		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

		return str_replace($bad, $good, $str);
	}
}

