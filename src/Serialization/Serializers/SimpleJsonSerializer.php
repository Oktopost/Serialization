<?php
namespace Serialization\Serializers;


use Serialization\Base\ISerializer;


/**
 * @note Do not use this class with unchecked input!
 * @link http://php.net/manual/en/function.serialize.php
 * @link http://php.net/manual/en/function.unserialize.php
 */
class SimpleJsonSerializer implements ISerializer  
{
	/**
	 * @param string $data
	 * @return mixed
	 */
	public function deserialize(string $data)
	{
		return jsondecode($data);
	}
	
	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data): string
	{
		return jsonencode($data);
	}
}