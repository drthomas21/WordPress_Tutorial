<?php
namespace Contact_Form_Light\Modules\Recaptcha;
class RecaptchaModule implements \Contact_Form_Light\Modules\BaseModule {
    const OPTION_RECAPTCHA_SITE_KEY     = "recaptcha_site_key";
    const OPTION_RECAPTCHA_SECRET_KEY   = "recaptcha_secret_key";
    const OPTION_RECAPTCHA_ENABLED      = "recaptcha_enabled";

    private $apiUrl     = "https://www.google.com/recaptcha/api/siteverify";
    private $jsLibrary  = "https://www.google.com/recaptcha/api.js";
    private $enabled    = false;
    private $siteKey    = "";
    private $secretKey  = "";
    private $response   = "";

    public function __construct() {
        $this->siteKey = \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->getSetting(self::OPTION_RECAPTCHA_SITE_KEY);
        $this->secretKey = \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->getSetting(self::OPTION_RECAPTCHA_SECRET_KEY);
        $this->enabled = \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->getSetting(self::OPTION_RECAPTCHA_ENABLED);

        add_action("wp_enqueue_scripts",function() {
            $this->enqueueAssetsCallback();
        },99);

        add_action(\Contact_Form_Light\Core\Controllers\AdminController::ACTION_ADMIN_TOP,function() {
            $this->adminDisplay();
        });

        if($this->enabled) {
            add_filter(\Contact_Form_Light\Core\Drivers\WPContactDriver::FILTER_PROCESS_DATA,function(\stdClass $data) {
                $this->response = $data->recaptcha;

                return $data;
            });

            add_filter(\Contact_Form_Light\Core\Drivers\WPContactDriver::FILTER_VALIDATE_DATA,function(array $errors, \Contact_Form_Light\Core\Records\EmailRecord $Record) {
                return $this->validateRecaptcha($errors,$Record);
            },10,2);

            add_action(\Contact_Form_Light\Core\Drivers\WPContactDriver::ACTION_FORM_BOTTOM,function() {
                $this->display();
            });
        }
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
        $nonce = "_wpnonce";

        if(isset($_POST[$nonce]) && wp_verify_nonce($_POST[$nonce],__CLASS__) === 1) {
            if(isset($_POST[self::OPTION_RECAPTCHA_ENABLED])) {
                $this->enabled = true;
            } else {
                $this->enabled = false;
            }
            \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->setSetting(self::OPTION_RECAPTCHA_ENABLED,$this->enabled);

            if(isset($_POST[self::OPTION_RECAPTCHA_SITE_KEY])) {
                $this->siteKey = $_POST[self::OPTION_RECAPTCHA_SITE_KEY];
                \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->setSetting(self::OPTION_RECAPTCHA_SITE_KEY,$this->siteKey);
            }

            if(isset($_POST[self::OPTION_RECAPTCHA_SECRET_KEY])) {
                $this->secretKey = $_POST[self::OPTION_RECAPTCHA_SECRET_KEY];
                \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->setSetting(self::OPTION_RECAPTCHA_SECRET_KEY,$this->secretKey);
            }
        }

        ?>
        <style>
            .form-group label {
                width: 100%;
                display:block;
            }
            .form-group input {
                display:block;
            }
            .form-group input["type='text'"] {
                width: 100%;
                max-width: 400px;
            }
        </style>
        <form method="POST">
            <h2>Google reCAPTCHA</h2>
            <div class="form-group-inline">
                <label for="enable-recaptcha" class="form-control">Enable reCAPTCHA</label>
                <input type='checkbox' name="<?= self::OPTION_RECAPTCHA_ENABLED; ?>" id="enable-recaptcha" value="1" class="form-group" <?= ($this->enabled ? "checked='checked'" : ""); ?>/>
            </div>
            <div class="form-group">
                <label for="site-key" class="form-control">Site Key</label>
                <input type='text' name="<?= self::OPTION_RECAPTCHA_SITE_KEY; ?>" id="site-key" value="<?= $this->siteKey; ?>" class="form-group regular-text code" />
            </div>
            <div class="form-group">
                <label for="secret-key" class="form-control">Secret Key</label>
                <input type='text' name="<?= self::OPTION_RECAPTCHA_SECRET_KEY; ?>" id="secret-key" value="<?= $this->secretKey; ?>" class="form-group regular-text code" />
            </div>
            <br />
            <?php wp_nonce_field( __CLASS__, $nonce,false, true ); ?>
            <input type="submit" value="Update" class="button button-primary"/>
        </form>
        <br />
        <?php
    }
}
