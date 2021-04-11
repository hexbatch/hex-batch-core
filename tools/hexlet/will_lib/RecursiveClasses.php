<?php
/** @noinspection PhpUnused */
namespace hexlet\will_lib;


use Exception;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClassConstant;
use ReflectionException;
use RegexIterator;

/**
 * User Beware !
 * Will load all files given directory and pattern
 * but can call methods if you know the class name
 * Class RecursiveClasses
 * @package gokabam_api
 */
class RecursiveClasses {

	private $def_classes;
	private $directory;
	private $pattern = null;

	/**
	 * RecursiveClasses constructor.
	 *
	 * @param string $directory needs to be passed by realpath
	 * @param string $search_pattern (must escape /)
	 *
	 * @throws Exception
	 */
	public function __construct( string $directory, string $search_pattern ) {
		$this->def_classes = [];
		$this->directory = realpath($directory);
		if (!$this->directory) {
			throw new InvalidArgumentException("Directory does not resolve [$directory]");
		}
		$this->pattern = $search_pattern;
		$this->auto_load();
	}

	/**
	 * calls each class loaded from this object with the function and params
	 * @param $method
	 * @param mixed ...$params
	 *
	 * @return array
	 */
	public function calleach($method,...$params) {

		$rets = [];
		foreach ($this->def_classes as  $page_def_class) {
			$fullname = $page_def_class['fullname'];
			$ret = call_user_func_array( $fullname . "::$method", $params );
			if ($ret===false) {
				throw new InvalidArgumentException("Cannot find a method of [$fullname] [$method]");
			}
			$rets[]= $ret;
		}
		return $rets;

	}

	public function call($classname,$method,...$params) {
		foreach ($this->def_classes as $key => $page_def_class) {
			$class = $page_def_class['class'];
			$namespace = $page_def_class['namespace'];
			if (strcmp($class,$classname) === 0) {
				$fullname = "$namespace\\$class";
				$ret = call_user_func_array( $fullname . "::$method", ...$params );
				if ($ret===false) {
					throw new InvalidArgumentException("Cannot find a method of [$classname] [$method]");
				}
			}
		}
		throw new InvalidArgumentException("Cannot find a class of [$classname]");
	}


	/**
	 * @throws Exception
	 */
	private  function auto_load() {

		$dir = $this->directory;


		$files = self::rsearch($dir,'/'.$this->pattern.'/');

		foreach ($files as $file) {
			try {
				/** @noinspection PhpIncludeInspection */
				require_once($file);
				$this->def_classes[] = self::get_class_name_from_file($file);

			} catch ( Exception $e) {
				continue;
			}
		}


		foreach ($this->def_classes as $key => $page_def_class) {
			$class = $page_def_class['class'];
			$namespace = $page_def_class['namespace'];

			$this->def_classes[$key]['fullname']= $fullname = "$namespace\\$class";

		}

	}


	private  function rsearch($folder, $pattern) {
		$dir = new RecursiveDirectoryIterator($folder);
		$ite = new RecursiveIteratorIterator($dir);
		$files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
		$fileList = array();
		foreach($files as $file) {
			$fileList = array_merge($fileList, $file);
		}
		return $fileList;
	}

	/**
	 * Gets the string class name, if more than one class defined in the file, then returns the last class defined
	 * @param $file
	 * @return array  [class=>'',namespace=>'']
	 * @throws Exception if class cannot be found
	 */
	private  function get_class_name_from_file($file)
	{
		$fp = fopen($file, 'r');
		if (!$fp) {
			throw new InvalidArgumentException("Cannot open a file $file");
		}
		$class = $namespace = $buffer = '';
		$i = 0;
		while (!$class) {
			if (feof($fp)) break;

			$buffer .= fread($fp, 512);
			$tokens = @token_get_all($buffer);

			if (strpos($buffer, '{') === false) continue;

			for (;$i<count($tokens);$i++) {
				if ($tokens[$i][0] === T_NAMESPACE) {
					for ($j=$i+1;$j<count($tokens); $j++) {
						if ($tokens[$j][0] === T_STRING) {
							$namespace .= '\\'.$tokens[$j][1];
						} else if ($tokens[$j] === '{' || $tokens[$j] === ';') {
							break;
						}
					}
				}

				if ($tokens[$i][0] === T_CLASS) {
					for ($j=$i+1;$j<count($tokens);$j++) {
						if ($tokens[$j] === '{') {
							$class = $tokens[$i+2][1];
						}
					}
				}
			}
		}
		if (!$class) {
			throw new Exception("Cannot find a class in $file");
		}
		return ['class' => $class, 'namespace' => $namespace];
	}

	/**
	 * @param object $object
	 * @param string $constant_name
	 *
	 * @return mixed|null
	 */
	public static function constant_value( object $object, string $constant_name ) {
		$class_name = get_class($object); // fully-qualified class name
		try {
			$constant_reflex = new ReflectionClassConstant($class_name, $constant_name);
			$constant_value = $constant_reflex->getValue();
		} /** @noinspection PhpRedundantCatchClauseInspection */ catch ( ReflectionException $e) {
			$constant_value = null;
		}
		return $constant_value;
	}

}