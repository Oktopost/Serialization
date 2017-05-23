<?php
namespace Serialization\Json\Serializers;


use PHPUnit\Framework\TestCase;


class PrimitiveSerializerTest extends TestCase
{
	public function test_canSerialize_ValidPrimitive_ReturnTrue()
	{
		$primitive = new PrimitiveSerializer();
		
		self::assertTrue($primitive->canSerialize(1));
		self::assertTrue($primitive->canSerialize('a'));
		self::assertTrue($primitive->canSerialize(0.03));
		self::assertTrue($primitive->canSerialize(true));
		self::assertTrue($primitive->canSerialize(null));
	}
	
	public function test_canSerialize_Array_ReturnFalse()
	{
		$primitive = new PrimitiveSerializer();
		self::assertFalse($primitive->canSerialize([1]));
	}
	
	public function test_canSerialize_StdClass_ReturnFalse()
	{
		$primitive = new PrimitiveSerializer();
		self::assertFalse($primitive->canSerialize((object)[1]));
	}
	
	public function test_canSerialize_Invalid_ReturnFalse()
	{
		$primitive = new PrimitiveSerializer();
		self::assertFalse($primitive->canSerialize($this));
	}
	
	public function test_canSerialize_Function_ReturnFalse()
	{
		$primitive = new PrimitiveSerializer();
		self::assertFalse($primitive->canSerialize(function() {}));
	}
	
	public function test_canSerialize_FunctionByName_ReturnTrue()
	{
		$primitive = new PrimitiveSerializer();
		self::assertTrue($primitive->canSerialize('Serialization\Json\TestMethodForPrimitiveSerializerTest'));
	}
	
	
	public function test_canDeserialize_ValidPrimitive_ReturnTrue()
	{
		$primitive = new PrimitiveSerializer();
		
		self::assertTrue($primitive->canDeserialize(1));
		self::assertTrue($primitive->canDeserialize('a'));
		self::assertTrue($primitive->canDeserialize(0.03));
		self::assertTrue($primitive->canDeserialize(true));
		self::assertTrue($primitive->canDeserialize(null));
	}
	
	public function test_canDeserialize_Array_ReturnFalse()
	{
		$primitive = new PrimitiveSerializer();
		self::assertFalse($primitive->canDeserialize([1]));
	}
	
	public function test_canDeserialize_StdClass_ReturnFalse()
	{
		$primitive = new PrimitiveSerializer();
		self::assertFalse($primitive->canDeserialize((object)[1]));
	}
	
	public function test_canDeserialize_FunctionByName_ReturnTrue()
	{
		$primitive = new PrimitiveSerializer();
		self::assertTrue($primitive->canSerialize('Serialization\Json\TestMethodForPrimitiveSerializerTest'));
	}
	
	
	public function test_deserialize()
	{
		$primitive = new PrimitiveSerializer();
		
		self::assertSame(1, 		$primitive->deserialize(1));
		self::assertSame('a', 		$primitive->deserialize('a'));
		self::assertSame(0.03, 	$primitive->deserialize(0.03));
		self::assertSame(true, 	$primitive->deserialize(true));
		self::assertSame(null, 	$primitive->deserialize(null));
	}
	
	public function test_serialize()
	{
		$primitive = new PrimitiveSerializer();
		
		self::assertSame(1, 		$primitive->serialize(1));
		self::assertSame('a', 		$primitive->serialize('a'));
		self::assertSame(0.03, 	$primitive->serialize(0.03));
		self::assertSame(true, 	$primitive->serialize(true));
		self::assertSame(null, 	$primitive->serialize(null));
	}
}


function TestMethodForPrimitiveSerializerTest()
{
	
}