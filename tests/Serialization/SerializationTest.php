<?php
namespace Serialization;


use PHPUnit\Framework\TestCase;
use Serialization\Base\ISerialization;
use Serialization\Base\INormalizedSerializer;
use Serialization\Base\ISerializationContainer;


class SerializationTest extends TestCase
{
	protected function tearDown()
	{
		\UnitTestSkeletonMock::reset();
	}
	
	
	/**
	 * @return INormalizedSerializer|\PHPUnit_Framework_MockObject_MockObject
	 */
	private function mockNormalizedSerializer(): INormalizedSerializer
	{
		return $this->createMock(INormalizedSerializer::class);
	}
	
	/**
	 * @return ISerializationContainer|\PHPUnit_Framework_MockObject_MockObject
	 */
	private function mockContainer(): ISerializationContainer
	{
		$container = $this->createMock(ISerializationContainer::class);
		\UnitTestSkeletonMock::set(ISerializationContainer::class, $container);
		return $container;
	}
	
	/**
	 * @return INormalizedSerializer|\PHPUnit_Framework_MockObject_MockObject
	 */
	private function mockSerializer(): INormalizedSerializer
	{
		return $this->createMock(INormalizedSerializer::class);
	}
	
	private function subject(): Serialization
	{
		return \UnitTestSkeletonMock::create(Serialization::class);
	}
	
	
	public function test_skeleton()
	{
		self::assertInstanceOf(Serialization::class, Scope::skeleton(ISerialization::class));
	}
	
	
	public function test_ReturnSelf()
	{
		$subject = $this->subject();
		self::assertSame($subject, $subject->add($this->mockNormalizedSerializer()));
	}
	
	
	public function test_canDeserialize_HaveSerializer_ReturnFalse()
	{
		$container = $this->mockContainer();
		$container->method('getDeserializerForJson')->willReturn($this->mockSerializer());
		
		self::assertTrue($this->subject()->canDeserialize('a'));
	}
	
	public function test_canDeserialize_NoSerializer_ReturnTrue()
	{
		$container = $this->mockContainer();
		$container->method('getDeserializerForJson')->willReturn(null);
		
		self::assertFalse($this->subject()->canDeserialize('a'));
	}
	
