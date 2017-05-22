<?php
namespace Serialization\Base\Json;


/**
 * @skeleton
 */
interface IJsonSerializersContainer
{
	public function __clone();
	public function add(IJsonDataConstructor $serializer): IJsonSerializersContainer;
	
	/**
	 * @param mixed $target
	 * @return IJsonDataConstructor|null
	 */
	public function getSerializerForTarget($target): ?IJsonDataConstructor;
	
	/**
	 * @param mixed $target
	 * @return IJsonDataConstructor|null
	 */
	public function getDeserializerForData($target): ?IJsonDataConstructor;
}