<?php
namespace Serialization\Json;


use Serialization\Base\Json\IJsonDataConstructor;
use Serialization\Base\Json\IJsonSerializersContainer;


class JsonSerializersContainer implements IJsonSerializersContainer
{
	/** @var IJsonDataConstructor[] */
	private $serializers = [];
	
	
	public function __clone() {}
	

	public function add(IJsonDataConstructor $serializer): IJsonSerializersContainer
	{
		$this->serializers[] = $serializer;
		return $this;
	}
	
	/**
	 * @param mixed $target
	 * @return IJsonDataConstructor|null
	 */
	public function getSerializerForTarget($target): ?IJsonDataConstructor
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
	
	/**
	 * @param mixed $target
	 * @return IJsonDataConstructor|null
	 */
	public function getDeserializerForData($target): ?IJsonDataConstructor
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
}