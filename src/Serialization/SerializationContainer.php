<?php
namespace Serialization;


use Serialization\Base\INormalizedSerializer;
use Serialization\Base\ISerializationContainer;


class SerializationContainer implements ISerializationContainer
{
	/** @var INormalizedSerializer[] */
	private $serializers = [];
	
	
	public function __clone() {}
	

	public function add(INormalizedSerializer $serializer): ISerializationContainer
	{
		$this->serializers[] = $serializer;
		return $this;
	}

	public function getSerializerForTarget($target): ?INormalizedSerializer
	{
		foreach ($this->serializers as $serializer)
		{
			if ($serializer->canSerialize($target))
			{
				return $serializer;
			}
		}
		
		return null;
	}

	public function getDeserializerForJson(string $target): ?INormalizedSerializer
	{
		foreach ($this->serializers as $serializer)
		{
			if ($serializer->canDeserialize($target))
			{
				return $serializer;
			}
		}
		
		return null;
	}
	
	
	/**
	 * @param mixed $target
	 * @return INormalizedSerializer|null
	 */
	public function getDeserializerForData($target): ?INormalizedSerializer
	{
		foreach ($this->serializers as $serializer)
		{
			if ($serializer->canDeserializeData($target))
			{
				return $serializer;
			}
		}
		
		return null;
	}
}