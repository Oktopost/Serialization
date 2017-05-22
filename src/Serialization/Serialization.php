<?php
namespace Serialization;


use Serialization\Base\ISerializer;
use Serialization\Base\ISerialization;
use Serialization\Base\INormalizedSerializer;
use Serialization\Exceptions\InvalidJsonException;
use Serialization\Exceptions\SerializationException;
use Serialization\Exceptions\CouldNotBeConvertedToJsonException;


/**
 * @autoload
 */
class Serialization implements ISerialization
{
	/**
	 * @autoload
	 * @var \Serialization\Base\ISerializationContainer
	 */
	private $container;
	
	
	private function validateIsJsonArray($result, string $data)
	{
		if (is_null($result))
			throw new InvalidJsonException($data);
		else if (!is_array($result))
			throw new SerializationException('Data must be a serialized array!');
	}
	
	private function validateSerializerFound(?ISerializer $serializer)
	{
		if (is_null($serializer))
			throw new SerializationException('Serializer not defined for target');
	}
	
	private function toJson($data): string
	{
		$result = json_encode($data);
		
		if (is_null($result))
			throw new CouldNotBeConvertedToJsonException();
		
		return $result;
	}
	
	
	public function add(INormalizedSerializer $serializer): ISerialization
	{
		$this->container->add($serializer);
		return $this;
	}
	
	
	public function canDeserialize(string $data): bool
	{
		return !is_null($this->container->getDeserializerForJson($data));
	}
	
	/**
	 * @param string $data
	 * @return mixed
	 */
	public function deserialize(string $data)
	{
		$serializer = $this->container->getDeserializerForJson($data);
		$this->validateSerializerFound($serializer);
		return $serializer->deserialize($data);
	}
	
	/**
	 * @param mixed $data
	 * @return bool
	 */
	public function canSerialize($data): bool
	{
		return !is_null($this->container->getSerializerForTarget($data));
	}
	
	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data): string
	{
		$serializer = $this->container->getSerializerForTarget($data);
		$this->validateSerializerFound($serializer);
		return $serializer->serialize($data);
	}
	
	
	/**
	 * @param mixed $data
	 * @return bool
	 */
	public function canDeserializeData($data): bool
	{
		return !is_null($this->container->getDeserializerForData($data));
	}
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function getSerializedData($data)
	{
		$serializer = $this->container->getSerializerForTarget($data);
		$this->validateSerializerFound($serializer);
		return $serializer->getSerializedData($data);
	}
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function deserializeFromData($data)
	{
		$serializer = $this->container->getDeserializerForData($data);
		$this->validateSerializerFound($serializer);
		return $serializer->deserializeFromData($data);
	}
	
	
	/**
	 * Array must contain objects of same type
	 * @param mixed $data
	 * @return bool
	 */
	public function canSerializeArray(array $data): bool
	{
		foreach ($data as $item)
		{
			if (is_null($this->container->getSerializerForTarget($item)))
				return false;
		}
		
		return true;
	}
	
	/**
	 * Data must be a previously serialized array.
	 * @param string $data Serialized array of items.
	 * @return bool
	 */
	public function canDeserializeArray(string $data): bool
	{
		$result = json_decode($data);
		
		$this->validateIsJsonArray($result, $data);
		
		foreach ($result as $item)
		{
			if (is_null($this->container->getDeserializerForData($item)))
				return false;
		}
		
		return true;
	}
	
	public function serializeAll(array $data): string
	{
		$result = [];
		
		foreach ($data as $item)
		{
			$result[] = $this->getSerializedData($item);
		}
		
		return $this->toJson($result);
	}
	
	/**
	 * Data must be a previously serialized array.
	 * @param string $data Serialized array of items.
	 * @return array
	 */
	public function deserializeAll(string $data): array
	{
		$result = [];
		$decoded = json_decode($data);
		
		$this->validateIsJsonArray($decoded, $data);
		
		foreach ($decoded as $item)
		{
			$result[] = $this->deserializeFromData($item);
		}
		
		return $result;
	}
}