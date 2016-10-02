<?php

/**
 * Class MY_Output
 *
 * This output sets and retrieves full page cache in redis instead of files. 
 */
class MY_Output extends CI_Output 
{
    public function _write_cache($output)
    {
        $CI =& get_instance();
        $path = $CI->config->item('cache_path');
        $cache_path = ($path === '') ? APPPATH.'cache/' : $path;
        $uri = $CI->config->item('base_url')
            .$CI->config->item('index_page')
            .$CI->uri->uri_string();
        if ($CI->config->item('cache_query_string') && ! empty($_SERVER['QUERY_STRING'])){
            $uri .= '?'.$_SERVER['QUERY_STRING'];
        }
        $cache_path .= md5($uri);
        $redis = new Redis();
        $host = $CI->config->item("redis_host");
        $port = $CI->config->item("redis_port");
        $redis->connect($host, $port);
        if(!$redis->ping()){
            log_message('error', "Unable to ping to redis $host:$port");
            return false;
        }
        if ($this->_compress_output === TRUE){
            $output = gzencode($output);
            if ($this->get_header('content-type') === NULL){
                $this->set_content_type($this->mime_type);
            }
        }
        $expire = time() + $this->cache_expiration;
        $cache_info = serialize(array(
            'last_modified' => time(),
            'expire' => $expire,
            'headers' => $this->headers
        ));
        $output = $cache_info.'ENDCI--->'.$output;
        try {
            $redis->set($cache_path, $output);
            $redis->expire($cache_path, $this->cache_expiration);
            $this->set_cache_header($_SERVER['REQUEST_TIME'], $expire);
        }catch(RedisException $e){
            log_message('error', "Unable to set cache key");
            return false;
        }
    }
    
    public function _display_cache(&$CFG, &$URI)
    {
        $cache_path = ($CFG->item('cache_path') === '') ? APPPATH.'cache/' : $CFG->item('cache_path');
        $uri = $CFG->item('base_url').$CFG->item('index_page').$URI->uri_string;
        if ($CFG->item('cache_query_string') && ! empty($_SERVER['QUERY_STRING'])){
            $uri .= '?'.$_SERVER['QUERY_STRING'];
        }
        $filepath = $cache_path.md5($uri);
        $redis = new Redis();
        $host = $CFG->item("redis_host");
        $port = $CFG->item("redis_port");
        $redis->connect($host, $port);
        if($redis->exists($filepath)){
            $cache = $redis->get($filepath);
            if ( ! preg_match('/^(.*)ENDCI--->/', $cache, $match)){
                return false;
            }
        }else{
            return false;
        }
        $cache_info = unserialize($match[1]);
        $expire = $cache_info['expire'];
        $last_modified = $cache_info["last_modified"];
        $this->set_cache_header($last_modified, $expire);
        foreach ($cache_info['headers'] as $header){
            $this->set_header($header[0], $header[1]);
        }
        $this->_display(substr($cache, strlen($match[0])));
        log_message('debug', 'Cache is current. Sending it to browser.');
        return TRUE;
    }
    
    public function delete_cache($uri = '')
    {
        $CI =& get_instance();
        $cache_path = $CI->config->item('cache_path');
        if ($cache_path === ''){
            $cache_path = APPPATH.'cache/';
        }
        if (empty($uri)){
            $uri = $CI->uri->uri_string();
            if ($CI->config->item('cache_query_string') && ! empty($_SERVER['QUERY_STRING'])){
                $uri .= '?'.$_SERVER['QUERY_STRING'];
            }
        }
        $cache_path .= md5($CI->config->item('base_url').$CI->config->item('index_page').$uri);
        $redis = new Redis();
        $host = $CI->config->item("redis_host");
        $port = $CI->config->item("redis_port");
        $redis->connect($host, $port);
        try{
            $redis->del($cache_path);
        }catch(RedisException $e){
            log_message('error', "Unable to delete redis cached item $uri");
            return false;
        }
        return TRUE;
    }
}