<?php
namespace Serialization\Json\Serializers;


use Serialization\Base\Json\IPlainSerializer;
use Serialization\Base\Json\IJsonDataConstructor;


class TypedSerializer implements IJsonDataConstructor
{
	private const TYPE_TOKEN = 'typed-serializer-name';
	private const META_TOKEN = 'meta';
	private const DATA_TOKEN = 'data';
	
	
	private $name;
	
	/** @var IPlainSerializer */
	private $child;
	
	
	public function __construct(string $name, IPlainSerializer $plain)
	{
		$this->child = $plain;
		$this->name = $name;
	}


	public function canSerialize($object): bool
	{
		return $this->child->canSerialize($object);
	}

	public function canDeserialize($data): bool
	{
		if (!($data instanceof \stdClass))
			return false;
		
		$typeToken = self::TYPE_TOKEN;
		$dataToken = self::DATA_TOKEN;
		
		if (!property_exists($data, $typeToken) ||
			!property_exists($data, $dataToken) || 
			$data->$typeToken !== $this->name)
		{
			return false;
		}
		
		return true;
	}

	/**
	 * @param mixed $object
	 * @return mixed
	 */
	public function serialize($object)
	{
		$meta = null;
		$data = $this->child->serialize($object, $meta);
		$result = [
			self::TYPE_TOKEN	=> $this->name,
			self::DATA_TOKEN	=> $data
		];
		
		if (!is_null($meta))
		{
			$result[self::META_TOKEN] = $meta;
		}
		
		return $result;
	}

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function deserialize($data)
	{
		$dataToken = self::DATA_TOKEN;
		$metaToken = self::META_TOKEN;
		
		$data = $data->$dataToken;
		$meta = $data->$metaToken;
		
		return $this->child->deserialize($data, $meta);
	}
}