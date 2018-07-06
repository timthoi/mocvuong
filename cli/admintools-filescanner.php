<?php
/**
 * @package   AdminTools
 * @copyright Copyright (c)2010-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

// Define ourselves as a parent file
define('_JEXEC', 1);

// Disable Akeeba Engine debugging
define('AKEEBADEBUG', 0);

$minphp = '5.3.4';

if (version_compare(PHP_VERSION, $minphp, 'lt'))
{
	$curversion = PHP_VERSION;
	$bindir = PHP_BINDIR;
	echo <<< ENDWARNING
================================================================================
WARNING! Incompatible PHP version $curversion
================================================================================

This CRON script must be run using PHP version $minphp or later. Your server is
currently using the older version $curversion which would cause this script to
crash. As a precaution we have aborted execution of the script. Please contact
your host and ask them for the correct path to the PHP CLI binary for PHP $minphp
or later, then edit your CRON job and replace your current path to PHP with the
one your host gave you.

For your information, the current PHP version information is as follows.

PATH:    $bindir
VERSION: $curversion

Further clarifications:

1. There is absolutely no possible way that you are receiving this warning in
   error. We are using the PHP_VERSION constant to detect the PHP version you
   are currently using. This is what PHP itself reports as its own version. It
   simply cannot lie.

2. Even though your *site* may be running in a higher PHP version that the one
   reported above, your CRON scripts will most likely NOT be running under it.
   This has to do with the fact that your site DOES NOT run under the command
   line and there are different executable files (binaries) for the web and
   command line versions of PHP.

3. Please note that you MUST NOT ask us for support about this error. We cannot
   possibly know the correct path to the PHP CLI binary as we have not set up
   your server. Your host must know and give that information.

4. The latest published versions of PHP can be found at http://www.php.net/
   Any older version is considered insecure and must NOT be used on a live
   server. If your server uses a much older version of PHP than that please
   notify them that their servers are insecure and in need of an update.

This script will now terminate. Goodbye.

ENDWARNING;
	die();
}

// Load system defines
if (file_exists(__DIR__ . '/defines.php'))
{
	require_once __DIR__ . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	$path = rtrim(__DIR__, DIRECTORY_SEPARATOR);
	$rpos = strrpos($path, DIRECTORY_SEPARATOR);
	$path = substr($path, 0, $rpos);
	define('JPATH_BASE', $path);
	require_once JPATH_BASE . '/includes/defines.php';
}

// Load the rest of the necessary files
if (file_exists(JPATH_LIBRARIES . '/import.legacy.php'))
{
	require_once JPATH_LIBRARIES . '/import.legacy.php';
}
else
{
	require_once JPATH_LIBRARIES . '/import.php';
}
require_once JPATH_LIBRARIES . '/cms.php';

JLoader::import('joomla.application.cli');

if (version_compare(JVERSION, '3.4.9999', 'ge'))
{
	// Joomla! 3.5 and later does not load the configuration.php unless you explicitly tell it to.
	JFactory::getConfig(JPATH_CONFIGURATION . '/configuration.php');
}

/**
 * Admin Tools File Alteration Monitor (PHP File Change Scanner) CLI application
 */
