<?php
namespace Serialization\Base;


/**
 * @skeleton
 */
interface IMeta
{
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getData(string $key);

	/**
	 * @param string $key
	 * @param $data
	 * @return mixed
	 */
	public function setData(string $key, $data): IMeta;
	
	/**
	 * @param array $data
	 * @return static|IMeta
	 */
	public function set(array $data): IMeta;
	
	public function get(): array;
	public function isEmpty(): bool;
}