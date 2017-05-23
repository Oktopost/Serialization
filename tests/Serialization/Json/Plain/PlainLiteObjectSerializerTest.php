<?php
namespace Serialization\Json\Plain;


use PHPUnit\Framework\TestCase;

use Objection\LiteSetup;
use Objection\LiteObject;


class PlainLiteObjectSerializerTest extends TestCase
{
	public function test_canSerialize_LiteObject_ReturnTrue()
	{
		$subject = new PlainLiteObjectSerializer();
		self::assertTrue($subject->canSerialize(new Test_PlainLiteObjectSerializerTest()));
	}
	
	public function test_canSerialize_NotLiteObject_ReturnFalse()
	{
		$subject = new PlainLiteObjectSerializer();
		self::assertFalse($subject->canSerialize($this));
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_deserialize_ObjectIsNotLiteObject_ErrorThrown()
	{
		$subject = new PlainLiteObjectSerializer();
		$meta = __CLASS__;
		$subject->deserialize([], $meta);
	}
	
	public function test_sanity()
	{
		$subject = new PlainLiteObjectSerializer();
		$meta = null;
		
		$object = new Test_PlainLiteObjectSerializerTest();
		$object->a = 'hello world';
		
		$data = $subject->serialize($object, $meta);
		$data = json_decode(json_encode($data));
		
		$result = $subject->deserialize($data, $meta);
		
		self::assertInstanceOf(Test_PlainLiteObjectSerializerTest::class, $result);
		self::assertEquals($result->a, $object->a);
	}
}



class Test_PlainLiteObjectSerializerTest extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a'	=> LiteSetup::createString('abc')
		];
	}
}