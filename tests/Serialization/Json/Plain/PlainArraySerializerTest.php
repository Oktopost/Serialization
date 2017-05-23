<?php
namespace Serialization\Json\Plain;


use PHPUnit\Framework\TestCase;


class PlainArraySerializerTest extends TestCase
{
	private function assertSanity($value)
	{
		$subject = new PlainArraySerializer();
		$meta = null;
		
		$data = $subject->serialize($value, $meta);
		$data = json_decode(json_encode($data));
		
		self::assertEquals($value, $subject->deserialize($data, $meta));
	}
	
	
	public function test_canSerialize_NumericArray_ReturnTrue()
	{
		$subject = new PlainArraySerializer();
		self::assertTrue($subject->canSerialize(['a']));
	}
	
	public function test_canSerialize_AssocArray_ReturnTrue()
	{
		$subject = new PlainArraySerializer();
		self::assertTrue($subject->canSerialize(['a' => 'b']));
	}
	
	public function test_canSerialize_EmptyArray_ReturnTrue()
	{
		$subject = new PlainArraySerializer();
		self::assertTrue($subject->canSerialize(['a' => 'b']));
	}
	
	public function test_canSerialize_Object_ReturnTrue()
	{
		$subject = new PlainArraySerializer();
		self::assertTrue($subject->canSerialize((object)[1]));
	}
	
	public function test_canSerialize_EmptyObject_ReturnTrue()
	{
		$subject = new PlainArraySerializer();
		self::assertTrue($subject->canSerialize((object)[]));
	}
	
	public function test_canSerialize_NonStdObject_ReturnFalse()
	{
		$subject = new PlainArraySerializer();
		self::assertFalse($subject->canSerialize($this));
	}
	
	public function test_canSerialize_NotArrayOrStdClass_ReturnFalse()
	{
		$subject = new PlainArraySerializer();
		self::assertFalse($subject->canSerialize(1));
		self::assertFalse($subject->canSerialize('a'));
		self::assertFalse($subject->canSerialize(null));
		self::assertFalse($subject->canSerialize(function() {}));
	}
	
	
	public function test_sanity_NumericArray()
	{
		$this->assertSanity(['a']);
	}
	
	public function test_sanity_AssocArray()
	{
		$this->assertSanity(['b' => 'a']);
	}
	
	public function test_sanity_EmptyArray()
	{
		$this->assertSanity([]);
	}
	
	public function test_sanity_DeepArray()
	{
		$this->assertSanity(['a' => ['b' => 'a'], [1]]);
	}
	
	
	public function test_sanity_NumericObject()
	{
		$this->assertSanity((object)['a']);
	}
	
	public function test_sanity_AssocObject()
	{
		$this->assertSanity((object)['b' => 'a']);
	}
	
	public function test_sanity_EmptyObject()
	{
		$this->assertSanity((object)[]);
	}
	
	public function test_sanity_DeepObject()
	{
		$this->assertSanity((object)['a' => (object)['b' => 'a'], (object)[1]]);
	}
}