class AdminToolsFAM extends JApplicationCli
{
	/**
	 * JApplicationCli didn't want to run on PHP CGI. I have my way of becoming
	 * VERY convincing. Now obey your true master, you petty class!
	 *
	 * @param JInputCli   $input
	 * @param JRegistry   $config
	 * @param JDispatcher $dispatcher
	 */
	public function __construct(JInputCli $input = null, JRegistry $config = null, JDispatcher $dispatcher = null)
	{
		// Close the application if we are not executed from the command line, Akeeba style (allow for PHP CGI)
		if (array_key_exists('REQUEST_METHOD', $_SERVER))
		{
			die('You are not supposed to access this script from the web. You have to run it from the command line. If you don\'t understand what this means, you must not try to use this file before reading the documentation. Thank you.');
		}

		$cgiMode = false;

		if (!defined('STDOUT') || !defined('STDIN') || !isset($_SERVER['argv']))
		{
			$cgiMode = true;
		}

		// If a input object is given use it.
		if ($input instanceof JInput)
		{
			$this->input = $input;
		}
		// Create the input based on the application logic.
		else
		{
			if (class_exists('JInput'))
			{
				if ($cgiMode)
				{
					$query = "";
					if (!empty($_GET))
					{
						foreach ($_GET as $k => $v)
						{
							$query .= " $k";
							if ($v != "")
							{
								$query .= "=$v";
							}
						}
					}
					$query = ltrim($query);
					$argv = explode(' ', $query);
					$argc = count($argv);

					$_SERVER['argv'] = $argv;
				}

				$this->input = new JInputCLI();
			}
		}

		// If a config object is given use it.
		if ($config instanceof JRegistry)
		{
			$this->config = $config;
		}
		// Instantiate a new configuration object.
		else
		{
			$this->config = new JRegistry;
		}

		// If a dispatcher object is given use it.
		if ($dispatcher instanceof JDispatcher)
		{
			$this->dispatcher = $dispatcher;
		}
		// Create the dispatcher based on the application logic.
		else
		{
			$this->loadDispatcher();
		}

		// Load the configuration object.
		$this->loadConfiguration($this->fetchConfigurationData());

		// Set the execution datetime and timestamp;
		$this->set('execution.datetime', gmdate('Y-m-d H:i:s'));
		$this->set('execution.timestamp', time());

		// Set the current directory.
		$this->set('cwd', getcwd());

		// Work around Joomla! 3.4.7's JSession bug
		if (version_compare(JVERSION, '3.4.7', 'eq'))
		{
			JFactory::getSession()->restart();
		}
	}

