<?php
namespace Serialization\Json\Serializers;


use Serialization\Base\Json\IJsonDataConstructor;


class PrimitiveSerializer implements IJsonDataConstructor 
{
	public function canSerialize($object): bool
	{
		return (is_string($object) || 
			(
				!is_object($object) && 
				!is_array($object) && 
				!is_callable($object)
			)
		);
	}
	
	public function canDeserialize($data): bool
	{
		return (is_string($data) || 
			(
				!is_object($data) && 
				!is_array($data) && 
				!is_callable($data)
			)
		);
	}
	
	/**
	 * @param mixed $object
	 * @return mixed
	 */
	public function serialize($object)
	{
		return $object;
	}
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function deserialize($data)
	{
		return $data;
	}
}