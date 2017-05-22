<?php
namespace Serialization\Base;


interface ISerializer
{
	/**
	 * @param string $data
	 * @return mixed
	 */
	public function deserialize(string $data);
	
	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data): string;
}