<?php
namespace Serialization;


use PHPUnit\Framework\TestCase;
use Serialization\Base\Encoder\IEncodersContainer;
use Serialization\Encoder\EncodersContainer;


class EncoderContainerTest extends TestCase
{
	/**
	 * @param string $name
	 * @return \PHPUnit_Framework_MockObject_MockObject|IEncoder
	 */
	private function mockEncoder(string $name): IEncoder
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		$serializer = $this->createMock(IEncoder::class);
		
		if ($name)
		{
			$serializer->method('serializerName')->willReturn($name);
		}
		
		return $serializer;
	}
	
	
	public function test_Skeleton()
	{
		self::assertInstanceOf(EncodersContainer::class, Scope::skeleton(IEncodersContainer::class));
	}
	
	
	public function test_ReturnSelf()
	{
		$subject = new EncodersContainer();
		self::assertSame($subject, $subject->add($this->mockEncoder('a')));
	}


	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_getByType_NotFound_ThrowException()
	{
		$subject = new EncodersContainer();
		$subject->getByType('a');
	}
	
	public function test_getByType_FoundAndReturned()
	{
		$serializer = $this->mockEncoder('abc');
		
		$subject = new EncodersContainer();
		$subject->add($serializer);
		
		self::assertSame($serializer, $subject->getByType('abc'));
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_getForTarget_NotFound_ThrowException()
	{
		$subject = new EncodersContainer();
		$subject->getForTarget(123);
	}
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_getForTarget_AllSerializersCalled()
	{
		$serializer1 = $this->mockEncoder('a');
		$serializer1->expects($this->once())->method('canSerialize')->willReturn(false);
		
		$serializer2 = $this->mockEncoder('b');
		$serializer2->expects($this->once())->method('canSerialize')->willReturn(false);
		
		$serializer3 = $this->mockEncoder('c');
		$serializer3->expects($this->once())->method('canSerialize')->willReturn(false);
		
		$subject = new EncodersContainer();
		$subject->add($serializer1);
		$subject->add($serializer2);
		$subject->add($serializer3);
		
		$subject->getForTarget(123);
	}
	
	public function test_getForTarget_SerializerFound_NewerSerializersNotCalled()
	{
		$serializer1 = $this->mockEncoder('a');
		$serializer1->expects($this->once())->method('canSerialize')->willReturn(false);
		
		$serializer2 = $this->mockEncoder('b');
		$serializer2->expects($this->once())->method('canSerialize')->willReturn(true);
		
		$serializer3 = $this->mockEncoder('c');
		$serializer3->expects($this->never())->method('canSerialize');
		
		$subject = new EncodersContainer();
		$subject->add($serializer1);
		$subject->add($serializer2);
		$subject->add($serializer3);
		
		$subject->getForTarget(123);
	}
	
	public function test_getForTarget_SerializerFound_SerializerReturned()
	{
		$serializer = $this->mockEncoder('a');
		$serializer->expects($this->once())->method('canSerialize')->willReturn(true);
		
		$subject = new EncodersContainer();
		$subject->add($serializer);
		
		self::assertSame($serializer, $subject->getForTarget(123));
	}
	
	public function test_getForTarget_DataPassedToSerializer()
	{
		$serializer = $this->mockEncoder('b');
		
		$serializer
			->expects($this->once())
			->method('canSerialize')
			->with(123)
			->willReturn(true);
		
		$subject = new EncodersContainer();
		$subject->add($serializer);
		
		$subject->getForTarget(123);
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_add_SerializerAlreadyDefined_ThrowException()
	{
		$subject = new EncodersContainer();
		$subject->add($this->mockEncoder('a'));
		$subject->add($this->mockEncoder('a'));
	}
}