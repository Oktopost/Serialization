<?php
namespace Serialization;


use PHPUnit\Framework\TestCase;
use Serialization\Base\ISerializerContainer;


class SerializerContainerTest extends TestCase
{
	/**
	 * @param string $name
	 * @return \PHPUnit_Framework_MockObject_MockObject|ISerializer
	 */
	private function mockSerializer(string $name): ISerializer
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		$serializer = $this->createMock(ISerializer::class);
		
		if ($name)
		{
			$serializer->method('serializerName')->willReturn($name);
		}
		
		return $serializer;
	}
	
	
	public function test_Skeleton()
	{
		self::assertInstanceOf(SerializerContainer::class, Scope::skeleton(ISerializerContainer::class));
	}
	
	
	public function test_ReturnSelf()
	{
		$subject = new SerializerContainer();
		self::assertSame($subject, $subject->add($this->mockSerializer('a')));
	}


	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_getByType_NotFound_ThrowException()
	{
		$subject = new SerializerContainer();
		$subject->getByType('a');
	}
	
	public function test_getByType_FoundAndReturned()
	{
		$serializer = $this->mockSerializer('abc');
		
		$subject = new SerializerContainer();
		$subject->add($serializer);
		
		self::assertSame($serializer, $subject->getByType('abc'));
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_getForTarget_NotFound_ThrowException()
	{
		$subject = new SerializerContainer();
		$subject->getForTarget(123);
	}
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_getForTarget_AllSerializersCalled()
	{
		$serializer1 = $this->mockSerializer('a');
		$serializer1->expects($this->once())->method('canSerialize')->willReturn(false);
		
		$serializer2 = $this->mockSerializer('b');
		$serializer2->expects($this->once())->method('canSerialize')->willReturn(false);
		
		$serializer3 = $this->mockSerializer('c');
		$serializer3->expects($this->once())->method('canSerialize')->willReturn(false);
		
		$subject = new SerializerContainer();
		$subject->add($serializer1);
		$subject->add($serializer2);
		$subject->add($serializer3);
		
		$subject->getForTarget(123);
	}
	
	public function test_getForTarget_SerializerFound_NewerSerializersNotCalled()
	{
		$serializer1 = $this->mockSerializer('a');
		$serializer1->expects($this->once())->method('canSerialize')->willReturn(false);
		
		$serializer2 = $this->mockSerializer('b');
		$serializer2->expects($this->once())->method('canSerialize')->willReturn(true);
		
		$serializer3 = $this->mockSerializer('c');
		$serializer3->expects($this->never())->method('canSerialize');
		
		$subject = new SerializerContainer();
		$subject->add($serializer1);
		$subject->add($serializer2);
		$subject->add($serializer3);
		
		$subject->getForTarget(123);
	}
	
	public function test_getForTarget_SerializerFound_SerializerReturned()
	{
		$serializer = $this->mockSerializer('a');
		$serializer->expects($this->once())->method('canSerialize')->willReturn(true);
		
		$subject = new SerializerContainer();
		$subject->add($serializer);
		
		self::assertSame($serializer, $subject->getForTarget(123));
	}
	
	public function test_getForTarget_DataPassedToSerializer()
	{
		$serializer = $this->mockSerializer('b');
		
		$serializer
			->expects($this->once())
			->method('canSerialize')
			->with(123)
			->willReturn(true);
		
		$subject = new SerializerContainer();
		$subject->add($serializer);
		
		$subject->getForTarget(123);
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_add_SerializerAlreadyDefined_ThrowException()
	{
		$subject = new SerializerContainer();
		$subject->add($this->mockSerializer('a'));
		$subject->add($this->mockSerializer('a'));
	}
}