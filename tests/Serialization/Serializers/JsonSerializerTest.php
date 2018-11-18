<?php
namespace Serialization\Json;


use PHPUnit\Framework\TestCase;

use Serialization\Base\Json\IJsonDataConstructor;
use Serialization\Scope;
use Serialization\Base\IJsonSerializer;
use Serialization\Base\Json\IJsonSerializersContainer;
use Serialization\Serializers\JsonSerializer;


class JsonSerializerTest extends TestCase
{
	protected function tearDown()
	{
		\UnitTestSkeletonMock::reset();
	}
	
	
	/**
	 * @return IJsonDataConstructor|\PHPUnit_Framework_MockObject_MockObject
	 */
	private function mockConstructor(): IJsonDataConstructor
	{
		return $this->createMock(IJsonDataConstructor::class);
	}
	
	/**
	 * @return IJsonSerializersContainer|\PHPUnit_Framework_MockObject_MockObject
	 */
	private function mockContainer(): IJsonSerializersContainer
	{
		$container = $this->createMock(IJsonSerializersContainer::class);
		\UnitTestSkeletonMock::set(IJsonSerializersContainer::class, $container);
		return $container;
	}
	
	private function subject(): JsonSerializer
	{
		return \UnitTestSkeletonMock::create(JsonSerializer::class);
	}
	
	
	public function test_skeleton()
	{
		self::assertInstanceOf(JsonSerializer::class, Scope::skeleton(IJsonSerializer::class));
	}
	
	
	public function test_ReturnSelf()
	{
		$subject = $this->subject();
		self::assertSame($subject, $subject->add($this->mockConstructor()));
	}
	
	
	/**
	 * @expectedException \Serialization\Exceptions\InvalidJsonException
	 */
	public function test_deserialize_ParameterNotJson_ErrorThrown()
	{
		$this->subject()->deserialize('Invalid Json');
	}
	
	/**
	 * @expectedException \Serialization\Exceptions\SerializationException
	 */
	public function test_deserialize_NoSerializer_ExceptionThrown()
	{
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn(null);
		
		$this->subject()->deserialize('[1]');
	}
	
	public function test_deserialize_HaveSerializer_ReturnSerializerResult()
	{
		$serializer = $this->mockConstructor();
		$serializer->method('deserialize')->willReturn([1]);
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($serializer);
		
		self::assertEquals([1], $this->subject()->deserialize('[1]'));
	}
	
	public function test_deserialize_DecodedParameterPassedToContainer()
	{
		$container = $this->mockContainer();
		$container
			->expects($this->once())
			->method('getDeserializerForData')
			->with([1])
			->willReturn($this->mockConstructor());
		
		$this->subject()->deserialize('[1]');
	}
	
	public function test_deserialize_DecodedParameterPassedToConstructor()
	{
		$serializer = $this->mockConstructor();
		$serializer
			->expects($this->once())
			->method('deserialize')
			->with([1]);
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($serializer);
		
		$this->subject()->deserialize('[1]');
	}
	
	public function test_deserialize_JsonWithOnlyNullValue_NoExceptionThrown()
	{
		$serializer = $this->mockConstructor();
		$serializer
			->expects($this->once())
			->method('deserialize')
			->with(null);
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($serializer);
		
		$this->subject()->deserialize('null');
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
		$serializer = $this->mockConstructor();
		$serializer->method('serialize')->willReturn('b');
		
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($serializer);
		
		self::assertEquals('"b"', $this->subject()->serialize('a'));
	}
	
	public function test_serialize_ParameterPassedToConstructor()
	{
		$serializer = $this->mockConstructor();
		$serializer
			->expects($this->once())
			->method('serialize')
			->with([1]);
		
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($serializer);
		
		$this->subject()->serialize([1]);
	}
	
	public function test_serialize_ParameterPassedToContainer()
	{
		$container = $this->mockContainer();
		$container
			->expects($this->once())
			->method('getSerializerForTarget')
			->with([1])
			->willReturn($this->mockConstructor());
		
		$this->subject()->serialize([1]);
	}
	
	public function test_serialize_NullReturned_SerializeNull()
	{
		$serializer = $this->mockConstructor();
		$serializer
			->method('serialize')
			->willReturn(null);
		
		$container = $this->mockContainer();
		$container
			->method('getSerializerForTarget')
			->willReturn($serializer);
		
		self::assertEquals("null", $this->subject()->serialize(null));
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
		$container->expects($this->at(0))->method('getDeserializerForData')->willReturn($this->mockConstructor());
		$container->expects($this->at(1))->method('getDeserializerForData')->willReturn($this->mockConstructor());
		$container->expects($this->at(2))->method('getDeserializerForData')->willReturn(null);
		
		$this->subject()->deserializeAll('["a","b","c"]');
	}
	
