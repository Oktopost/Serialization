<?php
namespace Serialization\Json\Plain;


use Objection\LiteObject;
use Serialization\Base\Json\IPlainSerializer;


class PlainNarrowSerializer implements IPlainSerializer
{
	private function clearEmpty($data)
	{
		if ($this->canSerialize($data))
			return $this->clearEmptyObject($data);
		else
			return [];
	}
	
	private function checkNotEmpty($data): bool
	{
		return !empty((array)$data);
	}
	
	/**
	 * @param mixed|LiteObject $object
	 * @return mixed
	 */
	private function clearEmptyObject($object)
	{
		$result = $object->toArray();
		
		foreach ($result as $key=>$value)
		{
			if ($this->canSerialize($value))
			{
				$child = $this->clearEmptyObject($value);
				
				if ($this->checkNotEmpty($child)) $result[$key] = $child;
				else $result[$key] = [];
			}
			else if (!$this->checkNotEmpty($value)) unset ($result[$key]);
		}
		
		return $result;
	}
	
	
	public function canSerialize($object): bool
	{
		return $object instanceof LiteObject;
	}
	
	/**
	 * @param mixed|LiteObject $object
	 * @param mixed $meta
	 * @return mixed
	 */
	public function serialize($object, &$meta = '')
	{
		$meta = get_class($object);
		
		return $this->clearEmpty($object);
		// TODO: Implement serialize() method.
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
		
		// TODO: Implement deserialize() method.
	}
}