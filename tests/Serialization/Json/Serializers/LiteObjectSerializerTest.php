<?php
namespace Serialization\Json\Serializers;


use Objection\LiteSetup;
use Objection\LiteObject;
use PHPUnit\Framework\TestCase;


class LiteObjectSerializerTest extends TestCase
{
	public function test_sanity()
	{
		$serializer = new LiteObjectSerializer();
		$subject = new LiteObjectSerializerTest_Helper();
		
		$subject->a = 'hello world';
		$subject->b = 9802;
		$subject->c = [1, 2, 3];
		
		$data = $serializer->serialize($subject);
		$data = json_decode(json_encode($data));
		
		$result = $serializer->deserialize($data);
		
		self::assertInstanceOf(LiteObjectSerializerTest_Helper::class, $result);
		self::assertEquals($subject->a, $result->a);
		self::assertEquals($subject->b, $result->b);
		self::assertEquals($subject->c, $result->c);
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