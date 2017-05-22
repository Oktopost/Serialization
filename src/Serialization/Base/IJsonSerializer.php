<?php
namespace Serialization\Base;


use Serialization\Base\Json\IJsonDataConstructor;


interface IJsonSerializer extends ISerializer 
{
	/**
	 * @param IJsonDataConstructor $constructor
	 * @return static|IJsonSerializer
	 */
	public function add(IJsonDataConstructor $constructor): IJsonSerializer;
	
	
	/**
	 * Note that serializeAll must be used with the result of this function and not serialize.
	 * @param string $data Must be a json of an array
	 * @return mixed
	 */
	public function deserializeAll(string $data);
	
	/**
	 * Note that only the result of deserializeAll must be passed to this function and not deserialize.
	 * @param array $data
	 * @return string
	 */
	public function serializeAll(array $data): string;
}