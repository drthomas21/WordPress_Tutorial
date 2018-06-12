<?php
namespace Youtube_Vids\Controllers;
class PageController {
    const WP_OPTIONS = "youtube-vids-filter";
    const LABEL_POPULAR = "popular";
    const LABEL_RECENT = "recent";
    private $Service = null;
    private $flaggedIds = [];

    public function __construct() {
        $this->Service = new \Youtube_Vids\Services\YoutubeService();
        $this->flaggedIds = \get_option(self::WP_OPTIONS,[]);
        if(empty($this->flaggedIds)) {
            $this->flaggedIds = [
                self::LABEL_POPULAR => [],
                self::LABEL_RECENT  => []
            ];

            \update_option(self::WP_OPTIONS,$this->flaggedIds);
        }
    }

    public function getPopularVideos(int $offset = 0, int $limit = 10): array {
        $videos = $this->Service->getPopularVideos($offset,$limit);
        return $videos;
    }

    public function toggledPopularVideo(string $id) {
        $flaggedIds = $this->flaggedIds[self::LABEL_POPULAR];
        if(!is_array($flaggedIds)) $flaggedIds = [];
        if(in_array($id,$flaggedIds)) {
            $flaggedIds = array_filter($flaggedIds,function($item) use ($id) {

                return $id != $item;
            });
        } else {
            $flaggedIds[] = $id;
        }

        $this->flaggedIds[self::LABEL_POPULAR] = array_values($flaggedIds);
        \update_option(self::WP_OPTIONS,$this->flaggedIds);
    }

    public function getRecentVideos(int $offset = 0, int $limit = 10): array {
        $videos = $this->Service->getNewestVideos($offset,$limit);
        return $videos;
    }

    public function toggledRecentVideo(string $id) {
        $flaggedIds = $this->flaggedIds[self::LABEL_RECENT];
        if(!is_array($flaggedIds)) $flaggedIds = [];
        if(in_array($id,$flaggedIds)) {
            $flaggedIds = array_filter($flaggedIds,function($item) use ($id) {

                return $id != $item;
            });
        } else {
            $flaggedIds[] = $id;
        }

        $this->flaggedIds[self::LABEL_RECENT] = array_values($flaggedIds);
        \update_option(self::WP_OPTIONS,$this->flaggedIds);
    }

    public function getFlaggedIds(): array {
        return apply_filters("youtube_vids_flagged_ids",$this->flaggedIds);
    }
}
