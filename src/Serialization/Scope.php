<?php
namespace Serialization;


require_once __DIR__ . '/SkeletonSetup.php';


use Skeleton\Skeleton;


class Scope
{
	use \Objection\TStaticClass;

	
	/** @var Skeleton */
	private static $skeleton;
	

	/**
	 * @param string|null $interface
	 * @return mixed
	 */
	public static function skeleton(?string $interface = null)
	{
		if (!self::$skeleton)
			self::$skeleton = SkeletonSetup::create();
		
		if (!$interface) 
			return self::$skeleton;
		
		return self::$skeleton->get($interface);
	}
}