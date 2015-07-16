<?php

class CacheInvalidator{
  static function delete_cache($uri_string=null)
  {
    $CI =& get_instance();
    $path = $CI->config->item('cache_path');
    $path = rtrim($path, DIRECTORY_SEPARATOR);

    $cache_path = ($path == '') ? APPPATH.'cache/' : $path;

    $uri =  $CI->config->item('base_url').
      $CI->config->item('index_page').
      $uri_string;

    if(file_exists($cache_path . md5($uri)))
      unlink($cache_path . md5($uri));
    if(file_exists($cache_path . md5($uri_string)))
      unlink($cache_path . md5($uri_string));
  }
}