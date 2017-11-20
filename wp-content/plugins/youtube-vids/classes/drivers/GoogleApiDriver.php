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
        $this->GoogleClient->setClientSecret($this->AccountRecord->secret);
        $this->GoogleClient->setClientId($this->AccountRecord->id);
        $this->GoogleClient->setScopes('https://www.googleapis.com/auth/youtube');
    }

    public function authenticate(string $code) {
        $this->GoogleClient->authenticate($code);
        update_option(self::OPTION_ACCESS_TOKEN,$this->GoogleClient->getAccessToken());
    }

    public function checkAccessToken(): bool {
        $token = get_option(self::OPTION_ACCESS_TOKEN,"");
        if(!empty($token)) {
            $this->GoogleClient->setAccessToken($token);
            return (bool)$this->GoogleClient->getAccessToken();
        }

        return false;
    }

    public function createAuthUrl(): string {
        return $this->GoogleClient->createAuthUrl();
    }

    public function getAccessToken(): string {
        return get_option(self::OPTION_ACCESS_TOKEN,"");
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
