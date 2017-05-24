<?php
namespace Sanity;


use Serialization\Json\Serializers\ArraySerializer;
use Serialization\Json\Serializers\LiteObjectSerializer;
use Serialization\Json\Serializers\PrimitiveSerializer;
use Serialization\Serializers\JsonSerializer;
use Objection\LiteSetup;
use Objection\LiteObject;
use PHPUnit\Framework\TestCase;


class JsonSerializerTest extends TestCase
{
	public function test()
	{
		$serializer = new JsonSerializer();
		
		$serializer
			->add(new PrimitiveSerializer())
			->add(new ArraySerializer())
			->add(new LiteObjectSerializer());
		
		$data = $serializer->serialize('123');
		self::assertEquals('123', $serializer->deserialize($data));
		
		$data = $serializer->serialize(null);
		self::assertEquals(null, $serializer->deserialize($data));
		
		$data = $serializer->serialize([1, 2, 'b' => 'c']);
		self::assertEquals([1, 2, 'b' => 'c'], $serializer->deserialize($data));
		
		$data = $serializer->serialize((object)[1, 2, 'b' => 'c']);
		self::assertEquals((object)[1, 2, 'b' => 'c'], $serializer->deserialize($data));
		
		
		$object = new SanityTest_Helper();
		$object->a = 'hello';
		$object->b = 890;
		
		$data = $serializer->serialize($object);
		$result = $serializer->deserialize($data);
		
		self::assertInstanceOf(SanityTest_Helper::class, $result);
		self::assertEquals($object->a, $result->a);
		self::assertEquals($object->b, $result->b);
	}
}


class SanityTest_Helper extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a' => LiteSetup::createString('abc'),
			'b' => LiteSetup::createInt(123)
		];
	}
}