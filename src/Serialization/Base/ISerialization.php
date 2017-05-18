<?php
namespace Serialization\Base;


use Serialization\Base\ISerializer;


/**
 * @skeleton
 */
interface ISerialization extends ISerializer
{
	public function add(ISerializer $serializer): ISerialization;
	
	/**
	 * Array must contain objects of same type
	 * @param mixed $data
	 * @return bool
	 */
	public function canSerializeArray(array $data): bool;
	
	/**
	 * @param mixed $data
	 * @return bool
	 */
	public function canDeserializeArray(array $data): bool;
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function serializeAll(array $data);
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function deserialize($data);
}