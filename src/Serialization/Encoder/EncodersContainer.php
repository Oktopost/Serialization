<?php
namespace Serialization\Encoder;


use Serialization\Base\Encoder\IEncodersContainer;
use Serialization\Exceptions\SerializationException;
use Serialization\Base\Encoder\IEncoder;


class EncodersContainer implements IEncodersContainer
{
	/** @var IEncoder[] */
	private $byType = [];
	
	/** @var IEncoder[] */
	private $serializers = [];
	
	
	public function add(IEncoder $serializer): IEncodersContainer
	{
		$name = $serializer->serializerName();
		
		if (isset($this->byType[$name]))
			throw new SerializationException("Serializer <$name> already defined");
		
		$this->byType[$name] = $serializer;
		$this->serializers[] = $serializer;
		
		return $this;
	}

	public function getByType(string $type): IEncoder
	{
		if (!isset($this->byType[$type]))
			throw new SerializationException("There is not serializer of type <$type> defined");
		
		return $this->byType[$type];
	}

	public function getForTarget($target): IEncoder
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