	/**
	 * The main entry point of the application
	 */
	public function execute()
	{
		// Set all errors to output the messages to the console, in order to
		// avoid infinite loops in JError ;)
		restore_error_handler();
		JError::setErrorHandling(E_ERROR, 'die');
		JError::setErrorHandling(E_WARNING, 'echo');
		JError::setErrorHandling(E_NOTICE, 'echo');

		// Required by Joomla!
		JLoader::import('joomla.environment.request');

		// Set the root path to Admin Tools Pro
		define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/com_admintools');

		// Load F0F
		JLoader::import('f0f.include');

		// Load the language files
		$jlang = JFactory::getLanguage();
		$jlang->load('com_admintools', JPATH_ADMINISTRATOR);
		$jlang->load('com_admintools.override', JPATH_ADMINISTRATOR);

		// Load the version.php file
		include_once JPATH_COMPONENT_ADMINISTRATOR . '/version.php';

		// Display banner
		$year = gmdate('Y');
		$phpversion = PHP_VERSION;
		$phpenvironment = PHP_SAPI;
		$phpos = PHP_OS;

		$this->out("Admin Tools PHP File Scanner CLI " . ADMINTOOLS_VERSION . " (" . ADMINTOOLS_DATE . ")");
		$this->out("Copyright (C) 2011-$year Nicholas K. Dionysopoulos");
		$this->out(str_repeat('-', 79));
		$this->out("Admin Tools is Free Software, distributed under the terms of the GNU General");
		$this->out("Public License version 3 or, at your option, any later version.");
		$this->out("This program comes with ABSOLUTELY NO WARRANTY as per sections 15 & 16 of the");
		$this->out("license. See http://www.gnu.org/licenses/gpl-3.0.html for details.");
		$this->out(str_repeat('-', 79));
		$this->out("You are using PHP $phpversion ($phpenvironment)");
		$this->out("");

		$safe_mode = true;

		if (function_exists('ini_get'))
		{
			$safe_mode = ini_get('safe_mode');
		}

		if (!$safe_mode && function_exists('set_time_limit'))
		{
			$this->out("Unsetting time limit restrictions");
			@set_time_limit(0);
		}

		// Sanity check
		$factoryPath = JPATH_ADMINISTRATOR . '/components/com_admintools/engine/Autoloader.php';

		if (!file_exists($factoryPath))
		{
			$this->out('Could not load the file scanning engine; aborting execution');

			return;
		}

		// Load the Akeeba Engine autoloader
		define('AKEEBAENGINE', 1);
		define('AKEEBA_BACKUP_ORIGIN', 'backend');
		require_once JPATH_ADMINISTRATOR . '/components/com_admintools/engine/Autoloader.php';

		// Load the platform
		\Akeeba\Engine\Platform::addPlatform('filescan', JPATH_ADMINISTRATOR . '/components/com_admintools/platform/Filescan');

		// Load the engine configuration
		\Akeeba\Engine\Platform::getInstance()->load_configuration(1);
		$this->aeconfig = \Akeeba\Engine\Factory::getConfiguration();


		$configOverrides['volatile.core.finalization.action_handlers'] = array(
			new Akeeba\Engine\Finalization\Email()
		);
		$configOverrides['volatile.core.finalization.action_queue'] = array(
			'remove_temp_files',
			'update_statistics',
			'update_filesizes',
			'apply_quotas',
			'send_scan_email'
		);

		// Apply the configuration overrides, please
		$platform = \Akeeba\Engine\Platform::getInstance();
		$platform->configOverrides = $configOverrides;

		$kettenrad = \Akeeba\Engine\Factory::getKettenrad();
		$options = array(
			'description' => '',
			'comment'     => '',
			'jpskey'      => ''
		);
		$kettenrad->setup($options);

		\Akeeba\Engine\Factory::getLog()->open(AKEEBA_BACKUP_ORIGIN);
		\Akeeba\Engine\Factory::getLog()->log(true, '');

		// Dummy array so that the loop iterates once
		$array = array(
			'HasRun' => 0,
			'Error'  => ''
		);

		$warnings_flag = false;

		$this->out("Starting file scanning");
		$this->out("");

		while (($array['HasRun'] != 1) && (empty($array['Error'])))
		{
			\Akeeba\Engine\Factory::loadState(AKEEBA_BACKUP_ORIGIN);

			$kettenrad = \Akeeba\Engine\Factory::getKettenrad();

			$kettenrad->tick();

			\Akeeba\Engine\Factory::saveState(AKEEBA_BACKUP_ORIGIN);

			\Akeeba\Engine\Factory::getTimer()->resetTime();

			$array = $kettenrad->getStatusArray();

			$time = date('Y-m-d H:i:s \G\M\TO (T)');
			$memusage = $this->memUsage();

			$warnings = "no warnings issued (good)";

			if (!empty($array['Warnings']))
			{
				$warnings_flag = true;
				$warnings = "POTENTIAL PROBLEMS DETECTED; " . count($array['Warnings']) . " warnings issued (see below).\n";
				foreach ($array['Warnings'] as $line)
				{
					$warnings .= "\t$line\n";
				}

				$kettenrad->resetWarnings();
			}

			echo <<<ENDSTEPINFO
Last Tick   : $time
Last folder : {$array['Step']}
Memory used : $memusage
Warnings    : $warnings


ENDSTEPINFO;
		}

		// Clean up
		\Akeeba\Engine\Factory::getFactoryStorage()->reset(AKEEBA_BACKUP_ORIGIN);

		$exitCode = 0;

		if (!empty($array['Error']))
		{
			$this->out("An error has occurred:\n{$array['Error']}");
			$exitCode = 2;
		}
		else
		{
			$this->out("File scanning finished successfully");
		}

		if ($warnings_flag)
		{
			$exitCode = 1;
		}

		$this->out("Peak memory usage: " . $this->peakMemUsage());

		exit($exitCode);
	}

	function memUsage()
	{
		if (function_exists('memory_get_usage'))
		{
			$size = memory_get_usage();
			$unit = array('b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');

			return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
		}
		else
		{
			return "(unknown)";
		}
	}

	function peakMemUsage()
	{
		if (function_exists('memory_get_peak_usage'))
		{
			$size = memory_get_peak_usage();
			$unit = array('b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');

			return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
		}
		else
		{
			return "(unknown)";
		}
	}
}

JApplicationCli::getInstance('AdminToolsFAM')->execute();
