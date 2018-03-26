<?php
namespace MU_Plugins\Rest_Service\V1\Ext;

interface BaseRestfulEndpoint {
    public function registerEndpoints(string $namespace);
}
