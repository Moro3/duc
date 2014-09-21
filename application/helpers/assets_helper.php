<?php


/**
 *  Helpers assets materials
 *  css, js, img
 *
 *
 */


function asset_img($file, $module = FALSE, $absolute = FALSE){
    $CI = &get_instance();
    $CI->load->config('assets');
    $path = $CI->config->item('assets', 'path_img');
    if(empty($module)){
       $module = CI::$APP->router->fetch_module().'/';
    }
    return $path.$module.$file;
}


function assets_file($file, $type, $module = '', $config = array(), $return = true){
    $CI = &get_instance();
    return $CI->assets->_file($file, $module, $type, $config, $return);
}

function assets_img($file, $module = '', $config = array(), $return = true){
    $CI = &get_instance();
    return $CI->assets->img->file($file, $module, $config, $return);
}

function assets_style($file, $module = '', $config = array(), $return = true){
    $CI = &get_instance();
    return $CI->assets->style->load($file, $module, $config, $return);
}

function assets_script($file, $module = '', $config = array(), $return = true){
    $CI = &get_instance();
    return $CI->assets->script->load($file, $module, $config, $return);
}

function assets_mixed($file, $module = '', $config = array(), $return = true){
    $CI = &get_instance();
    return $CI->assets->mixed->load($file, $module, $config, $return);
}

function assets_script_out($file = false, $module = '', $config = array(), $return = false){
    $CI = &get_instance();
    $res = $CI->assets->script->out($file, $module, $config, $return);
    $res .= $CI->assets->mixed->out_script($file, $module, $config, $return);
    return $res;
}

function assets_style_out($file = false, $module = '', $config = array(), $return = false){
    $CI = &get_instance();
    $res = $CI->assets->style->out($file, $module, $config, $return);
    $res .= $CI->assets->mixed->out_style($file, $module, $config, $return);
    return $res;
}

function assets_path($module = null){
    $CI = &get_instance();
    return $CI->assets->get_assets_path($module);
}

function assets_path_source($module = false, $type = false){
    $CI = &get_instance();
    return $CI->assets->get_assets_path_source($module, $type);
}

function assets_uploads($file = false){
    $CI = &get_instance();
    return $CI->assets->get_assets_uploads($file);
}

function assets_wysiwyg(){
    $CI = &get_instance();
    return $CI->assets->get_assets_wysiwyg();
}