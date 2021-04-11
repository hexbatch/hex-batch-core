<?php
/** @noinspection PhpUnused */
namespace hexlet\will_lib;
use Closure;
use Exception;
use hexlet\will_lib\exceptions\SecondTryException;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;

require_once 'GitHelper.php';
require_once 'JsonHelper.php';


class  ErrorLogger {

    //anytime an error is saved, the new id is written here
    public static $last_error_id = null;
    public static $exceptions = [];

    public static function unused_params(...$params) {

    }


    public static function print_exceptions($b_erase_after = false) {
	    $alerts = [];
    	foreach (self::$exceptions as $info) {

    		$s = $info['message'] . self::format_exception_info($info,false);
    		$alerts[] = $s;
	    }
	    if ($alerts) {
		    self::print_alerts( $alerts, 'danger' );
	    }
	    if ($b_erase_after) {
	    	self::$exceptions = [];
	    }
    }

    public static function format_exception_info($info,$minimal_exception_display) {
    	if ($minimal_exception_display) {
			$id = $info['class'];
			return "<span style='font-weight: bold; color: blue'>An exception occurred. Code $id</span>";
	    } else {
    		$message = '<span style="font-weight: bold; color: darkblue">'.$info['message'] . '</span>';
		    $godamg = explode("\n",$info['trace_as_string']);
		    $tracers = implode("<br>\n",$godamg);
		    $origin = '<span style="font-weight: bold; color: blue">'.$info['class'] . '</span>' .
		              ' ' . '<span style="font-weight: bold; color: green">'. $info['function_name']  . '</span>' .
		              ' ' . '<span style="font-weight: bold; color: purple">'. $info['line'] . '</span>' .
		              ' ' . '<span style="font-weight: bold; color: peru">'. $info['file_name'] . '</span>' ;
		    $s =  "<div style='text-align: left'>$message<br>$origin<br><p style='padding-left: 1em;font-size: xx-small;text-align: left'>$tracers</p>";
		    return $s;
	    }
    }

	/**
	 * Prints out html
	 *
	 * Allows messages to be put on screen in a nice way
	 * Requires bootstrap js and css to in the page
	 * Also, assumes inside a bootstrap container or container-fluid  div
	 * @param array $alerts <p>
	 *  array of things that will be converted to strings
	 * </p>
	 * @param string $style <p>
	 *   one of danger,warning,info, success
	 * </p>
	 *
	 * @return void
	 */
	public static function print_alerts(array $alerts,$style='danger') {


		switch ($style) {
			case 'danger':
				{
					$title = "Error!";
					break;
				}
			case 'warning':
				{
					$title = "Warning";
					break;
				}
			case 'info':
				{
					$title = "Notice";
					break;
				}

			case 'success':
				{
					$title = "Success";
					break;
				}

			default: {
				$style = 'info';
				$title = "Message";
			}
		}

		print "<!-- Generated by print_alerts -->\n";
		print "<div class='row'>\n";
		print "  <div class='col-sm-12 col-md-12 col-lg-12 '>\n";
		foreach ($alerts as $alert) {
			$alert = strval($alert);
			print "    <div class='alert alert-$style alert-dismissible' role='alert'>\n";
			print '      <a href="#" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>'."\n";

			print "      <strong>$title</strong> $alert"."\n";
			print "    </div>\n";

		}
		print "  </div>\n";
		print "</div>\n";
	}

	/**
	 * @param $e Exception
	 * @param mixed $extra
	 *
	 * @return array, exception info
	 *      with extra key of id
	 */
    public static function saveException( Exception $e,$extra=null) {
    	$info = null;
    	try {
		    $info = self::getExceptionInfo( $e );
		    $info['extra'] = $extra;
			self::$exceptions[] = $info;
			$last_id = self::saveErrorInfo( $info );
			$info['id'] = $last_id;
			return $info;
	    } catch ( exceptions\SecondTryException $s) {
    		JsonHelper::print_nice($info);
    		die("Cannot save or process Exception of ". $e->getMessage());
	    }

    }

