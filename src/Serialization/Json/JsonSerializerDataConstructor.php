<?php
namespace Serialization\Json;


use Serialization\Base\Json\IJsonDataConstructor;
use Serialization\Base\Json\IJsonSerializerDataConstructor;
use Serialization\Base\Json\IJsonSerializersContainer;
use Serialization\Exceptions\SerializationException;


class JsonSerializerDataConstructor implements IJsonSerializerDataConstructor
{
	/** @var IJsonSerializersContainer */
	private $container;
	
	
	private function validateSerializerFound(?IJsonDataConstructor $serializer)
	{
		if (is_null($serializer))
			throw new SerializationException('Serializer not defined for target');
	}
	
	
	public function __construct(IJsonSerializersContainer $container)
	{
		$this->container = $container;
	}
	

	public function canSerialize($object): bool
	{
		return (bool)$this->container->getSerializerForTarget($object);
	}

	public function canDeserialize($data): bool
	{
		return (bool)$this->container->getDeserializerForData($data);
	}

	public function serialize($object)
	{
		$serializer = $this->container->getSerializerForTarget($object);
		$this->validateSerializerFound($serializer);
		$object = $serializer->serialize($object);
		
		return $object;
	}

	public function deserialize($object)
	{
		$serializer = $this->container->getDeserializerForData($object);
		$this->validateSerializerFound($serializer);
		
		return $serializer->deserialize($object);
	}
}