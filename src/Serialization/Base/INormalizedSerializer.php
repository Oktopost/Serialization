<?php
namespace Serialization\Base;


interface INormalizedSerializer extends ISerializer 
{
	/**
	 * @param mixed $data
	 * @return bool
	 */
	public function canDeserializeData($data): bool;
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function getSerializedData($data);
	
	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function deserializeFromData($data);
}