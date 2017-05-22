<?php
namespace Serialization\Base;


interface ISerializer
{
	public function canDeserialize(string $data): bool;
	
	/**
	 * @param string $data
	 * @return mixed
	 */
	public function deserialize(string $data);
	
	/**
	 * @param mixed $data
	 * @return bool
	 */
	public function canSerialize($data): bool;
	
	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data): string;
}