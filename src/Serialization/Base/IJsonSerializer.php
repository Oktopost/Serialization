<?php
namespace Serialization\Base;


use Serialization\Base\Json\IJsonDataConstructor;


/**
 * @skeleton
 */
interface IJsonSerializer extends ISerializeManager 
{
	/**
	 * @param IJsonDataConstructor $constructor
	 * @return static|IJsonSerializer
	 */
	public function add(IJsonDataConstructor $constructor): IJsonSerializer;
}