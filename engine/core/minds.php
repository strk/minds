<?php
/**
 * Minds main class
 */
namespace minds\core;

class minds extends base{
	
	public $root = __MINDS_ROOT__;
	public $legacy_lib_dir = "/engine/lib/";
	static public $booted = false;
	
	/**
	 * Initialise the site
	 */
	public function init(){
		
	}
	
	/**
	 * Start the minds engine
	 */
	public function start(){
		
		$this->loadConfigs();
		$this->loadLegacy();

		//Trigger the boot event hook
		\elgg_trigger_event('boot', 'system');
		
		//Load plugins @todo make this oop
		\elgg_load_plugins();
		
		// Complete the boot process for both engine and plugins
		elgg_trigger_event('init', 'system');
		
		//tell the system that we have fully booted
		self::$booted = true;
		
		// System loaded and ready
		\elgg_trigger_event('ready', 'system');
		
		//new logger();
	
	}
	
	/**
	 * Load settings
	 */
	public function loadConfigs(){
		
		global $CONFIG;
		if(!isset($CONFIG))
			$CONFIG = new config();
		
		// Load the system settings
		if (!include_once(__MINDS_ROOT__ . "/engine/settings.php")) {
			$msg = 'Elgg could not load the settings file. It does not exist or there is a file permissions issue.';
			throw new \InstallationException($msg);
		}
		
		// Load mulit globals if set
		if(file_exists(__MINDS_ROOT__ . '/engine/multi.settings.php')) {
			require_once(__MINDS_ROOT__ . '/engine/multi.settings.php');
		}
	}
	
	/**
	 * Load the legacy files for elgg
	 */
	public function loadLegacy(){
		// load the rest of the library files from engine/lib/
		$lib_files = array(
			'elgglib.php', 'access.php', 'actions.php', 'admin.php', 'annotations.php', 'cache.php',
			'calendar.php', 'configuration.php', 'cron.php', 'database.php',
			'entities.php', 'export.php', 'extender.php', 'filestore.php', 'group.php',
			'input.php', 'languages.php', 'location.php', 'mb_wrapper.php',
			'memcache.php', 'metadata.php', 'metastrings.php', 'navigation.php',
			'notification.php', 'objects.php', 'opendd.php', 'output.php',
			'pagehandler.php', 'pageowner.php', 'pam.php', 'plugins.php',
			'private_settings.php', 'relationships.php', 'river.php', 'sessions.php',
			'sites.php', 'statistics.php', 'system_log.php', 'tags.php',
			'user_settings.php', 'users.php', 'upgrade.php', 'views.php',
			'web_services.php', 'widgets.php', 'xml.php', 'xml-rpc.php',
			
			// backward compatibility
			'deprecated-1.7.php', 'deprecated-1.8.php',
		);
		
		foreach ($lib_files as $file) {
			$file = __MINDS_ROOT__ . $this->legacy_lib_dir . $file;
			if (!include_once($file)) {
				$msg = "Could not load $file";
				throw new \InstallationException($msg);
			}
		}
	}
	
	
}