	/**
	 * @param array $info <p>
	 *   @param integer|null $parent_id
	 *
	 * @return int , the new id in the database row
	 * @throws SecondTryException if anything happens
	 *@see ErrorLogger::getExceptionInfo() output
	 *   this function will strip out any key called other_info
	 * </p>
	 */
	public static function saveErrorInfo(array $info,$parent_id = null) {
		global $wpdb;
		$info['parent_id'] = $parent_id;
		$batch_id = null;
		$version = HEXLET_VERSION;

		try {
			unset($info['other_info']);
			$info['trace'] = null; //do not save the array trace, recursion issues
			$mydb = DBSelector::getConnection();
			$savy = array(
				'parent_id' =>  $info['parent_id'],
				'batch_id' =>  $batch_id,
				'gokabam_version'   => $version,
				'hostname' =>  JsonHelper::toStringAgnostic($info['hostname']),
				'machine_id'  =>  JsonHelper::toStringAgnostic($info['machine_id']),
				'caller_ip_address' =>  JsonHelper::toStringAgnostic($info['caller_ip_address']),
				'git_branch' =>  JsonHelper::toStringAgnostic($info['git_branch']),
				'git_last_commit_hash' =>  JsonHelper::toStringAgnostic($info['git_last_commit_hash']),
				'is_commit_modified' =>  JsonHelper::toStringAgnostic($info['is_commit_modified']),
				'argv' =>  JsonHelper::toStringAgnostic($info['argv']),
				'request_method' =>  JsonHelper::toStringAgnostic($info['request_method']),
				'post_super'  =>  JsonHelper::toStringAgnostic($info['post_super']),
				'get_super'  =>  JsonHelper::toStringAgnostic($info['get_super']),
				'cookies_super' =>  JsonHelper::toStringAgnostic($info['cookies_super']),
				'server_super'  =>  JsonHelper::toStringAgnostic($info['server_super']),
				'message'  =>  JsonHelper::toStringAgnostic($info['message']),
				'class_of_exception'  =>  JsonHelper::toStringAgnostic($info['class_of_exception']),
				'code_of_exception'  =>  JsonHelper::toStringAgnostic($info['code_of_exception']),
				'file_name'  =>  JsonHelper::toStringAgnostic($info['file_name']),
				'line'  =>  JsonHelper::toStringAgnostic($info['line']),
				'trace'  =>  JsonHelper::toStringAgnostic($info['trace']),
				'class'  =>  JsonHelper::toStringAgnostic($info['class']),
				'function_name'  =>  JsonHelper::toStringAgnostic($info['function_name']),
				'trace_as_string' =>  JsonHelper::toStringAgnostic($info['trace_as_string']),
				'extra' => JsonHelper::toStringAgnostic($info['extra']),
				'chained'  =>  JsonHelper::toStringAgnostic($info['chained'])
			);
			$insert_result = $mydb->insert(self::getDBTableName(),$savy);
			if ($insert_result === false) {
				throw new exceptions\SecondTryException("could not create Exception info row in the database, but error is not showing in WPDB class" );
			}
			$parent_id = $wpdb->insert_id;


			//save any possible chaining
			foreach ($info['chained'] as $chain) {
				self::saveErrorInfo($chain,$parent_id);
			}
			self::$last_error_id = $parent_id;
			return $parent_id;
		} catch ( Exception $e) {

			$info['outer_exception'] = self::getExceptionInfo($e);
			$foolproof = print_r($info,true);
			throw new exceptions\SecondTryException($foolproof);

		}

	}

	public static function getDBTableName() {
		return "sym_action_error_logs";
	}


