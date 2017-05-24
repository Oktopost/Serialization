<?php
namespace Serialization\Json\Serializers;


use Serialization\Base\Json\IPlainSerializer;

use PHPUnit\Framework\TestCase;


class TypedSerializerTest extends TestCase
{
	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|IPlainSerializer
	 */
	private function mockPlainSerializer()
	{
		return $this->createMock(IPlainSerializer::class);
	}
	
	
	public function test_canSerialize_ChildSerializerCalled()
	{
		$mock = $this->mockPlainSerializer();
		$mock
			->expects($this->once())
			->method('canSerialize')
			->with(123);
		
		$typed = new TypedSerializer('a', $mock);
		
		$typed->canSerialize(123);
	}
	
	public function test_canSerialize_ValueReturned()
	{
		$mock = $this->mockPlainSerializer();
		$mock
			->method('canSerialize')
			->willReturn(true);
		
		$typed = new TypedSerializer('a', $mock);
		self::assertTrue($typed->canSerialize(123));
		
		
		$mock = $this->mockPlainSerializer();
		$mock
			->method('canSerialize')
			->willReturn(false);
		
		$typed = new TypedSerializer('a', $mock);
		self::assertFalse($typed->canSerialize(123));
	}
	
	
	public function test_canDeserialize_NotObject_ReturnFalse()
	{
		$typed = new TypedSerializer('a', $this->mockPlainSerializer());
		
		self::assertFalse($typed->canDeserialize(123));
		self::assertFalse($typed->canDeserialize(null));
		self::assertFalse($typed->canDeserialize('abcdef'));
	}
	
	public function test_canDeserialize_MissingTypeToken_ReturnFalse()
	{
		$typed = new TypedSerializer('a', $this->mockPlainSerializer());
		
		self::assertFalse($typed->canDeserialize(
			(object)[
				// 'typed-serializer-name' => 'a'
				'data' => 123
			]
		));
	}
	
	public function test_canDeserialize_MissingDataToken_ReturnFalse()
	{
		$typed = new TypedSerializer('a', $this->mockPlainSerializer());
		
		self::assertFalse($typed->canDeserialize(
			(object)[
				'typed-serializer-name' => 'a'
				// 'data' => 123
			]
		));
	}
	
	public function test_canDeserialize_DifferentToken_ReturnFalse()
	{
		$typed = new TypedSerializer('a', $this->mockPlainSerializer());
		
		self::assertFalse($typed->canDeserialize(
			(object)[
				'typed-serializer-name' => 'b',
				'data' => 123
			]
		));
	}
	
	public function test_canDeserialize_CorrectData_ReturnTrue()
	{
		$typed = new TypedSerializer('a', $this->mockPlainSerializer());
		
		self::assertTrue($typed->canDeserialize(
			(object)[
				'typed-serializer-name' => 'a',
				'data' => 123
			]
		));
	}
	
	
	public function test_deserialize_ParametersPassed()
	{
		$mock = $this->mockPlainSerializer();
		$mock
			->expects($this->once())
			->method('deserialize')
			->with(123, 'abc');
		
		$typed = new TypedSerializer('a', $mock);
		
		$typed->deserialize(
			(object)[
				'typed-serializer-name' => 'a',
				'data' => 123,
				'meta' => 'abc'
			]
		);
	}
	
	public function test_deserialize_MetaDataMissing_NullPassedAsMeta()
	{
		$mock = $this->mockPlainSerializer();
		$mock
			->expects($this->once())
			->method('deserialize')
			->with(123, null);
		
		$typed = new TypedSerializer('a', $mock);
		
		$typed->deserialize(
			(object)[
				'typed-serializer-name' => 'a',
				'data' => 123
			]
		);
	}
	
	public function test_deserialize_DataReturnedFromChildObject()
	{
		$mock = $this->mockPlainSerializer();
		$mock->method('deserialize')->willReturn($this);
		
		$typed = new TypedSerializer('a', $mock);
		
		self::assertSame(
			$this, 
			$typed->deserialize(
				(object)[
					'typed-serializer-name' => 'a',
					'data' => 123
				]
			));
	}
	
	
	public function test_serialize_ParametersPassed()
	{
		$mock = $this->mockPlainSerializer();
		$mock
			->expects($this->once())
			->method('serialize')
			->with(123, null);
		
		$typed = new TypedSerializer('a', $mock);
		
		$typed->serialize(123);
	}
	
	public function test_serialize_MetaDataNotSet_MetaDataMissingInResult()
	{
		$mock = $this->mockPlainSerializer();
		$mock
			->method('serialize')
			->with(123, null);
		
		$typed = new TypedSerializer('a', $mock);
		
		self::assertEquals(
			[
				'typed-serializer-name' => 'a',
				'data' => null
			],
			$typed->serialize(123));
	}
	
	public function test_serialize_MetaDataSet_MetaDataInResult()
	{
		$mock = $this->mockPlainSerializer();
		$mock
			->method('serialize')
			->willReturnCallback(function ($a, &$b)
			{
				$b = 123;
			});
		
		$typed = new TypedSerializer('a', $mock);
		
		self::assertEquals(
			[
				'typed-serializer-name' => 'a',
				'data' => null,
				'meta' => 123
			],
			$typed->serialize(123));
	}
	
	public function test_serialize_SerializerReturnsData_DataInResult()
	{
		$mock = $this->mockPlainSerializer();
		$mock
			->method('serialize')
			->willReturn($this);
		
		$typed = new TypedSerializer('a', $mock);
		
		self::assertEquals(
			[
				'typed-serializer-name' => 'a',
				'data' => $this
			],
			$typed->serialize(123));
	}
}