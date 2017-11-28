<?php
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

class PaypalApi {
	const CALLBACK_PAGENAME = "paypal-complete";
	const CHECKOUT_PAGENAME = "checkout";
	const DOWNLOAD_PAGENAME = "downloads";
	const AJAX_ADD_TO_CHART = "paypal_add";
	const TABLNAME="paypal_orders";

	const STATUS_NOT_PAID = "not_paid";
	const STATUS_PAID = "paid";
	const STATUS_DOWNLOADED = "downloaded";

	const OPTIONS_SETTINGS_FIELD = "paypal-account-group";
	const OPTIONS_API_CLIENT_ID = "paypal_client_id";
	const OPTIONS_API_SECRET = "paypal_api_secret";
	const OPTIONS_API_SANDBOX_ACCOUNT = "paypal_sandbox_account";
	const OPTIONS_DATABASE_SETUP = "paypal_setup";
	const OPTIONS_PRICE = "paypal_price";

	private static $Instance = null;
	protected $clientId = "";
	protected $secrete = "";
	protected $sandboxAccount = "";
	protected $sandboxEndpoint = "api.sandbox.paypal.com";
	protected $callbackUrl = "";
	protected $price = 1.00;

	public static function getInstance() {
		if(self::$Instance == null) {
			self::$Instance = new self();
		}

		return self::$Instance;
	}

	protected static function getScripts() {
		return array(
				"jquery" => array(
						"url" => "https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js",
						"ver" => "2.1.4",
						"dep" => array(),
						"bottom" => true
				),
				"angular" => array(
						"url" => "https://ajax.googleapis.com/ajax/libs/angularjs/1.4.6/angular.js",
						"ver" => "1.4.6",
						"deps" => array('jquery'),
						"bottom" => true
				),
				"angular-sanitize" => array(
						"url" => "https://ajax.googleapis.com/ajax/libs/angularjs/1.4.6/angular-sanitize.min.js",
						"ver" => "1.4.6",
						"deps" => array('angular'),
						"bottom" => true
				),
				"angular-cookies" => array(
						"url" => "https://ajax.googleapis.com/ajax/libs/angularjs/1.4.6/angular-cookies.min.js",
						"ver" => "1.4.6",
						"dep" => array('angular'),
						"bottom" => true
				),
				"angular-app" => array(
						"url" => plugin_dir_url(__DIR__).'/assets/app.js',
						"ver" => "1.4.6",
						"dep" => array('angular','angular-cookies','angular-sanitize'),
						"bottom" => true
				),
				"bootstrap" => array(
						"url" => "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js",
						"ver" => "3.3.5",
						"deps" => array('jquery'),
						"bottom" => true
				)
		);
	}

	protected static function getStyles() {
		return array(
				"bootstrap" => array(
						"url" => "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css",
						"ver" => "3.3.5",
						"deps" => array(),
						"bottom" => false
				)
		);
	}

	protected static function validateUser(array &$userInfo) {
		foreach($userInfo as $key => &$value) {
			if(in_array($key,array("first_name","last_name","email_address","phone_number","state","city","address","zip"))) {
				if(empty($value) || !$value) {
					return false;
				}
			}

			if(in_array($key,array("first_name","last_name"))) {
				if(preg_match("/[^A-Za-z\- ]/",$value)) {
					return false;
				}
			}elseif($key == "email_address") {
				if(!filter_var($value,FILTER_VALIDATE_EMAIL)) {
					return false;
				}
			}elseif($key == "phone_number") {
				if(strlen(preg_replace("/[^0-9]/", "", $value)) != 10) {
					return false;
				}
				$value=preg_replace("/[^0-9]/", "", $value);
			}elseif($key == "state") {
				if(strlen(preg_replace("/[^A-Z]/", "", $value)) != 2) {
					return false;
				}
			}elseif($key == "city") {
				if(preg_match("/[^A-Za-z0-9\.\- ]/", $value)) {
					return false;
				}
			}elseif($key == "address") {
				if(preg_match("/[^A-Za-z0-9 \#\.\,\;\:\']/", $value)) {
					return false;
				}
			}elseif($key == "zip") {
				if(strlen(preg_replace("/[^0-9]/", "", $value)) != 5) {
					return false;
				}
			}
		}

		return true;
	}

	protected static function getItemNames($str) {
		global $wpdb;
		$items = $wpdb->get_col("SELECT concat(post_title,' #',ID) as item_name FROM {$wpdb->posts} WHERE ID IN ({$str})");
		return implode(", ",$items);
	}

