<?php
namespace Serialization;


use PHPUnit\Framework\TestCase;
use Serialization\Base\Encoder\IMeta;
use Serialization\Encoder\Meta;


class MetaTest extends TestCase
{
	public function test_Skeleton()
	{
		self::assertInstanceOf(Meta::class, Scope::skeleton(IMeta::class));
	}
	
	
	public function test_ReturnSelf()
	{
		$subject = new Meta();
		self::assertSame($subject, $subject->set([]));
		self::assertSame($subject, $subject->setData('a', 123));
	}
	
	
	public function test_isEmpty()
	{
		$subject = new Meta();
		self::assertTrue($subject->isEmpty());
		
		$subject->set(['a' => 2]);
		self::assertFalse($subject->isEmpty());
	}
	
	
	public function test_setData()
	{
		$subject = new Meta();
		$subject->setData('a', 123);
		
		self::assertEquals(123, $subject->getData('a'));
	}
	
	public function test_setData_OverrideExistingKey()
	{
		$subject = new Meta();
		$subject->setData('a', 123);
		$subject->setData('a', 124);
		
		self::assertEquals(124, $subject->getData('a'));
	}
	
	public function test_setData_NotAffectExistingKeys()
	{
		$subject = new Meta();
		$subject->setData('a', 123);
		$subject->setData('b', 124);
		
		self::assertEquals(123, $subject->getData('a'));
	}
	
	
	public function test_set()
	{
		$subject = new Meta();
		$subject->set(['a' => 123, 'b' => false]);
		
		self::assertEquals(123, $subject->getData('a'));
		self::assertEquals(false, $subject->getData('b'));
	}
	
	public function test_set_EmptyArray()
	{
		$subject = new Meta();
		$subject->set([]);
		
		self::assertTrue($subject->isEmpty());
		self::assertEquals([], $subject->get());
	}
	
	public function test_set_OverrideExistingValues()
	{
		$subject = new Meta();
		$subject->set(['a' => 1]);
		$subject->set(['a' => 2]);
		
		self::assertEquals(['a' => 2], $subject->get());
	}
	
	
	public function test_get()
	{
		$subject = new Meta();
		$data = ['a' => 1, 'b' => true];
		$subject->set($data);
		
		self::assertEquals($data, $subject->get());
	}
	
	
	public function test_getData()
	{
		$subject = new Meta();
		$subject->set(['a' => 1, 'b' => true]);
		
		self::assertEquals(1, $subject->getData('a'));
		self::assertEquals(true, $subject->getData('b'));
	}
	
	public function test_getData_KeyNotExist_ReturnNull()
	{
		$subject = new Meta();
		self::assertNull($subject->getData('a'));
	}
}