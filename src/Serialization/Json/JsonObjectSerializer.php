<?php
namespace Serialization\Json;


use Serialization\Base\Json\IJsonDataConstructor;


abstract class JsonObjectSerializer implements IJsonDataConstructor 
{
	private const NAME_IDENTIFIER_TOKEN = '__jos_name__';
	private const DATA_IDENTIFIER_TOKEN = '__jos_data__';
	
	
	private $name;
	
	
	/**
	 * @param mixed $object
	 * @return mixed
	 */
	protected abstract function serializeObject($object);
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	protected abstract function deserializeData($data);
	
	
	public function __construct(string $name)
	{
		$this->name = $name;
	}
	
	
	public function canDeserialize($data): bool
	{
		$nameField = self::NAME_IDENTIFIER_TOKEN;
		$nameData = self::NAME_IDENTIFIER_TOKEN;
		
		return ($data instanceof \stdClass &&
			isset($data->$nameField) &&
			
			isset($data->$nameData) &&
			$data->$nameField === $this->name);
	}
	
	/**
	 * @param mixed $object
	 * @return mixed
	 */
	public function serialize($object)
	{
		return (object)[
			self::NAME_IDENTIFIER_TOKEN => $this->name,
			self::DATA_IDENTIFIER_TOKEN => $this->serializeObject($object)
		];
	}
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function deserialize($data)
	{
		$dataToken = self::DATA_IDENTIFIER_TOKEN;
		$objectData = $data->{$dataToken} ?? null;
		return $this->deserializeData($objectData);
	}
}