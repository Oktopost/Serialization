<?php
namespace Serialization;


use Serialization\Base\Encoder\IMeta;


interface IEncoder
{
	public function serializerName(): string;

	/**
	 * @param mixed $data
	 * @return bool
	 */
	public function canSerialize($data): bool;
	
	/**
	 * @param mixed $data
	 * @param IMeta $metadata
	 * @return mixed
	 */
	public function encode($data, IMeta $metadata);
	
	/**
	 * @param mixed $data
	 * @param IMeta $metadata
	 * @return mixed
	 */
	public function decode($data, IMeta $metadata);
}