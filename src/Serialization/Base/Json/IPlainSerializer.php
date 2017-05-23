<?php
namespace Serialization\Base\Json;


interface IPlainSerializer
{
	public function canSerialize($object): bool;
	
	/**
	 * @param mixed $object
	 * @param mixed $meta
	 * @return mixed
	 */
	public function serialize($object, &$meta);
	
	/**
	 * @param mixed $data
	 * @param mixed $meta
	 * @return mixed
	 */
	public function deserialize($data, $meta);
}