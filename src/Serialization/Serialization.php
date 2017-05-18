<?php
namespace Serialization;


use Serialization\Base\ISerializer;
use Serialization\Base\ISerialization;


class Serialization implements ISerialization 
{
	
	public function add(ISerializer $serializer): ISerialization
	{
		// TODO: Implement add() method.
	}
	
	/**
	 * Array must contain objects of same type
	 * @param mixed $data
	 * @return bool
	 */
	public function canSerializeArray(array $data): bool
	{
		// TODO: Implement canSerializeArray() method.
	}
	
	/**
	 * @param mixed $data
	 * @return bool
	 */
	public function canDeserializeArray(array $data): bool
	{
		// TODO: Implement canDeserializeArray() method.
	}
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function serializeAll(array $data)
	{
		// TODO: Implement serializeAll() method.
	}
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function deserialize($data)
	{
		// TODO: Implement deserialize() method.
	}
	
	public function canDeserialize(string $data): bool
	{
		// TODO: Implement canDeserialize() method.
	}
	
	/**
	 * @param mixed $data
	 * @return bool
	 */
	public function canSerialize($data): bool
	{
		// TODO: Implement canSerialize() method.
	}
	
	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data): string
	{
		// TODO: Implement serialize() method.
	}
}