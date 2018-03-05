<?php
namespace Serialization\Json\Plain;


use Objection\LiteObject;
use Objection\LiteSetup;
use PHPUnit\Framework\TestCase;


class PlainNarrowSerializerTest extends TestCase
{
	public function test_canSerialize_PassLiteObject_ReturnTrue()
	{
		$subject = new PlainNarrowSerializer();
		
		self::assertTrue(
			$subject->canSerialize(new class extends LiteObject {
				protected function _setup() { return []; }
			})
		);
	}
	
	public function test_NonLiteObjectInstancePassed_ReturnFalse()
	{
		$subject = new PlainNarrowSerializer();
		self::assertFalse($subject->canSerialize($this));
	}
	
	public function test_ScalarPassed_ReturnFalse()
	{
		$subject = new PlainNarrowSerializer();
		self::assertFalse($subject->canSerialize($this));
	}
	
	public function test_serialize_ObjectWithoutProperties_ReturnEmptyArray()
	{
		$subject = new PlainNarrowSerializer();
		
		self::assertEquals(
			$subject->canSerialize(new class extends LiteObject {
				protected function _setup() { return []; }
			}),
			[]
		);
	}
	
	public function test_serialize_ObjectWithProperties_ReturnProperties()
	{
		$subject = new PlainNarrowSerializer();
		
		self::assertEquals(
			$subject->canSerialize(new class extends LiteObject {
				protected function _setup() 
				{
					return [
						'A'	=> LiteSetup::createString('abc'),
						'B'	=> LiteSetup::createInt('123')
					]; 
				}
			}),
			[
				'A' => 'abc',
				'B' => '123'
			]
		);
	}
	
	public function test_serialize_ProperyWithValueNull_PropertySkipped()
	{
		$subject = new PlainNarrowSerializer();
		
		self::assertEquals(
			$subject->canSerialize(new class extends LiteObject {
				protected function _setup() 
				{
					return [
						'A'	=> LiteSetup::createString('abc'),
						'B'	=> LiteSetup::createInt(null)
					]; 
				}
			}),
			[
				'A' => 'abc'
			]
		);
	}
	
	public function test_serialize_FalseLikeProperties_ValuesReturned()
	{
		$subject = new PlainNarrowSerializer();
		
		self::assertEquals(
			$subject->canSerialize(new class extends LiteObject {
				protected function _setup() 
				{
					return [
						'A'	=> LiteSetup::createArray(''),
						'B'	=> LiteSetup::createInt(0),
						'C'	=> LiteSetup::createBool(false)
					]; 
				}
			}),
			[
				'A' => '',
				'B'	=> 0,
				'C' => false
			]
		);
	}
	
	public function test_serialize_PropertyIsAnotherObject_ValuesReturned()
	{
		$subject = new PlainNarrowSerializer();
		
		$parent = new class extends LiteObject {
			protected function _setup() 
			{
				return [
					'A'	=> LiteSetup::createInstanceOf(ChildClass::class)
				]; 
			}
		};
		
		$parent->A = new ChildClass(['child_A' => '123', 'child_B' => 456]);
		
		self::assertEquals(
			$subject->canSerialize($parent),
			[
				'A' =>
				[
					'child_A' => '123',
					'child_B' => 456
				]
			]
		);
	}
	
	public function test_serialize_PropertyInChildObjectIsNull_NullValuesReturned()
	{
		$subject = new PlainNarrowSerializer();
		
		$parent = new class extends LiteObject {
			protected function _setup() 
			{
				return [
					'A'	=> LiteSetup::createInstanceOf(ChildClass::class)
				]; 
			}
		};
		
		$parent->A = new ChildClass(['child_A' => '123']);
		
		self::assertEquals(
			$subject->canSerialize($parent),
			[
				'A' =>
				[
					'child_A' => '123'
				]
			]
		);
	}
	
	public function test_serialize_AllPropertiesInChildObjectAreNull_ObjectIsEmptyArray()
	{
		$subject = new PlainNarrowSerializer();
		
		$parent = new class extends LiteObject {
			protected function _setup() 
			{
				return [
					'A'	=> LiteSetup::createInstanceOf(ChildClass::class),
					'B'	=> LiteSetup::createInt(123)
				]; 
			}
		};
		
		$parent->A = new ChildClass();
		
		self::assertEquals(
			$subject->canSerialize($parent),
			[
				'A'	=> [],
				'B' => 123
			]
		);
	}
	
	public function test_serialize_ChildObjectIsNull_PropertyIgnored()
	{
		$subject = new PlainNarrowSerializer();
		
		$parent = new class extends LiteObject {
			protected function _setup() 
			{
				return [
					'A'	=> LiteSetup::createInstanceOf(ChildClass::class),
					'B'	=> LiteSetup::createInt(123)
				]; 
			}
		};
		
		self::assertEquals(
			$subject->canSerialize($parent),
			[ 'B' => 123 ]
		);
	}
}


class ChildClass extends LiteObject 
{
	protected function _setup() 
	{
		return [
			'child_A'	=> LiteSetup::createString(null),
			'child_B'	=> LiteSetup::createInt(null)
		]; 
	}
	
	
	public function __construct(array $values = [])
	{
		parent::__construct();
		$this->fromArray($values);
	}
};