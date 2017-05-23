<?php
namespace Serialization\Json\Plain;


use Objection\LiteObject;
use Serialization\Base\Json\IPlainSerializer;
use Serialization\Exceptions\SerializationException;


class PlainLiteObjectSerializer implements IPlainSerializer
{
	public function canSerialize($object): bool
	{
		return $object instanceof LiteObject;
	}

	/**
	 * @param mixed|LiteObject $object
	 * @param mixed $meta
	 * @return mixed
	 */
	public function serialize($object, &$meta)
	{
		$meta = get_class($object);
		return $object->toArray();
	}

	/**
	 * @param mixed $data
	 * @param mixed $meta
	 * @return mixed
	 */
	public function deserialize($data, $meta)
	{
		/** @var LiteObject $object */
		$object = new $meta;
		
		if (!($object instanceof LiteObject))
			throw new SerializationException("Class named $meta is not a LiteObject class");
		
		return $object->fromArray((array)$data);
	}
}