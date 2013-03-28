<?php
/**
 * Klein (klein.php) - A lightning fast router for PHP
 *
 * @author      Chris O'Hara <cohara87@gmail.com>
 * @author      Trevor Suarez (Rican7) (contributor and v2 refactorer)
 * @copyright   (c) Chris O'Hara
 * @link        https://github.com/chriso/klein.php
 * @license     MIT
 */

namespace Klein\Tests;


use \PHPUnit_Framework_TestCase;

use \Klein\Klein;


/**
 * AbstractKleinTest
 *
 * Base test class for PHP Unit testing
 * 
 * @uses PHPUnit_Framework_TestCase
 * @abstract
 * @package Klein\Tests
 */
abstract class AbstractKleinTest extends PHPUnit_Framework_TestCase {

	/**
	 * The automatically created test Klein instance
	 * (for easy testing and less boilerplate)
	 * 
	 * @var \Klein\Klein;
	 * @access protected
	 */
	protected $klein_app;


	/**
	 * Setup our test
	 * (runs before each test)
	 * 
	 * @access protected
	 * @return void
	 */
	protected function setUp() {
		// Create a new klein app,
		// since we need one pretty much everywhere
		$this->klein_app = new Klein();
	}

	/**
	 * Runs a callable and asserts that the output from the executed callable
	 * matches the passed in expected output
	 * 
	 * @param mixed $expected The expected output
	 * @param callable $callback The callable function
	 * @param string $message (optional) A message to display if the assertion fails
	 * @access protected
	 * @return void
	 */
	protected function assertOutputSame( $expected, $callback, $message = '' ) {
		// Start our output buffer so we can capture our output
	    ob_start();

	    call_user_func($callback);

		// Grab our output from our buffer
	    $out = ob_get_contents();

		// Clean our buffer and destroy it, so its like no output ever happened. ;)
	    ob_end_clean();

		// Use PHPUnit's built in assertion
	    $this->assertSame( $expected, $out, $message );
	}

	/**
	 * Loads externally defined routes under the filename's namespace
	 * 
	 * @param Klein $app_context The application context to attach the routes to
	 * @access protected
	 * @return array
	 */
	protected function loadExternalRoutes( Klein $app_context = null ) {
		// Did we not pass an instance?
		if ( is_null( $app_context ) ) {
			$app_context = $this->klein_app ?: new Klein();
		}

		$route_directory = __DIR__ . '/routes/';
		$route_files = scandir( $route_directory );
		$route_namespaces = array();

		foreach( $route_files as $file ) {
			if ( is_file( $route_directory . $file ) ) {
				$route_namespace = '/' . basename( $file, '.php' );
				$route_namespaces[] = $route_namespace;

				$app_context->with( $route_namespace, $route_directory . $file );
			}
		}

		return $route_namespaces;
	}

} // End class AbstractKleinTest
