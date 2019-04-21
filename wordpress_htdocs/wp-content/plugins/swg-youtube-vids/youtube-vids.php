<?php
/**
* Plugin Name: SWG Youtube Profile Vids
* Plugin URI: https://github.com/drthomas21/WordPress_Tutorial/tree/master/wp-content/plugins/youtube-vids
* Description: Plaster your Youtube videos all over your site
* Author: niiiiisama
* Author URI: https://superwordpressguide.com/about/
* Version: 2.0
*
* Youtube Profile Vids is distributed under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* any later version.
*
* Youtube Profile Vids is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Youtube Profile Vids. If not, see <http://www.gnu.org/licenses/>.
**/

define("YOUTUBE_VIDS_DIR",__DIR__);
define("YOUTUBE_VIDS_CACHE_GROUP","youtube-vids");

//register autoload
spl_autoload_register(function ($class_name) {
    //Ignore class_name that does not match
    if(stripos($class_name,"Youtube_Vids") === false) {
        return;
    }

    //Replace namespace prefix with class directory
    $class_name = str_replace("Youtube_Vids\\","classes".DIRECTORY_SEPARATOR,$class_name);

    //Build path to the expected class
	$path = preg_replace_callback('!^(.*)?\\\([A-Za-z0-9_]+)$!',function(array $matches): string {
        $matches[1] = str_replace("_","-",$matches[1]);
		return strtolower(str_replace('\\', DIRECTORY_SEPARATOR ,$matches[1])) . DIRECTORY_SEPARATOR . $matches[2];
	},$class_name);

    if(file_exists(__DIR__. DIRECTORY_SEPARATOR . $path . ".php")) {
        include_once(__DIR__. DIRECTORY_SEPARATOR . $path . ".php");
    }
});

//Load Youtube Library
require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
\Youtube_Vids\Controllers\AdminController::getInstance();

require_once(__DIR__.DIRECTORY_SEPARATOR.'wp-functions.php');
