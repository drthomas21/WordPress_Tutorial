<?php
$RestfulService = new \MU_Plugins\Rest_Service\V1\RestfulService();
add_action( 'rest_api_init', function() use ($RestfulService) {
    $RestfulService->register_routes();
});

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
}
//$_SERVER['HTTPS'] = 'on';