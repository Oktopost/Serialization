<?php
namespace Serialization\Encoder;


use Serialization\Base\Encoder\IMeta;


class Meta implements IMeta
{
	private $data = [];
	
	
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getData(string $key)
	{
		return $this->data[$key] ?? null;
	}

	/**
	 * @param string $key
	 * @param $data
	 * @return mixed
	 */
	public function setData(string $key, $data): IMeta
	{
		$this->data[$key] = $data;
		return $this;
	}

	/**
	 * @param array $data
	 * @return static|IMeta
	 */
	public function set(array $data): IMeta
	{
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	public function get(): array
	{
		return $this->data;
	}

	public function isEmpty(): bool
	{
		return !(bool)$this->data;
	}
}