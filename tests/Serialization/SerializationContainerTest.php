<?php
namespace Serialization;


use PHPUnit\Framework\TestCase;
use Serialization\Base\INormalizedSerializer;
use Serialization\Base\ISerializationContainer;


class SerializationContainerTest extends TestCase
{
	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|INormalizedSerializer
	 */
	private function mockSerializer(): INormalizedSerializer
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		$serializer = $this->createMock(INormalizedSerializer::class);
		return $serializer;
	}
	
	
	public function test_skeleton()
	{
		self::assertInstanceOf(SerializationContainer::class, Scope::skeleton(ISerializationContainer::class));
	}
	
	
	public function test_ReturnSelf()
	{
		$subject = new SerializationContainer();
		self::assertSame($subject, $subject->add($this->mockSerializer()));
	}
	
	
	public function test_getSerializerForTarget_NoSerializers_ReturnNull()
	{
		$subject = new SerializationContainer();
		self::assertNull($subject->getSerializerForTarget("123"));
	}
	
	public function test_getSerializerForTarget_NotFound_ReturnNull()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->method('canSerialize')->willReturn(false);
		
		$ser2 = $this->mockSerializer();
		$ser2->method('canSerialize')->willReturn(false);
		
		$subject->add($ser1);
		$subject->add($ser2);
		
		self::assertNull($subject->getSerializerForTarget("123"));
	}
	
	public function test_getSerializerForTarget_ObjectPassedToSerializer()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->expects($this->once())
			->method('canSerialize')
			->with("123")
			->willReturn(false);
		
		$subject->add($ser1);
		
		self::assertNull($subject->getSerializerForTarget("123"));
	}
	
	public function test_getSerializerForTarget_SerializerReturnsTrue_ReturnSerializer()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->method('canSerialize')->willReturn(true);
		
		$subject->add($ser1);
		
		self::assertEquals($ser1, $subject->getSerializerForTarget("123"));
	}
	
	public function test_getSerializerForTarget_FirstFoundSerializerReturned()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->expects($this->once())->method('canSerialize')->willReturn(false);
		
		$ser2 = $this->mockSerializer();
		$ser2->expects($this->once())->method('canSerialize')->willReturn(true);
		
		$ser3 = $this->mockSerializer();
		$ser3->expects($this->never())->method('canSerialize');
		
		$subject->add($ser1);
		$subject->add($ser2);
		$subject->add($ser3);
		
		$subject->getSerializerForTarget("123");
	}
	
	
	public function test_getDeserializerForJson_NoSerializers_ReturnNull()
	{
		$subject = new SerializationContainer();
		self::assertNull($subject->getDeserializerForJson("123"));
	}
	
	public function test_getDeserializerForJson_NotFound_ReturnNull()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->method('canDeserialize')->willReturn(false);
		
		$ser2 = $this->mockSerializer();
		$ser2->method('canDeserialize')->willReturn(false);
		
		$subject->add($ser1);
		$subject->add($ser2);
		
		self::assertNull($subject->getDeserializerForJson("123"));
	}
	
	public function test_getDeserializerForJson_ObjectPassedToSerializer()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->expects($this->once())
			->method('canDeserialize')
			->with("123")
			->willReturn(false);
		
		$subject->add($ser1);
		
		self::assertNull($subject->getDeserializerForJson("123"));
	}
	
	public function test_getDeserializerForJson_SerializerReturnsTrue_ReturnSerializer()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->method('canDeserialize')->willReturn(true);
		
		$subject->add($ser1);
		
		self::assertEquals($ser1, $subject->getDeserializerForJson("123"));
	}
	
	public function test_getDeserializerForJson_FirstFoundSerializerReturned()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->expects($this->once())->method('canDeserialize')->willReturn(false);
		
		$ser2 = $this->mockSerializer();
		$ser2->expects($this->once())->method('canDeserialize')->willReturn(true);
		
		$ser3 = $this->mockSerializer();
		$ser3->expects($this->never())->method('canDeserialize');
		
		$subject->add($ser1);
		$subject->add($ser2);
		$subject->add($ser3);
		
		$subject->getDeserializerForJson("123");
	}
	
	
	public function test_getDeserializerForData_NoSerializers_ReturnNull()
	{
		$subject = new SerializationContainer();
		self::assertNull($subject->getDeserializerForData(123));
	}
	
	public function test_getDeserializerForData_NotFound_ReturnNull()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->method('canDeserializeData')->willReturn(false);
		
		$ser2 = $this->mockSerializer();
		$ser2->method('canDeserializeData')->willReturn(false);
		
		$subject->add($ser1);
		$subject->add($ser2);
		
		self::assertNull($subject->getDeserializerForData(123));
	}
	
	public function test_getDeserializerForData_ObjectPassedToSerializer()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->expects($this->once())
			->method('canDeserializeData')
			->with(123)
			->willReturn(false);
		
		$subject->add($ser1);
		
		self::assertNull($subject->getDeserializerForData(123));
	}
	
	public function test_getDeserializerForData_SerializerReturnsTrue_ReturnSerializer()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->method('canDeserializeData')->willReturn(true);
		
		$subject->add($ser1);
		
		self::assertEquals($ser1, $subject->getDeserializerForData(123));
	}
	
	public function test_getDeserializerForData_FirstFoundSerializerReturned()
	{
		$subject = new SerializationContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->expects($this->once())->method('canDeserializeData')->willReturn(false);
		
		$ser2 = $this->mockSerializer();
		$ser2->expects($this->once())->method('canDeserializeData')->willReturn(true);
		
		$ser3 = $this->mockSerializer();
		$ser3->expects($this->never())->method('canDeserializeData');
		
		$subject->add($ser1);
		$subject->add($ser2);
		$subject->add($ser3);
		
		$subject->getDeserializerForData(123);
	}
}