<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('set_permalink')){
	setlocale(LC_ALL, 'en_US.UTF8');
        function toAscii($str) {
			$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
			$clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '-', $clean);
			$clean = strtolower(trim($clean, '-'));
			$clean = preg_replace("/[\/_| -]+/", '-', $clean);
			return $clean;
    	}
}
