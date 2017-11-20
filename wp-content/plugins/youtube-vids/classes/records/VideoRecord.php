<?php
namespace Youtube_Vids\Records;
class VideoRecord extends BaseRecord {
    protected $id = '';
    protected $snippet = null;

    public function __construct(\stdClass $Id, \stdClass $snippet) {
        $this->props = [
            "id" => "id",
            "Snippet" => "snippet"
        ];

        $this->id = $Id->videoId;
        $this->snippet = $snippet;
    }
}
