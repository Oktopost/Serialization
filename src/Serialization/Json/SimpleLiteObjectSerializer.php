<?php
namespace Serialization\Json;


use Objection\LiteObject;
use Serialization\Exceptions\SerializationException;


class SimpleLiteObjectSerializer extends JsonObjectSerializer
{
	/**
	 * @param LiteObject|mixed $object
	 * @return mixed
	 */
	protected function serializeObject($object)
	{
		return (object)[
			'name'      => get_class($object),
			'fields'    => $object->toArray()
		];
	}
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	protected function deserializeData($data)
	{
		$className = $data->name ?? null;
		$data = $data->fields ?? null;
		
		if (is_null($className) || !is_array($data))
			throw new SerializationException('Invalid data received: ' . json_encode($data));
		
		if (!class_exists($className))
			throw new SerializationException('Class named ' . $className . ' was not found!');
		
		$object = new $className;
		
		if (!$object instanceof LiteObject)
			throw new SerializationException('Object is not of LiteObject type: ' . $className);
		
		return $object->fromArray($data);
	}
	
	
	/**
	 * @param LiteObject|mixed $object
	 * @return bool
	 */
	public function canSerialize($object): bool
	{
		return $object instanceof LiteObject;
	}
}