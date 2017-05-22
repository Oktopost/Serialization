<?php
namespace Serialization\Base\Encoder;


/**
 * @skeleton
 */
interface IEncodersContainer
{
	public function add(IEncoder $encoder): IEncodersContainer;
	public function getByType(string $type): IEncoder;
	public function getForTarget($target): IEncoder;
	public function __clone();
}