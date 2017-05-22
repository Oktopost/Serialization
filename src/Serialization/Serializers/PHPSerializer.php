<?php
namespace Serialization\Serializers;


use Serialization\Base\ISerializer;


/**
 * @note Do not use this class with unchecked input!
 * @link http://php.net/manual/en/function.serialize.php
 * @link http://php.net/manual/en/function.unserialize.php
 */
class PHPSerializer implements ISerializer  
{
	/**
	 * @param string $data
	 * @return mixed
	 */
	public function deserialize(string $data)
	{
		return unserialize($data);
	}
	
	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data): string
	{
		return serialize($data);
	}
}