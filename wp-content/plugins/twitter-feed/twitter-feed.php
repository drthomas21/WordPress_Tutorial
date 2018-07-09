<?php
/**
* Plugin Name: Twitter Feed
* Plugin URI: https://github.com/drthomas21/WordPress_Tutorial/tree/master/wp-content/plugins/twitter-feed
* Description: Create your own twitter feed
* Author: niiiiisama
* Author URI: https://superwordpressguide.com/about/
* Version: 1.0
*
* Twitter Feed is distributed under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* any later version.
*
* Twitter Feed is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Twitter Feed. If not, see <http://www.gnu.org/licenses/>.
**/

define("TWITTER_FEED_DIR",__DIR__);
define("TWITTER_FEED_CACHE_GROUP","twitter-feed");

//register autoload
spl_autoload_register(function ($class_name) {
    //Ignore class_name that does not match
    if(stripos($class_name,"Twitter_Feed") === false) {
        return;
    }

    //Replace namespace prefix with class directory
    $class_name = str_replace("Twitter_Feed\\","classes".DIRECTORY_SEPARATOR,$class_name);

    //Build path to the expected class
	$path = preg_replace_callback('!^(.*)?\\\([A-Za-z0-9_]+)$!',function(array $matches): string {
        $matches[1] = str_replace("_","-",$matches[1]);
		return strtolower(str_replace('\\', DIRECTORY_SEPARATOR ,$matches[1])) . DIRECTORY_SEPARATOR . $matches[2];
	},$class_name);

    if(file_exists(__DIR__. DIRECTORY_SEPARATOR . $path . ".php")) {
        include_once(__DIR__. DIRECTORY_SEPARATOR . $path . ".php");
    }
});
