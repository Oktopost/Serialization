<?php
namespace Serialization\Serializers;


use Serialization\Scope;
use Serialization\Exceptions;
use Serialization\Base\IJsonSerializer;
use Serialization\Base\Json\IJsonDataConstructor;
use Serialization\Base\Json\IJsonSerializersContainer;
use Serialization\Json\JsonSerializerDataConstructor;


class JsonSerializer implements IJsonSerializer
{
	/** @var IJsonSerializersContainer */
	private $container;
	
	
	private function validateIsJsonArray($result, string $data)
	{
		if (is_null($result))
			throw new Exceptions\InvalidJsonException($data);
		else if (!is_array($result))
			throw new Exceptions\SerializationException('Data must be a serialized array!');
	}
	
	private function validateSerializerFound(?IJsonDataConstructor $serializer)
	{
		if (is_null($serializer))
			throw new Exceptions\SerializationException('Serializer not defined for target');
	}
	
	private function fromJson($data)
	{
		$result = jsondecode($data);
		
		if (is_null($result) && $data !== 'null')
			throw new Exceptions\InvalidJsonException($data);
		
		return $result;
	}
	
	private function toJson($data): string
	{
		$result = jsonencode($data);
		
		if ($result === false)
			throw new Exceptions\CouldNotBeConvertedToJsonException();
		
		return $result;
	}
	
	
	public function __construct()
	{
		$this->container = Scope::skeleton(IJsonSerializersContainer::class);
	}


	/**
	 * @param IJsonDataConstructor $constructor
	 * @return static|IJsonSerializer
	 */
	public function add(IJsonDataConstructor $constructor): IJsonSerializer
	{
		$this->container->add($constructor);
		return $this;
	}
	
	/**
	 * Note that serializeAll must be used with the result of this function and not serialize.
	 * @param string $data Must be a json of an array
	 * @return mixed
	 */
	public function deserializeAll(string $data)
	{
		$result = [];
		$decoded = jsondecode($data);
		
		$this->validateIsJsonArray($decoded, $data);
		
		foreach ($decoded as $item)
		{
			$serializer = $this->container->getDeserializerForData($item);
			$this->validateSerializerFound($serializer);
			$result[] = $serializer->deserialize($item);
		}
		
		return $result;
	}
	
	/**
	 * Note that only the result of deserializeAll must be passed to this function and not deserialize.
	 * @param array $data
	 * @return string
	 */
	public function serializeAll(array $data): string
	{
		$result = [];
		
		foreach ($data as $item)
		{
			$serializer = $this->container->getSerializerForTarget($item);
			$this->validateSerializerFound($serializer);
			$result[] = $serializer->serialize($item);
		}
		
		return jsonencode($result);
	}
	
	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data): string
	{
		$serializer = $this->container->getSerializerForTarget($data);
		$this->validateSerializerFound($serializer);
		$object = $serializer->serialize($data);
		return $this->toJson($object);
	}
	
	/**
	 * @param string $data
	 * @return mixed
	 */
	public function deserialize(string $data)
	{
		$object = $this->fromJson($data);
		$serializer = $this->container->getDeserializerForData($object);
		$this->validateSerializerFound($serializer);
		return $serializer->deserialize($object);
	}

	public function asDataConstructor(): IJsonDataConstructor
	{
		return new JsonSerializerDataConstructor($this->container);
	}
}