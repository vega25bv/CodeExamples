<?php
namespace Db;

use Db\Result;
use Db\Query;

/**
 * Class Connect
 */
class Connect implements IConnect
{
	protected $pdo = null;

	/**
	 * Connect constructor.
	 * @param       $dsn
	 * @param       $username
	 * @param       $password
	 * @param array $options
	 */
	public function __construct($dsn, $username, $password, array $options = array())
	{
		$this->pdo = new \PDO($dsn, $username, $password, $options);
		$this->pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * @param       $strSQL
	 * @param array $params
	 * @return \Db\Result
	 * @throws \Exception
	 */
	public function query($strSQL, array $params = array())
	{
		return new Result(new Query($strSQL, $this->pdo, $params));
	}

	/**
	 * to close connection just use unset() function call on Connect object
	 */
	public function __destruct()
	{
		$this->pdo = null;
	}
}