<?php
namespace Youtube_Vids\Records;
class AccountRecord extends BaseRecord {
    protected $id = "";
    protected $secret = "";

    public function __construct(string $id, string $secret) {
        $this->props = [
            "id" => "id",
            "secret" => "secret"
        ];

        $this->id = $id;
        $this->secret = $secret;
    }
}
