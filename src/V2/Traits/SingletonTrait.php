<?php
declare(strict_types=1);

namespace Amocrmapi\V2\Traits;

/**
 * Singleton realisation for api classes
 */
trait SingletonTrait
{
    /**
     * @var null
     */
    private static $instance = null;

    /**
     * Default singleton method
     *
     * @return SingletonTrait
     */
    public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

    /**
     * Block creating objects instead of getInstance method
     */
    public function __clone() {}

    /**
     * Block creating objects instead of getInstance method
     */
    public function __sleep() {}

    /**
     * Block creating objects instead of getInstance method
     */
    public function __wakeup() {}

    /**
     * Block creating objects instead of getInstance method
     */
    public function __construct() {}
}