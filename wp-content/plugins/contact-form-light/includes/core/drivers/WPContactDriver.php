<?php
namespace Contact_Form_Light\Core\Drivers;

class WPContactDriver {
    const ACTION_SEND_MAIL = "contact_send_mail";
    const ACTION_FORM_TOP = "contact_form_top";
    const ACTION_FORM_BOTTOM = "contact_form_bottom";
    const FILTER_PROCESS_DATA = "contact_process_data";
    const FILTER_VALIDATE_DATA = "contact_validate_data";
    const SHORTCODE = "contact_form_light";
    const ERROR_FAILED_RECAPTCHA = "You need to pass Google's ReCAPTCHA";
    const ERROR_INVALID_EMAIL = "You need to provide a valid email";
    const ERROR_EMPTY_BODY = "You need to provide a message";

    protected static $Instance = null;
    protected static $assetJsDefault = [
        "src" => "",
        "ver" => "",
        "deps" => [],
        "footer" => true
    ];
    protected static $assetsCssDefault = [
        "src" => "",
        "deps" => [],
        "ver" => "",
        "media" => "all"
    ];

    protected $options = [];

    protected $jsAssets = [];

    protected $cssAssets = [];

    public static function getInstance(): self {
        if(self::$Instance == null) {
            self::$Instance = new static();
        }

        return self::$Instance;
    }

    protected function __construct() {
        $this->jsAssets = [
            "jquery" => [
                "src" => "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js",
                "ver" => "3.2.1"
            ],
            "angularjs" => [
                "src" => "https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.js",
                "ver" => "1.6.4",
                "deps" => ['jquery']
            ],
            "angularjs-sanitize" => [
                "src" => "https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-sanitize.js",
                "ver" => "1.6.4",
                "deps" => ['angularjs']
            ],
            "bootstrap" => [
                "src" => "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js",
                "ver" => "3.3.7",
                "deps" => ['jquery']
            ],
            "contact-main" => [
                "src" => plugins_url("/assets/js/main.js",CONTACT_FORM_LIGHT_DIR.'/index.php'),
                "ver" => "3.3.7",
                "deps" => ['jquery','angularjs','bootstrap']
            ]
        ];

        add_action("wp_ajax_".self::ACTION_SEND_MAIL,function() {
            $this->actionAjaxCallback();
        });

        add_action("wp_ajax_no_priv".self::ACTION_SEND_MAIL,function() {
            $this->actionAjaxCallback();
        });

        add_shortcode(self::SHORTCODE,function() {
            return $this->shortcodeCallback();
        });

        add_action("wp_enqueue_scripts",function() {
            $this->enqueueAssetsCallback();
        },99);

        $this->options = get_option(self::SHORTCODE,[]);
    }

    protected function enqueueAssetsCallback() {
        global $post;
        if(!$post || stripos($post->post_content,"[".self::SHORTCODE."]") === false) {
            return;
        }

        foreach($this->jsAssets as $handle => $asset) {
            if(!wp_script_is($handle,"registered")) {
                $asset = array_merge(self::$assetJsDefault,$asset);
                wp_enqueue_script($handle,$asset['src'],$asset['deps'],$asset['ver'],$asset['footer']);
            }
        }

        foreach($this->cssAssets as $handle => $asset) {
            if(!wp_style_is($handle,"done") && !wp_style_is($handle,"to_do") && !wp_style_is($handle,"queue")) {
                $asset = array_merge(self::$assetsCssDefault,$asset);
                wp_enqueue_style($handle,$asset['src'],$asset['deps'],$asset['ver'],$asset['media']);
            }
        }
    }

    protected function shortcodeCallback(array $attr = []): string {
        ob_start();
        include_once(CONTACT_FORM_LIGHT_DIR.'/templates/contactForm.php');
        $content = ob_get_clean();
        return $content;
    }

    protected function actionAjaxCallback() {
        ignore_user_abort(true);

        $errors = [];
        $Record = new \Contact_Form_Light\Core\Records\EmailRecord();
        $fp = fopen("php://input","r");
        $raw = fgets($fp);
        fclose($fp);
        $data = json_decode($raw);


        $Record->ip_address = $_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
        $filtered = apply_filters(self::FILTER_PROCESS_DATA,$data,$Record->ip_address);
        if(is_object($filtered)) {
            foreach(get_object_vars($filtered) as $prop => $value) {
                $data->$prop = $value;
            }
        }

        if($data) {
            $Record->from = $data->from ? trim($data->from) : "";
            $Record->subject = $data->subject ? trim($data->subject) : "-- no subject --";
            $Record->body = $data->body ? trim($data->body) : "";
        }

        //Validate Values
        $errors = array_merge($errors,apply_filters(self::FILTER_VALIDATE_DATA,[],$Record));
        if(!filter_var($Record->from,FILTER_VALIDATE_EMAIL)) {
            $errors[] = self::ERROR_INVALID_EMAIL;
        }

        if(strlen($Record->body) == 0) {
            $errors[] = self::ERROR_EMPTY_BODY;
        }

        if(!empty($errors)) {
            wp_send_json_error($errors,200);
        } else {
            ob_start();
            if(session_id()) session_write_close();
            echo json_encode(["success"=>true,"messages"=>["Message has been sent"]]);
            header('Content-Type: application/json');
            header('Content-Encoding: none');
            header('Connection: close');
            header('Content-Length: ' . ob_get_length());
            http_response_code(200);
            ob_end_flush();
            ob_flush();
            flush();
            if (is_callable('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }

            sleep(10);

            $success = \Contact_Form_Light\Core\Models\EmailModel::insertEmail($Record);
            if(!$success) {
                error_log("Failed to insert into the database");
            }

            wp_mail(get_bloginfo('admin_email'),$Record->subject,$Record->body,["Reply-To: <{$Record->from}>"]);
            wp_die();
        }
    }

    public function activation() {
        \Contact_Form_Light\Core\Models\EmailModel::createTable();
    }

    public function getSetting(string $name) {
        return array_key_exists($name,$this->options) ? $this->options[$name] : false;
    }

    public function setSetting(string $name,$value) {
        $this->options[$name] = $value;
        update_option(self::SHORTCODE,$this->options);
    }
}
