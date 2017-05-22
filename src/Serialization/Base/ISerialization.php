<?php
namespace Serialization\Base;


/**
 * @skeleton
 */
interface ISerialization extends INormalizedSerializer
{
	public function add(INormalizedSerializer $serializer): ISerialization;
	
	
	/**
	 * Array must contain objects of same type
	 */
	public function canSerializeArray(array $data): bool;
	
	/**
	 * Data must be a previously serialized array.
	 * @param string $data Serialized array of items.
	 * @return bool
	 */
	public function canDeserializeArray(string $data): bool;
	
	public function serializeAll(array $data): string;
	
	/**
	 * Data must be a previously serialized array.
	 * @param string $data Serialized array of items.
	 * @return array
	 */
	public function deserializeAll(string $data): array;
}