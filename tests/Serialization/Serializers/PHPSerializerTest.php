<?php
namespace Serialization\Serializers;


use PHPUnit\Framework\TestCase;


class PHPSerializerTest extends TestCase
{
	public function test_sanity()
	{
		$subject = new PHPSerializer();
		$data = ['a' => 'hello world', 2, (object)[1, 2, 3]];
		
		$serialized = $subject->serialize($data);
		
		self::assertTrue(is_string($serialized));
		self::assertEquals($data, $subject->deserialize($serialized));
	}
}