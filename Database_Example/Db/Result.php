<?php
namespace Db;

/**
 * Class Result
 */
class Result implements \Iterator, IResult
{
	private $result = null;
	private $query = null;

	private $current = null;
	private $key = 0;
	private $valid = false;

	/**
	 * Result constructor.
	 * @param \Db\IQuery $query
	 */
	public function __construct(IQuery $query)
	{
		$this->result = $query->execute();
		$this->query = $query;
	}

	/**
	 * @return bool|null
	 */
	public function getAffectedRows()
	{
		return $this->query->getQuery()->rowCount();
	}

	/**
	 * @param bool $fetchAsObjectsList
	 * @return array
	 */
	public function fetchAll($fetchAsObjectsList = false)
	{
		$all = array();
		for ($this->rewind(); $this->valid(); $this->next()) {
			if ($fetchAsObjectsList) {
				$current = $this->getObject();
			} else {
				$current = $this->current();
			}
			$all[] = $current;
		}
		return $all;
	}

	/**
	 * @return \Generator
	 */
	public function getGenerator()
	{
		for ($this->rewind(); $this->valid(); $this->next()) {
			yield $this->current();
		}
	}

	/**
	 * @return int
	 */
	public function getNumberRows()
	{
		return $this->query->getQuery()->rowCount();
	}

	/**
	 * @return string
	 */
	public function getInsertId()
	{
		return $this->query->getDriver()->lastInsertId();
	}

	/**
	 * @return mixed|null
	 */
	public function getAssocRow()
	{
		if ($this->valid() && !$this->current()) {
			$this->query->getQuery()->execute();
		}
		$this->rewind();
		return $this->current();
	}

	/**
	 * @return object
	 */
	public function getObject()
	{
		return (object)$this->current();
	}

	/**
	 * Rewind the Iterator to the first element
	 * @return void Any returned value is ignored.
	 */
	public function rewind()
	{
		$this->query->execute();
		if ($this->getNumberRows() > 0) {
			$this->key = 0;
			$this->valid = true;
			$this->current = $this->query->getQuery()->fetch(\PDO::FETCH_ASSOC, $this->key);
		} else {
			$this->valid = false;
		}
	}

	/**
	 * Move forward to next element
	 * @return void Any returned value is ignored.
	 */
	public function next()
	{
		if($this->current = $this->query->getQuery()->fetch(\PDO::FETCH_ASSOC, $this->key() + 1)) {
			$this->valid = true;
			$this->key++;
		} else {
			$this->valid = false;
		}
	}

	/**
	 * Checks if current position is valid
	 * @return bool The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 */
	public function valid()
	{
		return $this->valid;
	}

	/**
	 * Return the current element
	 * @return mixed Can return any type.
	 */
	public function current()
	{
		if (is_null($this->current)) {
			$this->rewind();
		}
		return $this->current;
	}

	/**
	 * Return the key of the current element
	 * @return bool|float|int|string|null
	 */
	public function key()
	{
		return $this->key;
	}

	public function __destruct()
	{
		unset($this->result);
		unset($this->query);
		unset($this->current);
	}
}