    /**
     * @param object|Exception $e an exception
     *
     * @return array <p>
     *      string      'hostname' => the name of the machine the exception occurred on
     *      string      'machine_id' => mac address or similar id
     *      string      'caller_ip_address' => the ip address of the browser caller, else will be null
     *      string      'git_branch' => the git git_branch
     *      string      'git_last_commit_hash' => the sha1 hash of the last commit made on the code throwing the exception
     *      boolean     'is_commit_modified' => true if the code has been changed since the last commit
     *      array|null  'argv' => array of arguments if this is called from the command line
     *      string      'request_method' => usually post or get
     *      array|null  'post_super'=> the post vars, if set
     *      array|null  'get_super' => the get vars, if set
     *      array|null  'cookies_super' => array of cookies, if any
     *      array       'server_super' => the server array
     *      string      'message' => the exception message
     *      string      'class_of_exception' => the name of the exception class
     *      string      'code_of_exception' => exception code
     *      string      'file_name' => the file the exception occurred in
     *      string      'line' => line number in the file of the exception
     *      array       'trace' => array of the trace, its called this because its converted to json in the database
     *      string      'class' => if the exception occurred inside a class, it will be listed here
     *      string      'function_name' => if the exception occurred inside a function, it will be listed here
     *      string      'trace_as_string' => the trace in an easier to read string format
     *      array|null  'chained' => an array of exceptions chained to this one, with the same info as above
     * </p>
     */

    public static function getExceptionInfo($e) {
        $ret = self::get_call_info();
	    $gitinfo = ErrorLogger::getGitInfo($e->getFile());
	    $ret['git_branch'] = $gitinfo['git_branch'];
	    $ret['git_last_commit_hash'] = $gitinfo['commit'];
	    $ret['is_commit_modified'] = $gitinfo['dirty'];
        $ret['message'] = $e->getMessage();
        $ret['class_of_exception'] = get_class($e);
        $ret['code_of_exception'] = $e->getCode();
        $ret['file_name'] = $e->getFile();
        $ret['line'] = $e->getLine();
	    $ret['trace_as_string'] = $e->getTraceAsString();
	    //self::flattenExceptionBacktrace($e);
        $trace = $e->getTrace();
        $ret['trace'] = $trace;
        $ret['extra'] = null;


        if( is_array($trace) && isset($trace[0]) && isset($trace[0]['class']) && $trace[0]['class'] != '') {
            $ret['class'] =  $trace[0]['class'];
        } else {
            $ret['class'] = null;
        }

        if(is_array($trace) && isset($trace[0]) && isset($trace[0]['function']) && $trace[0]['function'] != '') {
            $ret['function_name'] =  $trace[0]['function'];
        } else {
            $ret['function_name'] = null;
        }





        $ret['chained'] = [];
        //do chained exceptions
        $f = $e->getPrevious();
        while($f  ) {
            $ret['chained'][] = self::getExceptionInfo($f);
            $f = $f->getPrevious();
        }


        return $ret;
    }

	/**

	 * @return array of info to help see who called this script
	 * <p>
	 *  string 'hostname' => the name of the machine the exception occurred on
	 *  string 'machine_id' => mac address or similar id
	 *  string 'caller_ip_address' => the ip address of the browser caller, else will be null
	 *  string 'git_branch' => the git git_branch
	 *  string 'git_last_commit_hash' => the sha1 hash of the last commit made on the code throwing the exception
	 *  boolean 'is_commit_modified' => true if the code has been changed since the last commit
	 *  array|null 'argv' => array of arguments if this is called from the command line
	 *  string 'request_method' => usually post or get
	 *  array|null 'post_super'=> the post vars, if set
	 *  array|null 'get_super' => the get vars, if set
	 *  array|null 'cookies_super' => array of cookies, if any
	 *  array 'server_super' => the server array
	 * </p>
	 */
	public static function get_call_info()
	{

		$ret = [];
		$ret['hostname'] = gethostname();
		$ret['machine_id'] = ErrorLogger::getMachineID();


		$isCLI = (php_sapi_name() == 'cli');





		$ret['argv'] = null;
		$ret['request_method'] = null;
		$ret['post_super'] = null;
		$ret['get_super'] = null;
		$ret['cookies_super'] = null;
		if ($isCLI) {
			$ret['argv'] = $_SERVER['argv'];
		} else {
			$ret['request_method'] = $_SERVER['REQUEST_METHOD'];
			$ret['post_super'] = $_POST;
			$ret['get_super'] = $_GET;
			$ret['cookies_super'] = $_COOKIE;
		}


		$ret['caller_ip_address'] = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : null;
		return $ret;
	}

