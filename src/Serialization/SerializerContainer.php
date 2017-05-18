<?php
namespace Serialization;


use Serialization\Base\ISerializerContainer;
use Serialization\Exceptions\SerializationException;


class SerializerContainer implements ISerializerContainer
{
	/** @var ISerializer[] */
	private $byType = [];
	
	/** @var ISerializer[] */
	private $serializers = [];
	
	
	public function add(ISerializer $serializer): ISerializerContainer
	{
		$name = $serializer->serializerName();
		
		if (isset($this->byType[$name]))
			throw new SerializationException("Serializer <$name> already defined");
		
		$this->byType[$name] = $serializer;
		$this->serializers[] = $serializer;
		
		return $this;
	}

	public function getByType(string $type): ISerializer
	{
		if (!isset($this->byType[$type]))
			throw new SerializationException("There is not serializer of type <$type> defined");
		
		return $this->byType[$type];
	}

	public function getForTarget($target): ISerializer
	{
		foreach ($this->serializers as $serializer)
		{
			if ($serializer->canSerialize($target))
				return $serializer;
		}
		
		throw new SerializationException('There is not serializer defined for passed target');
	}
	
	
	public function __clone() {}
}