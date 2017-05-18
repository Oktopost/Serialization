<?php
namespace Serialization\Base;


/**
 * @skeleton
 */
interface ISerializationContainer
{
	public function __clone();
	public function add(INormalizedSerializer $serializer): ISerializationContainer;
	public function getDeserializerForJson(string $target): ?INormalizedSerializer;
	
	/**
	 * @param $target
	 * @return INormalizedSerializer|null
	 */
	public function getSerializerForTarget($target): ?INormalizedSerializer;
	
	/**
	 * @param mixed $target
	 * @return INormalizedSerializer|null
	 */
	public function getDeserializerForData($target): ?INormalizedSerializer;
}