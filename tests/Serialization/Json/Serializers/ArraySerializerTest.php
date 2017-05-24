<?php
namespace Serialization\Json\Serializers;


use PHPUnit\Framework\TestCase;


class ArraySerializerTest extends TestCase
{
	public function test_sanity_array()
	{
		$serializer = new ArraySerializer();
		$target = [
			1,
			2, 
			3,
			'a' => 'b',
			'c' => 'd',
			'd' => null
		];
		
		$data = $serializer->serialize($target);
		$data = json_decode(json_encode($data));
		
		$result = $serializer->deserialize($data);
		
		self::assertEquals($target, $result);
	}
	
	public function test_sanity_object()
	{
		$serializer = new ArraySerializer();
		$target = (object)[
			1,
			2, 
			3,
			'a' => 'b',
			'c' => 'd',
			'd' => null
		];
		
		$data = $serializer->serialize($target);
		$data = json_decode(json_encode($data));
		
		$result = $serializer->deserialize($data);
		
		self::assertEquals($target, $result);
	}
}