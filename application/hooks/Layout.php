<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layout{

    function doLayout(){

        global $OUT;

        $CI =& get_instance();

        $output = $CI->output->get_output();
        $CI->yield = isset($CI->yield) ? $CI->yield : TRUE;
        $CI->layout = isset($CI->layout) ? $CI->layout : 'layout-default';

        /****************************************************************
        * 레이아웃 설정
        ****************************************************************/
        if ($CI->yield === TRUE) {
            if (!preg_match('/(.+).php$/', $CI->layout)) {
                $CI->layout .= '.php';
            }
            $requested = APPPATH . 'views/_layout/' . $CI->layout;
            $layout = $CI->load->file($requested, true);
            $view = str_replace("{yield}", $output, $layout);
        } else {
            $view = $output;
        }
        $OUT->_display($view);
    }
}