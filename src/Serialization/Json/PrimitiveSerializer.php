<?php
namespace Serialization\Json;


use Serialization\Base\Json\IJsonDataConstructor;


class PrimitiveSerializer implements IJsonDataConstructor 
{
	public function canSerialize($object): bool
	{
		return false;
	}
	
	public function canDeserialize($data): bool
	{
		return false;
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