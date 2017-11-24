<?php
namespace Youtube_Vids\Drivers;
class GoogleApiDriver {
    const OPTION_ACCESS_TOKEN = "youtube_vids_access_token";
    private $AccountRecord = null;
    private $GoogleClient = null;

    public function __construct() {
        $this->AccountRecord = \Youtube_Vids\Models\AccountModel::getAccountRecord();
        $this->GoogleClient = new \Google_Client();
        $this->GoogleClient->setApplicationName("Youtube Vids");
        $this->GoogleClient->setAccessType("offline");
        $this->GoogleClient->setClientSecret($this->AccountRecord->secret);
        $this->GoogleClient->setClientId($this->AccountRecord->id);
        $this->GoogleClient->setScopes('https://www.googleapis.com/auth/youtube');
    }

    public function authenticate(string $code) {
        $this->GoogleClient->authenticate($code);
        update_option(self::OPTION_ACCESS_TOKEN,$this->GoogleClient->getAccessToken());
    }

    public function checkAccessToken(): bool {
        $token = $this->getAccessToken();
        if(is_array($token) && array_key_exists("access_token",$token) && array_key_exists("expires_in",$token) && array_key_exists("created",$token)) {
            return time() < (int)($token['created'] + $token['expires_in']);
        }

        return false;
    }

    public function getAccessToken(): array {
        $token = get_option(self::OPTION_ACCESS_TOKEN,[]);
        return !is_array($token) ? [] : $token;
    }

    public function setAccessToken(): bool {
        if($this->checkAccessToken()) {
            $this->GoogleClient->setAccessToken($this->getAccessToken());
            return true;
        } else {
            $token = $this->GoogleClient->getAccessToken();
            update_option(self::OPTION_ACCESS_TOKEN,$token);
            return true;
        }
        return false;
    }

    public function createAuthUrl(): string {
        return $this->GoogleClient->createAuthUrl();
    }

    public function getClient(): \Google_Client {
        return $this->GoogleClient;
    }

    public function prepareScopes() {
        $this->GoogleClient->prepareScopes();
    }

    public function setRedirectUri(string $uri) {
        $this->GoogleClient->setRedirectUri($uri);
    }
}
