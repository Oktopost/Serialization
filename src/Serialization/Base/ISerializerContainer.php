<?php
namespace Serialization\Base;


use Serialization\ISerializer;


interface ISerializerContainer
{
	public function add(ISerializer $serializer): ISerializerContainer;
	public function getByType(string $type): ISerializer;
	public function getForTarget($target): ISerializer;
	public function __clone();
}