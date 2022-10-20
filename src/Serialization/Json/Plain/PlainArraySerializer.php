<?php
namespace Serialization\Json\Plain;


use Serialization\Base\Json\IPlainSerializer;


class PlainArraySerializer implements IPlainSerializer
{
	public function canSerialize($object): bool
	{
		return is_array($object) || ($object instanceof \stdClass);
	}
	
	/**
	 * @param mixed $object
	 * @param mixed $meta
	 * @return mixed
	 */
	public function serialize($object, &$meta)
	{
		if (is_array($object))
		{
			$meta = 'array';
		}
		else
		{
			$meta = 'object';
		}
		
		return $object;
	}
	
	/**
	 * @param mixed $data
	 * @param mixed $meta
	 * @return mixed
	 */
	public function deserialize($data, $meta)
	{
		if ($meta == 'array')
		{
			return jsondecode_a(jsonencode($data));
		}
		else
		{
			return (object)$data;
		}
	}
}