	public function test_canDeserialize_ParameterPassedToSerializer()
	{
		$container = $this->mockContainer();
		$container
			->expects($this->once())
			->method('getDeserializerForJson')
			->with('a');
		
		$this->subject()->canDeserialize('a');
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_deserialize_NoSerializer_ExceptionThrown()
	{
		$container = $this->mockContainer();
		$container->method('getDeserializerForJson')->willReturn(null);
		
		$this->subject()->deserialize('a');
	}
	
	public function test_deserialize_HaveSerializer_ReturnSerializerResult()
	{
		$serializer = $this->mockSerializer();
		$serializer->method('deserialize')->willReturn('b');
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForJson')->willReturn($serializer);
		
		self::assertEquals('b', $this->subject()->deserialize('a'));
	}
	
	public function test_deserialize_ParameterPassedToSerializer()
	{
		$serializer = $this->mockSerializer();
		$serializer
			->expects($this->once())
			->method('deserialize')
			->with('a');
		
		$container = $this->mockContainer();
		$container
			->expects($this->once())
			->method('getDeserializerForJson')
			->with('a')
			->willReturn($serializer);
		
		$this->subject()->deserialize('a');
	}
	
	
	public function test_canSerialize_HaveSerializer_ReturnFalse()
	{
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($this->mockSerializer());
		
		self::assertTrue($this->subject()->canSerialize('a'));
	}
	
	public function test_canSerialize_NoSerializer_ReturnTrue()
	{
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn(null);
		
		self::assertFalse($this->subject()->canSerialize('a'));
	}
	
	public function test_canSerialize_ParameterPassedToSerializer()
	{
		$container = $this->mockContainer();
		$container
			->expects($this->once())
			->method('getSerializerForTarget')
			->with('a');
		
		$this->subject()->canSerialize('a');
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_serialize_NoSerializer_ExceptionThrown()
	{
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn(null);
		
		$this->subject()->serialize('a');
	}
	
	public function test_serialize_HaveSerializer_ReturnSerializerResult()
	{
		$serializer = $this->mockSerializer();
		$serializer->method('serialize')->willReturn('b');
		
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($serializer);
		
		self::assertEquals('b', $this->subject()->serialize('a'));
	}
	
	public function test_serialize_ParameterPassedToSerializer()
	{
		$serializer = $this->mockSerializer();
		$serializer
			->expects($this->once())
			->method('serialize')
			->with('a');
		
		$container = $this->mockContainer();
		$container
			->expects($this->once())
			->method('getSerializerForTarget')
			->with('a')
			->willReturn($serializer);
		
		$this->subject()->serialize('a');
	}
	
	
	public function test_canDeserializeData_HaveSerializer_ReturnFalse()
	{
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($this->mockSerializer());
		
		self::assertTrue($this->subject()->canDeserializeData('a'));
	}
	
	public function test_canDeserializeData_NoSerializer_ReturnTrue()
	{
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn(null);
		
		self::assertFalse($this->subject()->canDeserializeData('a'));
	}
	
	public function test_canDeserializeData_ParameterPassedToSerializer()
	{
		$container = $this->mockContainer();
		$container
			->expects($this->once())
			->method('getDeserializerForData')
			->with('a');
		
		$this->subject()->canDeserializeData('a');
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_getSerializedData_NoSerializer_ExceptionThrown()
	{
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn(null);
		
		$this->subject()->getSerializedData('a');
	}
	
	public function test_getSerializedData_HaveSerializer_ReturnSerializerResult()
	{
		$serializer = $this->mockSerializer();
		$serializer->method('getSerializedData')->willReturn('b');
		
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($serializer);
		
		self::assertEquals('b', $this->subject()->getSerializedData('a'));
	}
	
	public function test_getSerializedData_ParameterPassedToSerializer()
	{
		$serializer = $this->mockSerializer();
		$serializer
			->expects($this->once())
			->method('getSerializedData')
			->with('a');
		
		$container = $this->mockContainer();
		$container
			->expects($this->once())
			->method('getSerializerForTarget')
			->with('a')
			->willReturn($serializer);
		
		$this->subject()->getSerializedData('a');
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_deserializeFromData_NoSerializer_ExceptionThrown()
	{
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn(null);
		
		$this->subject()->deserializeFromData('a');
	}
	
	public function test_deserializeFromData_HaveSerializer_ReturnSerializerResult()
	{
		$serializer = $this->mockSerializer();
		$serializer->method('deserializeFromData')->willReturn('b');
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($serializer);
		
		self::assertEquals('b', $this->subject()->deserializeFromData('a'));
	}
	
	public function test_deserializeFromData_ParameterPassedToSerializer()
	{
		$serializer = $this->mockSerializer();
		$serializer
			->expects($this->once())
			->method('deserializeFromData')
			->with('a');
		
		$container = $this->mockContainer();
		$container
			->expects($this->once())
			->method('getDeserializerForData')
			->with('a')
			->willReturn($serializer);
		
		$this->subject()->deserializeFromData('a');
	}
	
	
	public function test_canSerializeArray_HaveSerializer_ReturnFalse()
	{
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($this->mockSerializer());
		
		self::assertTrue($this->subject()->canSerializeArray(['a']));
	}
	
	public function test_canSerializeArray_NoSerializer_ReturnTrue()
	{
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn(null);
		
		self::assertFalse($this->subject()->canSerializeArray(['a']));
	}
	
	public function test_canSerializeArray_AtLeastOneSerializerMissing_ReturnFalse()
	{
		$container = $this->mockContainer();
		$container->expects($this->at(0))->method('getSerializerForTarget')->willReturn($this->mockSerializer());
		$container->expects($this->at(1))->method('getSerializerForTarget')->willReturn($this->mockSerializer());
		$container->expects($this->at(2))->method('getSerializerForTarget')->willReturn(null);
		
		self::assertFalse($this->subject()->canSerializeArray(['a', 'b', 'c']));
	}
	
	public function test_canSerializeArray_AllSerializersExist_ReturnTrue()
	{
		$container = $this->mockContainer();
		$container->expects($this->at(0))->method('getSerializerForTarget')->willReturn($this->mockSerializer());
		$container->expects($this->at(1))->method('getSerializerForTarget')->willReturn($this->mockSerializer());
		$container->expects($this->at(2))->method('getSerializerForTarget')->willReturn($this->mockSerializer());
		
		self::assertTrue($this->subject()->canSerializeArray(['a', 'b', 'c']));
	}
	
	public function test_canSerializeArray_ParameterPassedToSerializer()
	{
		$container = $this->mockContainer();
		$container->expects($this->at(0))->method('getSerializerForTarget')->with('a')->willReturn($this->mockSerializer());
		$container->expects($this->at(1))->method('getSerializerForTarget')->with('b')->willReturn($this->mockSerializer());
		$container->expects($this->at(2))->method('getSerializerForTarget')->with('c')->willReturn($this->mockSerializer());
		
		$this->subject()->canSerializeArray(['a', 'b', 'c']);
	}
	
	
	public function test_deserializeAll_EmptyArray_ReturnEmptyArray()
	{
		self::assertEquals([], $this->subject()->deserializeAll('[]'));
	}
	
	/**
	 * @expectedException \Serialization\Exceptions\InvalidJsonException
	 */
	public function test_deserializeAll_InvalidJson_ThrowException()
	{
		$this->subject()->deserializeAll('not json');
	}
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_deserializeAll_JsonIsNotArray_ThrowException()
	{
		$this->subject()->deserializeAll('"a"');
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_serializeAll_NoSerializer_ExceptionThrown()
	{
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn(null);
		
		$this->subject()->serializeAll(['a']);
	}
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_serializeAll_OneSerializerNotFound_ExceptionThrown()
	{
		$container = $this->mockContainer();
		$container->expects($this->at(0))->method('getSerializerForTarget')->willReturn($this->mockSerializer());
		$container->expects($this->at(1))->method('getSerializerForTarget')->willReturn($this->mockSerializer());
		$container->expects($this->at(2))->method('getSerializerForTarget')->willReturn(null);
		
		$this->subject()->serializeAll(["a","b","c"]);
	}
	
	public function test_serializeAll_HaveSerializer_ReturnSerializerResult()
	{
		$serializer = $this->mockSerializer();
		$serializer->method('getSerializedData')->willReturn('b');
		
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($serializer);
		
		self::assertEquals('["b"]', $this->subject()->serializeAll(["a"]));
	}
	
	public function test_serializeAll_AllSerializersExists_ReturnSerializersResult()
	{
		$serializer = $this->mockSerializer();
		$serializer->expects($this->at(0))->method('getSerializedData')->willReturn('1');
		$serializer->expects($this->at(1))->method('getSerializedData')->willReturn('2');
		$serializer->expects($this->at(2))->method('getSerializedData')->willReturn('3');
		
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($serializer);
		
		
		self::assertEquals('["1","2","3"]', $this->subject()->serializeAll(["a","b","c"]));
	}
	
	public function test_serializeAll_DataPassedToSerializers()
	{
		$serializer = $this->mockSerializer();
		$serializer->expects($this->at(0))->method('getSerializedData')->with('a');
		$serializer->expects($this->at(1))->method('getSerializedData')->with('b');
		$serializer->expects($this->at(2))->method('getSerializedData')->with('c');
		
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($serializer);
		
		
		$this->subject()->serializeAll(["a","b","c"]);
	}
	
	public function test_serializeAll_ParameterPassedToContainer()
	{
		$serializer = $this->mockSerializer();
		$serializer->method('getSerializedData')->willReturn('a');
		
		$container = $this->mockContainer();
		$container->expects($this->at(0))->method('getSerializerForTarget')->with('a')->willReturn($serializer);
		$container->expects($this->at(1))->method('getSerializerForTarget')->with('b')->willReturn($serializer);
		$container->expects($this->at(2))->method('getSerializerForTarget')->with('c')->willReturn($serializer);
		
		$this->subject()->serializeAll(["a","b","c"]);
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_deserializeAll_NoSerializer_ExceptionThrown()
	{
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn(null);
		
		$this->subject()->deserializeAll('["a"]');
	}
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_deserializeAll_OneSerializerNotFound_ExceptionThrown()
	{
		$container = $this->mockContainer();
		$container->expects($this->at(0))->method('getDeserializerForData')->willReturn($this->mockSerializer());
		$container->expects($this->at(1))->method('getDeserializerForData')->willReturn($this->mockSerializer());
		$container->expects($this->at(2))->method('getDeserializerForData')->willReturn(null);
		
		$this->subject()->deserializeAll('["a","b","c"]');
	}
	
	public function test_deserializeAll_HaveSerializer_ReturnSerializerResult()
	{
		$serializer = $this->mockSerializer();
		$serializer->method('deserializeFromData')->willReturn('b');
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($serializer);
		
		self::assertEquals(['b'], $this->subject()->deserializeAll('["a"]'));
	}
	
	public function test_deserializeAll_AllSerializersExists_ReturnSerializersResult()
	{
		$serializer = $this->mockSerializer();
		$serializer->expects($this->at(0))->method('deserializeFromData')->willReturn('1');
		$serializer->expects($this->at(1))->method('deserializeFromData')->willReturn('2');
		$serializer->expects($this->at(2))->method('deserializeFromData')->willReturn('3');
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($serializer);
		
		
		self::assertEquals(['1', '2', '3'], $this->subject()->deserializeAll('["a","b","c"]'));
	}
	
	public function test_deserializeAll_DataPassedToSerializers()
	{
		$serializer = $this->mockSerializer();
		$serializer->expects($this->at(0))->method('deserializeFromData')->with('a');
		$serializer->expects($this->at(1))->method('deserializeFromData')->with('b');
		$serializer->expects($this->at(2))->method('deserializeFromData')->with('c');
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($serializer);
		
		
		$this->subject()->deserializeAll('["a","b","c"]');
	}
	
	public function test_deserializeAll_ParameterPassedToContainer()
	{
		$serializer = $this->mockSerializer();
		$serializer->method('deserializeFromData')->willReturn('a');
		
		$container = $this->mockContainer();
		$container->expects($this->at(0))->method('getDeserializerForData')->with('a')->willReturn($serializer);
		$container->expects($this->at(1))->method('getDeserializerForData')->with('b')->willReturn($serializer);
		$container->expects($this->at(2))->method('getDeserializerForData')->with('c')->willReturn($serializer);
		
		$this->subject()->deserializeAll('["a","b","c"]');
	}
}