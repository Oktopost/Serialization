<?php
namespace Serialization\Base\Json;


interface IJsonDataConstructor
{
	public function canSerialize($object): bool;
	public function canDeserialize($data): bool;
	
	/**
	 * @param mixed $object
	 * @return mixed
	 */
	public function serialize($object);
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function deserialize($data);
}