	protected static function getOrderInfo($order) {
		return
		"{$order->address}<br />
		{$order->city}, {$order->state} {$order->zip}<br />
		{$order->email_address}<br />{$order->phone_number}";
	}

	protected static function getOrderActions($order) {
		if($order->status != self::STATUS_NOT_PAID) {
			//TODO: add some action later
			return "";
		}
		return "";
	}

	protected function __construct() {
		$this->clientId = get_option(self::OPTIONS_API_CLIENT_ID);
		$this->secrete = get_option(self::OPTIONS_API_SECRET);
		$this->sandboxAccount = get_option(self::OPTIONS_API_SANDBOX_ACCOUNT);
		$didSetup = get_option(self::OPTIONS_DATABASE_SETUP,false);
		$this->price = floatval(get_option(self::OPTIONS_PRICE,1.00));

		if(!$didSetup) {
			$this->doSetup();
		}

		add_action("parse_request",function(WP $Query) {
			if($Query->query_vars['name'] == self::CALLBACK_PAGENAME) {
				$this->validatePayment();
			}

			if($Query->query_vars['name'] == self::CHECKOUT_PAGENAME) {
				$this->checkoutItems();
			}

			if($Query->query_vars['name'] == self::DOWNLOAD_PAGENAME) {
				$this->downloadItems();
			}
		});

		add_action("admin_menu",function() {
			add_menu_page("Paypal Account","Paypal Account","manage_options","paypal-account",function() {
				$this->dispayAdminPage();
			}, plugin_dir_url(__DIR__)."/assets/paypal-icon.png",81);
		});

		add_action("admin_init",function() {
			register_setting(self::OPTIONS_SETTINGS_FIELD,self::OPTIONS_API_CLIENT_ID);
			register_setting(self::OPTIONS_SETTINGS_FIELD,self::OPTIONS_API_SECRET);
			register_setting(self::OPTIONS_SETTINGS_FIELD,self::OPTIONS_API_SANDBOX_ACCOUNT);
			register_setting(self::OPTIONS_SETTINGS_FIELD,self::OPTIONS_PRICE);
		});

		add_action("init",function() {
			foreach(self::getScripts() as $handle => $args) {
				wp_deregister_script($handle);
				wp_register_script($handle, $args['url'],$args['deps'],$args['ver'],$args['bottom']);
			}

			foreach(self::getStyles() as $handle => $args) {
				wp_deregister_style($handle);
				wp_register_style($handle, $args['url'],$args['deps'],$args['ver'],$args['bottom']);
			}
		},999);

		add_action("wp_enqueue_scripts",function() {
			foreach(self::getScripts() as $handle => $args) {
				wp_enqueue_script($handle);
			}

			foreach(self::getStyles() as $handle => $args) {
				wp_enqueue_style($handle);
			}

			wp_add_inline_script("angular-app", "var angularConfig = " . json_encode(array("price" => $this->price)));
		}, 999);

		add_filter("angular_app",function($app) {
			return "ng-app='paypal' ng-cloak";
		});

		add_action("paypal_navigation_bar",function($type = "") {
			if($type != "") {
				$type = "-{$type}";
			}

			$this->displayNavigationBar($type);
		});

		add_action("wp_ajax_paypal",function() {
			$search = $_GET['search'];
			if(empty($search)) {
				wp_send_json_error("there was nothing to search for");
				wp_die();
			}

			global $wpdb;
			$rets = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}".self::TABLNAME."
						 WHERE ID=%d OR INSTR(first_name,%s) > 0 OR INSTR(last_name,%s) > 0 OR
						 INSTR(phone_number,%s) > 0 OR state = %s OR INSTR(city,%s) > 0 OR
						 INSTR(address,%s) > 0 OR zip = %d OR INSTR(invoice_number,%s) > 0 OR
						 INSTR(email_address,%s) > 0 OR status = %s OR INSTR(order_date,%s) > 0",
						 array(intval($search),$search,$search,$search,$search,$search,$search,intval($search),$search,$search,$search,$search)
					)
			);
			if(!empty($rets)) {
				foreach($rets as $i=>$row) {
					$rets[$i]->items = self::getItemNames($row->items);
					$rets[$i]->info = self::getOrderInfo($row);
					$rets[$i]->actions = self::getOrderActions($row);
				}
			}
			wp_send_json_success($rets);
			wp_die();
		});
	}

	private function doSetup() {
		global $wpdb;
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}".self::TABLNAME."` (
			  `id` INT NOT NULL AUTO_INCREMENT,
			  `items` TEXT NULL,
			  `first_name` VARCHAR(45) NULL,
			  `last_name` VARCHAR(45) NULL,
			  `phone_number` VARCHAR(10) NULL,
			  `state` VARCHAR(2) NULL,
			  `city` VARCHAR(45) NULL,
			  `address` VARCHAR(255) NULL,
			  `zip` VARCHAR(5) NULL,
			  `invoice_number` VARCHAR(255) NULL,
			  `email_address` VARCHAR(255) NULL,
			  `status` VARCHAR(45) NULL,
			  `order_date` DATETIME NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `id_UNIQUE` (`id` ASC));";
		$wpdb->query($sql);

		update_option(self::OPTIONS_DATABASE_SETUP,true,false);
	}

	protected function dispayAdminPage() {
		global $wpdb;
		$error = "";
		$success = "";
		if($_GET['action'] == "paypal_download" && isset($_GET['id'])) {
			$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}".self::TABLNAME." WHERE id = %d",array($_GET['id'])));
			if($row) {
				if($row->status == self::STATUS_DOWNLOADED) {
					$wpdb->update($wpdb->prefix.self::TABLNAME, array('status'=>self::STATUS_PAID), array("id"=>$_GET['id']),array('%s'),array('%d'));
				}
				add_filter( 'wp_mail_content_type', function() {
					return 'text/html';
				} );

				$message = "<h2>Thank you for your purchase</h2><br /><p>Howerver, it seems that you have not downloaded your item(s) yet.
				We are sorry that you was not able to enjoy your item(s) sooner. We are providing you a download link in this email
				so that you will be to download your item(s) and enjoy your item(s) at a moment that is most convient for you.</p><br />
				<br />
				<a href='".site_url(self::DOWNLOAD_PAGENAME."?paymentId={$row->invoice_number}")."'>".site_url(self::DOWNLOAD_PAGENAME."?paymentId={$row->invoice_number}")."</a>
				<br /><br />
				<p>Once again, thank you for your purchase :)</p>";

				if(!wp_mail($row->email_address,"Download your purchase",$message)) {
					$error ="Unable to send email";
				} else {
					$success = "Email sent";
				}
			} else {
				$error="Cannot find user";
			}
		}

		$recentOrders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}".self::TABLNAME." ORDER BY order_date DESC LIMIT 0,100");
		include_once(__DIR__.'/template/index.inc');
	}

	protected function displayNavigationBar($type) {
		include_once(__DIR__."/template/navigation{$type}.inc");
	}

	protected function checkoutItems() {
		$userInfo = array();
		$arrRequired = array(
				"first_name",
				"last_name",
				"email_address",
				"phone_number",
				"state",
				"city",
				"address",
				"zip"
		);

		if(!empty($_POST)) {
			foreach($_POST as $key=>$value){
				$prop = strtolower(preg_replace("/([A-Z]+)/","_\$1",$key));
				$userInfo[$prop] = trim(strip_tags($value));
			}
		}

		$items = json_decode(stripslashes($_COOKIE['paypal_items']));
		if(!items || empty($items) || empty($_POST) || !self::validateUser($userInfo)) {
			wp_safe_redirect(site_url());
			exit;
		}
		error_log(print_r($items,true));

		$invoiceNumber = sha1(date('U'));
		$userInfo['status'] = self::STATUS_NOT_PAID;
		$userInfo['items'] = "";
		$userInfo['invoice_number'] = "";

		$apiContext = new \PayPal\Rest\ApiContext(new \PayPal\Auth\OAuthTokenCredential($this->clientId, $this->secrete));

		$arrItems = array();
		error_log("Price: {$this->price}");
		foreach($items as $item) {
			if(strlen($userInfo['items']) > 0) {
				$userInfo['items'].=", ";
			}
			$userInfo['items'].= $item->itemId;
			$Item = new Item();
			$Item->setName($item->itemName);
			$Item->setCurrency("USD");
			$Item->setQuantity(1);
			$Item->setPrice($this->price);
			$arrItems[] = $Item;
		}
		$ItemList = new ItemList();
		$ItemList->setItems($arrItems);

		$Amount = new Amount();
		$Amount->setCurrency("USD");
		$Amount->setTotal(count($arrItems) * $this->price);

		$Transaction = new Transaction();
		$Transaction->setAmount($Amount);
		$Transaction->setItemList($ItemList);
		$Transaction->setInvoiceNumber($invoiceNumber);

		$RedirectUrls = new RedirectUrls();
		$RedirectUrls->setReturnUrl(site_url(self::CALLBACK_PAGENAME)."?success=true");
		$RedirectUrls->setCancelUrl(site_url(self::CALLBACK_PAGENAME)."?success=false");

		$Payer = new Payer();
		$Payer->setPaymentMethod("paypal");

		$Payment = new Payment();
		$Payment->setIntent("sale");
		$Payment->setPayer($Payer);
		$Payment->setRedirectUrls($RedirectUrls);
		$Payment->setTransactions(array($Transaction));

		try {
			$Payment->create($apiContext);
		} catch(Exception $e) {
			error_log($e->getMessage());
			wp_safe_redirect(site_url());
			exit;
		}

		$url = $Payment->getApprovalLink();
		global $wpdb;
		$userInfo['invoice_number'] = $Payment->getId();
		$userInfo['order_date'] = date("Y-m-d H:i:s");
		$ret = $wpdb->insert($wpdb->prefix.self::TABLNAME,$userInfo,array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'));
		if(!$ret) {
			error_log("Failed to insert user info into the database");
			error_log(print_r($userInfo,true));
		} else {
			error_log(print_r($userInfo,true));
		}
		wp_redirect($Payment->getApprovalLink());
		exit;
	}

	protected function validatePayment(){
		if(!isset($_GET['success']) || $_GET['success'] != 'true' || !isset($_GET['paymentId']) || empty($_GET['paymentId']) || !isset($_GET['PayerID']) || empty($_GET['PayerID'])) {
			wp_safe_redirect(site_url());
			exit;
		}

		global $wpdb;
		$paymentId = $_GET['paymentId'];
		$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}".self::TABLNAME." WHERE invoice_number = %s AND status = %s",array($paymentId,self::STATUS_NOT_PAID)));
		if(empty($row)) {
			wp_safe_redirect(site_url());
			exit;
		}

		$apiContext = new \PayPal\Rest\ApiContext(new \PayPal\Auth\OAuthTokenCredential($this->clientId, $this->secrete));

		$Payment = Payment::get($paymentId,$apiContext);
		$Execution = new PaymentExecution();
		$Execution->setPayerId($_GET['PayerID']);

		$Transaction = new Transaction();
		$Amount = new Amount();

		$Amount->setCurrency("USD");
		error_log(print_r($row,true));
		$Amount->setTotal(count(explode(',',$row->items))* $this->price);

		$Transaction->setAmount($Amount);

		$Execution->addTransaction($Transaction);

		try {
			$result = $Payment->execute($Execution,$apiContext);
			$status = $result->getState();
			if($status == "approved") {
				$status = self::STATUS_PAID;
				$wpdb->update($wpdb->prefix.self::TABLNAME, array("status" => $status), array("invoice_number" => $paymentId),array('%s'),array('%s'));
				@setcookie("paypal_items",null,time()-3600,"/");
				wp_safe_redirect(site_url(self::DOWNLOAD_PAGENAME."?paymentId={$paymentId}"));
				exit;
			}
			$wpdb->update($wpdb->prefix.self::TABLNAME, array("status" => $status), array("invoice_number" => $paymentId),array('%s'),array('%s'));
		} catch (Exception $e) {
			error_log($e->getMessage());
		}
		wp_safe_redirect(site_url());
		exit;
	}

	protected function downloadItems() {
		if(!isset($_GET['paymentId']) || empty($_GET['paymentId'])) {
			exit;
		}

		global $wpdb;
		$paymentId = $_GET['paymentId'];
		$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}".self::TABLNAME." WHERE invoice_number = %s AND status != %s",array($paymentId,self::STATUS_DOWNLOADED)));
		if(empty($row)) {
			exit;
		}

		$Zip = new ZipArchive();
		$filename = wp_upload_dir("0000/00")['path'].'/'.sha1($paymentId).".zip";
		$Zip->open($filename,ZipArchive::OVERWRITE | ZipArchive::CREATE);

		$items = explode(',',$row->items);
		foreach($items as $ID) {
			$imagePath = get_attached_file(intval($ID));
			$Zip->addFile($imagePath,basename($imagePath));
		}
		$Zip->close();
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize($filename));
		header('Content-Disposition: attachment; filename="'.basename($filename).'"');
		readfile($filename);
		unlink($filename);

		$wpdb->update($wpdb->prefix.self::TABLNAME, array("status"=>self::STATUS_DOWNLOADED), array("invoice_number"=>$paymentId),array('%s'),array('%s'));
		exit;
	}
}
