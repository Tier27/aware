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
define( 'AWARE_PREFIX', 'aware_' );

require_once AWARE_PATH . 'includes/class.load.php'; 
require_once AWARE_PATH . 'includes/class.utility.php'; 

$load = new load();
register_activation_hook( __FILE__, '\aware\activate' );
register_deactivation_hook( __FILE__, '\aware\deactivate' );
function activate(){
	Schema::create('clients', function($table)
	{
		$table->increments('id');
		$table->string('first_name');
		$table->string('last_name');
		$table->integer('user_id');
		$table->string('notes');
	});
	Schema::create('projects', function($table)
	{
		$table->increments('id');
		$table->integer('client_id');
		$table->string('duration');
		$table->string('details');
		$table->string('notes');
	});
	Schema::create('events', function($table)
	{
		$table->increments('id');
		$table->string('name');
		$table->date('date');
		$table->string('duration');
		$table->string('details');
		$table->string('notes');
	});
	Schema::create('communications', function($table)
	{
		$table->increments('id');
		$table->integer('to');
		$table->integer('from');
		$table->string('subject');
		$table->mediumText('content');
	});
}
function deactivate(){
	Schema::drop('clients');
	Schema::drop('projects');
	Schema::drop('events');
	Schema::drop('communications');
}
/*****************************/

