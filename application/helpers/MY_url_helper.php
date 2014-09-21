<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//���������� ��������� url_helper
// ------------------------------------------------------------------------

/**
 * Uri replace
 * ������ ����������� �������� � ������ uri ��� ���������� �������

 * @access	public
 * @param: $uri    - ������ �������
 *         $replace - ���������� ��������
 *                ���� �������� ���������, �� ������
 *                ������� ���������� � ������� ������ ��������������� ������� ���������� �������� � ���������������� �����
 *         $name    - ��� ������� �������, ���� �� �������, �� �������.
 *                !!! ������������� ������ ��������� ��� �������,
 *                    �.�. ������� ������ ����� ������� � ����������������� �����������
 * @return	string
 */
if ( ! function_exists('uri_replace'))
{
	function uri_replace($uri, $replace, $name = '')
	{
            $CI =& get_instance();
            //return $CI->load->library('control_uri')->guri($name)->uri_replace($uri, $replace, $name);
            return $CI->load->library('uri_generation')->uri_replace($uri, $replace, $name);
	}
}
