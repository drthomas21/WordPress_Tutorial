<?php
namespace Contact_Form_Light\Modules\Recaptcha;
class RecaptchaModule implements \Contact_Form_Light\Modules\BaseModule {
    const OPTION_SITE_KEY       = "recaptcha_site_key";
    const OPTION_SECRET_KEY     = "recaptcha_secret_key";

    private $apiUrl     = "https://www.google.com/recaptcha/api/siteverify";
    private $jsLibrary  = "https://www.google.com/recaptcha/api.js";
    private $siteKey    = "";
    private $secretKey  = "";
    private $response   = "";

    public function __construct() {
        add_filter(\Contact_Form_Light\Core\Drivers\WPContactDriver::FILTER_PROCESS_DATA,function(\stdClass $data) {
            $this->response = $data->recaptcha;

            return $data;
        });

        add_filter(\Contact_Form_Light\Core\Drivers\WPContactDriver::FILTER_VALIDATE_DATA,function(array $errors, \Contact_Form_Light\Core\Records\EmailRecord $Record) {
            return $this->validateRecaptcha($errors,$Record);
        },10,2);

        add_action("wp_enqueue_scripts",function() {
            $this->enqueueAssetsCallback();
        },99);

        add_action(\Contact_Form_Light\Core\Drivers\WPContactDriver::ACTION_FORM_BOTTOM,function() {
            $this->display();
        });

        add_action(\Contact_Form_Light\Core\Controllers\AdminController::ACTION_ADMIN_TOP,function() {
            $this->adminDisplay();
        });

        $this->siteKey = \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->getSetting(self::OPTION_SITE_KEY);
        $this->secretKey = \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->getSetting(self::OPTION_SECRET_KEY);
    }

    protected function enqueueAssetsCallback() {
        global $post;
        if(!$post || stripos($post->post_content,"[".\Contact_Form_Light\Core\Drivers\WPContactDriver::SHORTCODE."]") === false) {
            return;
        }

        wp_enqueue_script("recaptcha",$this->jsLibrary,[],"",true);
    }

    protected function validateRecaptcha(array $errors, \Contact_Form_Light\Core\Records\EmailRecord $Record): array {
        if(empty($this->response)) {
            $errors[] = "Please pass the reCAPTCHA";
        } else {
            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL,$this->apiUrl);
            curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query ([
                "secret"    => $this->secretKey,
                "response"  => $this->response,
                //"remoteip"  => $Record->ip_address
            ]));

            $ret = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($ret);
            if(!$data || !is_object($data) || !$data->success) {
                $errors[] = "Failed reCAPTCHA, please refresh the page and try again";
            }
        }

        return $errors;
    }

    protected function display() {
        echo '<div class="g-recaptcha" data-sitekey="'.$this->siteKey.'"></div>';
    }

    protected function adminDisplay() {
        if(isset($_POST[self::OPTION_SITE_KEY])) {
            $this->siteKey = $_POST[self::OPTION_SITE_KEY];
            \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->setSetting(self::OPTION_SITE_KEY,$this->siteKey);
        }

        if(isset($_POST[self::OPTION_SECRET_KEY])) {
            $this->secretKey = $_POST[self::OPTION_SECRET_KEY];
            \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->setSetting(self::OPTION_SECRET_KEY,$this->secretKey);
        }
        ?>
        <style>
            .form-group label {
                width: 100%;
                display:block;
            }
            .form-group input {
                width: 100%;
                max-width: 400px;
            }
        </style>
        <form method="POST">
            <div class="form-group">
                <label for="site-key" class="form-control">Site Key</label>
                <input name="<?= self::OPTION_SITE_KEY; ?>" id="site-key" value="<?= $this->siteKey; ?>" class="form-group regular-text code" />
            </div>
            <div class="form-group">
                <label for="secret-key" class="form-control">Secret Key</label>
                <input name="<?= self::OPTION_SECRET_KEY; ?>" id="secret-key" value="<?= $this->secretKey; ?>" class="form-group regular-text code" />
            </div>
            <br />
            <input type="submit" value="Update" class="button button-primary"/>
        </form>
        <br />
        <?php
    }
}
