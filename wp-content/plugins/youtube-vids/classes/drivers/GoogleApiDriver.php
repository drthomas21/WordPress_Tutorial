<?php
namespace Youtube_Vids\Drivers;
class GoogleApiDriver {
    const OPTION_ACCESS_TOKEN = "youtube_vids_access_token";
    private $AccountRecord = null;
    private $GoogleClient = null;

    public function __construct() {
        //$this->AccountRecord = \Youtube_Vids\Models\AccountModel::getAccountRecord();
        $this->GoogleClient = new \Google_Client();
        $this->GoogleClient->setAuthConfig(GOOGLE_CLIENT_FILE);
        $this->GoogleClient->setAccessType("offline");
        $this->GoogleClient->setIncludeGrantedScopes(true);
        $this->GoogleClient->setIncludeGrantedScopes(true);
        $this->GoogleClient->setApprovalPrompt('force');
        $this->GoogleClient->addScope(\Google_Service_YouTube::YOUTUBE_READONLY);
        //$this->GoogleClient->addScope(\Google_Service_Drive::DRIVE_METADATA_READONLY);
    }

    public function authenticate(string $code) {
        $this->GoogleClient->authenticate($code);
        $token = $this->GoogleClient->getAccessToken();
        update_option(self::OPTION_ACCESS_TOKEN,$token);
    }

    public function checkAccessToken(): bool {
        $token = $this->getAccessToken();
        if(empty($token)) return false;

        $this->GoogleClient->setAccessToken($token);
        return !$this->GoogleClient->isAccessTokenExpired();
    }

    public function getAccessToken(): array {
        $token = get_option(self::OPTION_ACCESS_TOKEN,[]);
        return !is_array($token) ? [] : $token;
    }

    public function refreshAccessToken(): bool {
        $token = $this->getAccessToken();
        if(empty($token)) return false;

        $this->GoogleClient->setAccessToken($token);
        if($this->checkAccessToken()) {
            return true;
        } else {
            $oldToken = $this->getAccessToken();
            if(is_array($oldToken) && array_key_exists('refresh_token',$oldToken)) {
                $token = $this->GoogleClient->fetchAccessTokenWithRefreshToken();
                if(is_array($token)) {
                    update_option(self::OPTION_ACCESS_TOKEN,$token);
                    return true;
                }
            }
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
