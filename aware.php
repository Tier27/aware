<?php
/**
 * Plugin Name: AWARE
 * Plugin URI: http://aware.plugins.tier27.com/
 * Description: A superb system to manage clients
 * Version: 0.0.1
 * Author: Joshua Kornreich
 * Author URI: http://joshua.tier27.com/
 * Text Domain: aware_plugin
 * Domain Path: languages
 *
 * Copyright 2014  Joshua B. Kornreich  ( email : joshua@tier27.com )
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AWARe; if not, see <http://www.gnu.org/licenses/>.
 *
 * @package AWARE
 * @category Core
 * @author Joshua B. Kornreich
 * @version 0.0.1
 */

namespace aware;
define( 'AWARE_PATH', plugin_dir_path( __FILE__ ) );
define( 'AWARE_DIR_NAME', plugin_basename( dirname( __FILE__ ) ) );
define( 'AWARE_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'AWARE_PATH', plugin_dir_path( __FILE__ ) );
define( 'AWARE_URL', plugin_dir_url( __FILE__ ) );

require_once AWARE_PATH . 'includes/class.load.php'; 
require_once AWARE_PATH . 'includes/class.utility.php'; 

$load = new load();

/*****************************/