	public function test_deserializeAll_HaveSerializer_ReturnSerializerResult()
	{
		$serializer = $this->mockConstructor();
		$serializer->method('deserialize')->willReturn('b');
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($serializer);
		
		self::assertEquals(['b'], $this->subject()->deserializeAll('["a"]'));
	}
	
	public function test_deserializeAll_AllSerializersExists_ReturnSerializersResult()
	{
		$serializer = $this->mockConstructor();
		$serializer->expects($this->at(0))->method('deserialize')->willReturn(1);
		$serializer->expects($this->at(1))->method('deserialize')->willReturn('2');
		$serializer->expects($this->at(2))->method('deserialize')->willReturn('3');
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($serializer);
		
		self::assertEquals([1, '2', '3'], $this->subject()->deserializeAll('["a","b","c"]'));
	}
	
	public function test_deserializeAll_DataPassedToSerializers()
	{
		$serializer = $this->mockConstructor();
		$serializer->expects($this->at(0))->method('deserialize')->with('a');
		$serializer->expects($this->at(1))->method('deserialize')->with(2);
		$serializer->expects($this->at(2))->method('deserialize')->with(null);
		
		$container = $this->mockContainer();
		$container->method('getDeserializerForData')->willReturn($serializer);
		
		$this->subject()->deserializeAll('["a",2,null]');
	}
	
	public function test_deserializeAll_ParameterPassedToContainer()
	{
		$serializer = $this->mockConstructor();
		$serializer->method('deserialize')->willReturn('a');
		
		$container = $this->mockContainer();
		$container->expects($this->at(0))->method('getDeserializerForData')->with('a')->willReturn($serializer);
		$container->expects($this->at(1))->method('getDeserializerForData')->with(2)->willReturn($serializer);
		$container->expects($this->at(2))->method('getDeserializerForData')->with(null)->willReturn($serializer);
		
		$this->subject()->deserializeAll('["a",2,null]');
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
		$container->expects($this->at(0))->method('getSerializerForTarget')->willReturn($this->mockConstructor());
		$container->expects($this->at(1))->method('getSerializerForTarget')->willReturn($this->mockConstructor());
		$container->expects($this->at(2))->method('getSerializerForTarget')->willReturn(null);
		
		$this->subject()->serializeAll(["a","b","c"]);
	}
	
	public function test_serializeAll_HaveSerializer_ReturnSerializerResult()
	{
		$serializer = $this->mockConstructor();
		$serializer->method('serialize')->willReturn(123);
		
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($serializer);
		
		self::assertEquals('[123]', $this->subject()->serializeAll(['a']));
	}
	
	public function test_serializeAll_AllSerializersExists_ReturnSerializersResult()
	{
		$serializer = $this->mockConstructor();
		$serializer->expects($this->at(0))->method('serialize')->willReturn('1');
		$serializer->expects($this->at(1))->method('serialize')->willReturn(2);
		$serializer->expects($this->at(2))->method('serialize')->willReturn(null);
		
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($serializer);
		
		
		self::assertEquals('["1",2,null]', $this->subject()->serializeAll(["a","b","c"]));
	}
	
	public function test_serializeAll_DataPassedToSerializers()
	{
		$serializer = $this->mockConstructor();
		$serializer->expects($this->at(0))->method('serialize')->with('a');
		$serializer->expects($this->at(1))->method('serialize')->with(123);
		$serializer->expects($this->at(2))->method('serialize')->with(null);
		
		$container = $this->mockContainer();
		$container->method('getSerializerForTarget')->willReturn($serializer);
		
		
		$this->subject()->serializeAll(["a",123,null]);
	}
	
	public function test_serializeAll_ParameterPassedToContainer()
	{
		$serializer = $this->mockConstructor();
		$serializer->method('serialize')->willReturn('a');
		
		$container = $this->mockContainer();
		$container->expects($this->at(0))->method('getSerializerForTarget')->with('a')->willReturn($serializer);
		$container->expects($this->at(1))->method('getSerializerForTarget')->with(123)->willReturn($serializer);
		$container->expects($this->at(2))->method('getSerializerForTarget')->with(null)->willReturn($serializer);
		
		$this->subject()->serializeAll(["a",123,null]);
	}
}