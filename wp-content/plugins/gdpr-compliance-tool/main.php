<?php
/**
* Plugin Name: GDPR Compliance Tool
* Description: tool used to bind the new compliance tool from WP to all users
* Version: 0.2a
* Author: dathomas
* Author URI: http://github.com/drthomas21/
**/

namespace GDPR_Compliance_Tool {
    $AJAX_ACTION = "gdpr-compliance";
    function displayPrivacyRequests(\WP_User $User) {
        global $AJAX_ACTION;
        include_once __DIR__.'/templates/privacy-request.tpl';
    }

    \add_action("admin_init",function() {
        \add_action("show_user_profile",__NAMESPACE__ . "\\displayPrivacyRequests");
        \add_action("edit_user_profile",__NAMESPACE__ . "\\displayPrivacyRequests");
    });

    \add_action("wp_ajax_{$AJAX_ACTION}",function() {
        $type = array_key_exists('type',$_POST) ? intval($_POST['type']) : 0;
        $user_id = array_key_exists('user_id',$_POST) ? intval($_POST['user_id']) : 0;
        $action_type = "";
        $code = 200;
        $ret = [
            "success" => false,
            "message" => ""
        ];

        if($user_id > 0) {
            $User = \get_user_by("id",$user_id);
        } else {
            $type = 0;
        }

        if($type == 1) {
            $action_type = "export_personal_data";
        } else if($type == 2){
            $action_type = "remove_personal_data";
        }

        if(strlen($action_type) > 0) {
            $request_id = wp_create_user_request( $User->data->user_email, $action_type );

            if(is_wp_error($request_id)) {
                $ret['message'] = $request_id->get_error_message();
                $ret['success'] = false;
            } else {
                $ret['message'] = "Successfully created request";
                $ret['success'] = true;
            }
        } else {
            $code = 405;
            $ret['message'] = "Invalid Request";
            $ret['success'] = false;
        }

        http_response_code($code);
        wp_send_json($ret);
    });
}
