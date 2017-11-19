<?php
namespace Youtube_Vids\Drivers;
class GoogleApiDriver {
    private $AccountRecord = null;
    private $GoogleClient = null;

    public function __construct() {
        $this->AccountRecord = \Youtube_Vids\Models\AccountModel::getAccountRecord();
        $this->GoogleClient = new Google_Client();
        $this->GoogleClient->setApplicationName("Youtube Vids");
        $this->GoogleClient->setDeveloperKey($this->AccountRecord->apiKey);
    }
}
