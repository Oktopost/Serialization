<?php
namespace Serialization;


interface ISerializer
{
	public function serializerName(): string;

	/**
	 * @param mixed $data
	 * @return bool
	 */
	public function canSerialize($data): bool;
	
	/**
	 * @param mixed $data
	 * @param mixed|null $metadata
	 * @return mixed
	 */
	public function serialize($data, &$metadata);
	
	/**
	 * @param mixed $data
	 * @param mixed|null $metadata
	 * @return mixed
	 */
	public function deserialize($data, $metadata);
}