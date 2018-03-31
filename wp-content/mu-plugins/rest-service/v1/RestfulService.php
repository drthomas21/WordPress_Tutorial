<?php
namespace MU_Plugins\Rest_Service\V1;

class RestfulService extends \WP_REST_Controller{
    private $PostEndpoint = null;
    private $VideoEndpoint = null;

    public function __construct() {
        $this->namespace = '/data/v1';

        $this->PostEndpoint = new Ext\PostEndpoint();
        $this->VideoEndpoint = new Ext\YoutubeEndpoint();
        $this->TermEndpoint = new Ext\TermEndpoint();
    }

    public function register_routes() {
        $this->PostEndpoint->registerEndpoints($this->namespace);
        $this->VideoEndpoint->registerEndpoints($this->namespace);
        $this->TermEndpoint->registerEndpoints($this->namespace);
    }
}