    /**
     * Gets an identifying id for a machine on all platforms
     * mac and linux have ifconfig
     * windows has ipconfig
     * @return string
     */
    public static function getMachineID()
    {
        try {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                ob_start();
                system('ipconfig /all');
                $mycom = ob_get_contents(); // Capture the output into a variable
                ob_clean();
                $findme = "Physical";
                $pos = strpos($mycom, $findme);
                if ($pos === false) {
                    $pos = 25;
                }
                return substr($mycom, ($pos + 36), 17);
            } else {
                $ifconfig = shell_exec("ifconfig ");
                $valid_mac = "([0-9A-F]{2}[:-]){5}([0-9A-F]{2})";
                preg_match("/" . $valid_mac . "/i", $ifconfig, $ifconfig);
                if (isset($ifconfig[0])) {
                    return trim(strtoupper($ifconfig[0]));
                }
                return null;
            }
        } catch (Exception $e) {
            return null;
        }

    }


	/**
	 * @param $file
	 * @return array ['git_branch'(string),'commit'(string),'dirty'(boolean) ]
	 */
	public static function getGitInfo($file) {
		try {
			$a = new GitHelper($file);
			if (!$a->is_repo()) {
				return ['git_branch'=>null,'commit'=>null,'dirty'=>0];
			}
		} catch(Exception $e)  {
			return ['git_branch'=>null,'commit'=>null,'dirty'=>0];
		}

		try {
			$git_branch = $a->getCurrentBranchName();
		} catch(Exception $e)  {
			$git_branch =  null;
		}

		try {
			$commit = $a->getCurrentCommit();
		} catch(Exception $e)  {
			$commit =  null;
		}

		try {
			$b_dirty = $a->hasChanges();
		} catch(Exception $e)  {
			$b_dirty =  false;
		}
		return ['git_branch'=>$git_branch,'commit'=>$commit,'dirty'=>$b_dirty];
	}

    /**
     * Send the output from a backtrace to string that has newlines
     * @param string $message Optional message that will be added before the backtrace
     * @return string
     * @author  http://php.net/manual/en/function.debug-backtrace.php , comment
    */
    public static function back_trace_as_string_with_newlines($message = '') {
        $ret = '';
        $trace = debug_backtrace();
        if ($message) {
            $ret .= $message;
            $ret.= "\n";
        }
        $caller = array_shift($trace);
        $function_name = $caller['function'];
        $ret .= (sprintf('%s: Called from %s:%s', $function_name, $caller['file'], $caller['line']));
        $ret.= "\n";
        foreach ($trace as $entry_id => $entry) {
            $entry['file'] = $entry['file'] ? : '-';
            $entry['line'] = $entry['line'] ? : '-';
            if (empty($entry['class'])) {
                $ret .= (sprintf('%3s. %s() %s:%s', $entry_id + 1, $entry['function'], $entry['file'], $entry['line']));
                $ret.= "\n";
            } else {
                $ret .= (sprintf('%3s. %s->%s() %s:%s', $entry_id + 1, $entry['class'], $entry['function'], $entry['file'], $entry['line']));
                $ret.= "\n";
            }
        }

        return trim($ret);
    }


	/**
	 * @param Exception $exception
	 * @return void
	 * @throws ReflectionException
	 */
	static function flattenExceptionBacktrace(Exception $exception) {
		$traceProperty = (new ReflectionClass('Exception'))->getProperty('trace');
		$traceProperty->setAccessible(true);
		$flatten = function(&$value, /** @noinspection PhpUnusedParameterInspection */ $key) {
			if ($value instanceof Closure) {
				$closureReflection = new ReflectionFunction($value);
				$value = sprintf(
					'(Closure at %s:%s)',
					$closureReflection->getFileName(),
					$closureReflection->getStartLine()
				);
			} elseif (is_object($value)) {
				$value = sprintf('object(%s)', get_class($value));
			} elseif (is_resource($value)) {
				$value = sprintf('resource(%s)', get_resource_type($value));
			}
		};
		do {
			$trace = $traceProperty->getValue($exception);
			foreach($trace as &$call) {
				array_walk_recursive($call['args'], $flatten);
			}
			$traceProperty->setValue($exception, $trace);
		} while($exception = $exception->getPrevious());
		$traceProperty->setAccessible(false);
	}

}


