<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Расширение помощника form_helper
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------

/**
 * Text Input Field
 *
 * @access	public
 * @param	string
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('gform_input'))
{
	function gform_input($name_form, $data = '', $value = '', $extra = '')
	{
		$ci =& get_instance();
                $ci->load->library('gform');
                if(is_object($ci->gform->html($name_form))){
                    return $ci->gform->html($name_form)->_get_form_input($data, $value, $extra);
                }
                return false;                
	}
}

// ------------------------------------------------------------------------

/**
 * Text Input Field
 *
 * @access	public
 * @param	string
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('gform_select'))
{
	function gform_select($name_form, $data = '', $options = array(), $selected = '', $extra = '')
	{
		$ci =& get_instance();
                $ci->load->library('gform');
                if(is_object($ci->gform->html($name_form))){
                    return $ci->gform->html($name_form)->_get_form_select($data, $options, $selected, $extra);
                }
                return false;                
	}
}

// ------------------------------------------------------------------------

/**
 * Text Input Field
 *
 * @access	public
 * @param	string
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('gform_form'))
{
	function gform_form($name_form, $data = '', $multi = array(), $css = '', $js = '')
	{
		$ci =& get_instance();
                $ci->load->library('gform');
                if(is_object($ci->gform->html($name_form))){
                    return $ci->gform->html($name_form)->get_form($data, $multi, $css, $js);
                }
                return false;                
	}
}