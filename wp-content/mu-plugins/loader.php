<?php
$RestfulService = new \MU_Plugins\Rest_Service\V1\RestfulService();
add_action( 'rest_api_init', function() use ($RestfulService) {
    $RestfulService->register_routes();
});
