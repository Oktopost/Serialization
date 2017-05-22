<?php
namespace Serialization\Json;


use PHPUnit\Framework\TestCase;

use Serialization\Base\Json\IJsonDataConstructor;
use Serialization\Base\Json\IJsonSerializersContainer;
use Serialization\Scope;


class JsonSerializersContainerTest extends TestCase
{
	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|IJsonDataConstructor
	 */
	private function mockSerializer(): IJsonDataConstructor
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		$serializer = $this->createMock(IJsonDataConstructor::class);
		return $serializer;
	}
	
	
	public function test_skeleton()
	{
		self::assertInstanceOf(JsonSerializersContainer::class, Scope::skeleton(IJsonSerializersContainer::class));
	}
	
	
	public function test_ReturnSelf()
	{
		$subject = new JsonSerializersContainer();
		self::assertSame($subject, $subject->add($this->mockSerializer()));
	}
	
	
	public function test_getSerializerForTarget_NoSerializers_ReturnNull()
	{
		$subject = new JsonSerializersContainer();
		self::assertNull($subject->getSerializerForTarget(123));
	}
	
	public function test_getSerializerForTarget_NotFound_ReturnNull()
	{
		$subject = new JsonSerializersContainer();
		
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
		$subject = new JsonSerializersContainer();
		
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
		$subject = new JsonSerializersContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->method('canSerialize')->willReturn(true);
		
		$subject->add($ser1);
		
		self::assertEquals($ser1, $subject->getSerializerForTarget("123"));
	}
	
	public function test_getSerializerForTarget_FirstFoundSerializerReturned()
	{
		$subject = new JsonSerializersContainer();
		
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
	
	
	public function test_getDeserializerForData_NoSerializers_ReturnNull()
	{
		$subject = new JsonSerializersContainer();
		self::assertNull($subject->getDeserializerForData(123));
	}
	
	public function test_getDeserializerForData_NotFound_ReturnNull()
	{
		$subject = new JsonSerializersContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->method('canDeserialize')->willReturn(false);
		
		$ser2 = $this->mockSerializer();
		$ser2->method('canDeserialize')->willReturn(false);
		
		$subject->add($ser1);
		$subject->add($ser2);
		
		self::assertNull($subject->getDeserializerForData(123));
	}
	
	public function test_getDeserializerForData_ObjectPassedToSerializer()
	{
		$subject = new JsonSerializersContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->expects($this->once())
			->method('canDeserialize')
			->with(123)
			->willReturn(false);
		
		$subject->add($ser1);
		
		self::assertNull($subject->getDeserializerForData(123));
	}
	
	public function test_getDeserializerForData_SerializerReturnsTrue_ReturnSerializer()
	{
		$subject = new JsonSerializersContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->method('canDeserialize')->willReturn(true);
		
		$subject->add($ser1);
		
		self::assertEquals($ser1, $subject->getDeserializerForData(123));
	}
	
	public function test_getDeserializerForData_FirstFoundSerializerReturned()
	{
		$subject = new JsonSerializersContainer();
		
		$ser1 = $this->mockSerializer();
		$ser1->expects($this->once())->method('canDeserialize')->willReturn(false);
		
		$ser2 = $this->mockSerializer();
		$ser2->expects($this->once())->method('canDeserialize')->willReturn(true);
		
		$ser3 = $this->mockSerializer();
		$ser3->expects($this->never())->method('canDeserialize');
		
		$subject->add($ser1);
		$subject->add($ser2);
		$subject->add($ser3);
		
		$subject->getDeserializerForData(123);
	}
}