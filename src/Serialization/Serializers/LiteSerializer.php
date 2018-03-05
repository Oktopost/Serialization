<?php
namespace Serialization\Serializers;


use Serialization\Exceptions;


use Serialization\Base\ISerializer;


class LiteSerializer implements ISerializer  
{
	private function clearEmpty($data)
	{
		if (is_object($data))
			$result = $this->clearEmptyObject($data);
		else if (is_array($data))
			$result = $this->clearEmptyArray($data);
		else
			throw new Exceptions\CouldNotBeConvertedToJsonException();
		
		if ($result === false)
			throw new Exceptions\CouldNotBeConvertedToJsonException();
		
		return $result;
	}
	
	private function checkNotEmpty($data): bool
	{
		return !empty((array)$data);
	}
	
	private function clearEmptyObject(\stdClass $object): \stdClass
	{
		$result = new \stdClass;
		
		foreach ($object as $key=>$value)
		{
			if (is_object($value))
			{
				$child = $this->clearEmpty($value);
				
				if ($this->checkNotEmpty($child)) $result->$key = $child;
			}
			else if (is_array($value))
			{
				$child = [];
				
				foreach( $value as $subKey=>$subValue )
				{
					$subChild = $this->clearEmpty($subValue);
					if ($this->checkNotEmpty($subChild)) $child[] = $subChild;
				}
				
				if ($this->checkNotEmpty($child)) $result->$key = $child;
			}
			else if (!is_null($value)) $result->$key = $value;
		}
		
		return $result;
	}
	
	private function clearEmptyArray(array $array): array
	{
		$result = [];
		
		foreach ($array as $key=>$value)
		{
			if (is_object($value))
			{
				$child = $this->clearEmpty($value);
				
				if ($this->checkNotEmpty($child)) $result[$key] = $child;
			}
			else if (is_array($value))
			{
				$child = [];
				
				foreach( $value as $subKey=>$subValue )
				{
					$subChild = $this->clearEmpty($subValue);
					if ($this->checkNotEmpty($subChild)) $child[] = $subChild;
				}
				
				if ($this->checkNotEmpty($child)) $result[$key][] = $child;
			}
			else if (!is_null($value)) $result[$key] = $value;
		}
		
		return $result;
	}
	
	/**
	 * @param string $data
	 * @return mixed
	 */
	public function deserialize(string $data)
	{
		$result = json_decode($data);
		
		if (is_null($result) && $data !== 'null')
			throw new Exceptions\InvalidJsonException($data);
		
		return $result;
	}
	
	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data): string
	{
		$result = json_encode($this->clearEmpty($data));
		
		if ($result === false)
			throw new Exceptions\CouldNotBeConvertedToJsonException();
		
		return $result;
	}
}