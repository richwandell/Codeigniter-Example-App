<?php

class MY_Controller extends CI_Controller
{
    protected $referrer;

    public function __construct()
    {
        $this->referrer = @substr(parse_url($_SERVER["HTTP_REFERER"], PHP_URL_PATH), 1);
        parent::__construct();
        $this->setNoCache();
    }

    /**
     * Sets the browser no cache header so that we don't get browser cached pages
     * We don't want that for this type of form.
     */
    protected function setNoCache()
    {
        $this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
}