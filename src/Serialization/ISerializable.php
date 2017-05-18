<?php
namespace Serialization;


interface ISerializable
{
	/**
	 * @param mixed|null $metadata
	 * @return mixed
	 */
	public function serialize(&$metadata);

	/**
	 * @param mixed $data
	 * @param mixed $metadata
	 * @return static
	 */
	public static function deserialize($data, $metadata);
}