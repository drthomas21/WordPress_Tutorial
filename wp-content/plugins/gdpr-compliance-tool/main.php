<?php
/**
* Plugin Name: GDPR Compliance Tool
* Description: tool used to bind the new compliance tool from WP to all users
* Version: 0.1a
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
        //var_dump(function_exists("GDPR_Compliance_Tool\\displayPrivacyRequests")); exit;
        \add_action("show_user_profile",__NAMESPACE__ . "\\displayPrivacyRequests");
        \add_action("edit_user_profile",__NAMESPACE__ . "\\displayPrivacyRequests");
    });

    \add_action("wp_ajax_{$AJAX_ACTION}",function() {
        $User = wp_get_current_user();
        $type = array_key_exists('type',$_POST) ? intval($_POST['type']) : 0;
        $code = $type == 0 ? 405 : 200;
        $ret = [
            "success" => $type != 0,
            "message" => $type == 0 ? "Invalid Request" : ""
        ];

        $action_type = "";
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
        }

        wp_send_json($ret);
    });
}
