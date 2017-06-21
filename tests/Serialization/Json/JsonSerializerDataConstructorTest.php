<?php
namespace Serialization\Json;


use Objection\LiteSetup;
use Objection\LiteObject;
use PHPUnit\Framework\TestCase;
use Serialization\Json\Serializers\LiteObjectSerializer;
use Serialization\Serializers\JsonSerializer;


class JsonSerializerDataConstructorTest extends TestCase
{
	public function test_sanity()
	{
		$serializer = new JsonSerializer();
		
		$serializer->add(new LiteObjectSerializer());
		
		$constructor = $serializer->asDataConstructor();
		
		$subject = new LiteObjectSerializerTest_Helper();
		
		$constructor->canSerialize($subject);
		
		$subject->a = 'hello world';
		$subject->b = 9802;
		$subject->c = [1, 2, 3];
		
		$data = $constructor->serialize($subject);
		
		$constructor->canDeserialize($data);
		
		$data = json_decode(json_encode($data));
		
		$result = $constructor->deserialize($data);
		
		self::assertInstanceOf(LiteObjectSerializerTest_Helper::class, $result);
		self::assertEquals($subject->a, $result->a);
		self::assertEquals($subject->b, $result->b);
		self::assertEquals($subject->c, $result->c);
	}
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_serialize_NoSerializer_ExceptionThrown()
	{
		$serializer = new JsonSerializer();
		$constructor = $serializer->asDataConstructor();
		
		$subject = new LiteObjectSerializerTest_Helper();
		
		$data = $constructor->serialize($subject);
	}
}


class LiteObjectSerializerTest_Helper extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a'	=> LiteSetup::createString('abc'),
			'b'	=> LiteSetup::createInt(123),
			'c' => LiteSetup::createArray(['a', 'b'])
		];
	}
}