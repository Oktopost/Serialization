<?php
namespace Serialization\Json\Plain;


use Serialization\Base\Json\IPlainSerializer;


class PlainNarrowSerializer implements IPlainSerializer
{
	public function canSerialize($object): bool
	{
		// TODO: Implement canSerialize() method.
	}
	
	/**
	 * @param mixed $object
	 * @param mixed $meta
	 * @return mixed
	 */
	public function serialize($object, &$meta)
	{
		// TODO: Implement serialize() method.
	}
	
	/**
	 * @param mixed $data
	 * @param mixed $meta
	 * @return mixed
	 */
	public function deserialize($data, $meta)
	{
		// TODO: Implement deserialize() method.
	}
}