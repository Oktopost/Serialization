<?php
require_once __DIR__ . '/../vendor/autoload.php';



use \Skeleton\UnitTestSkeleton;

use \Serialization\Scope;


class UnitTestSkeletonMock 
{
	/** @var \Skeleton\UnitTestSkeleton */
	private static $testSkeleton = null;
	
	
	public static function set(string $interface, $item)
	{
		if (!self::$testSkeleton)
			self::$testSkeleton = new UnitTestSkeleton(Scope::skeleton());
		
		self::$testSkeleton->override($interface, $item);
		return $item;
	}
	
	public static function reset()
	{
		if (self::$testSkeleton)
			self::$testSkeleton->clear();
	}
	
	public static function create($class)
	{
		if (!self::$testSkeleton)
			self::$testSkeleton = new UnitTestSkeleton(Scope::skeleton());
		
		return Scope::skeleton()->load($class);
	}
}