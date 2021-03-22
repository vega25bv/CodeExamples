<?php
namespace Db;

/**
 * Class Query
 */
class Query implements IQuery
{

	protected $query = null;
	protected $pdo = null;

	const NAME_KEY = 'key';
	const NAME_VALUE = 'value';
	const NAME_VALUE_TYPE = 'value_type';

	/**
	 * Query constructor.
	 * @param       $strSQL
	 * @param \PDO  $pdo
	 * @param array $params
	 * @throws \Exception
	 */
	public function __construct($strSQL, \PDO $pdo, array $params = array())
	{
		try
		{
			$this->query = $pdo->prepare($strSQL);
			$this->pdo = $pdo;
			$this->bindParams($params);
		} catch (\Exception $e) {
			// todo some logs
			throw new \Exception($e->getMessage(), $e->getCode());
		}
	}

	/**
	 * @param array $params
	 * @throws \Exception
	 */
	public function bindParams(array $params = array())
	{
		foreach ($params as $p) {
			if(array_key_exists(self::NAME_KEY, $p) && array_key_exists(self::NAME_VALUE, $p)) {
				if(array_key_exists(self::NAME_VALUE_TYPE, $p)) {
					$this->query->bindValue($p[self::NAME_KEY], $p[self::NAME_VALUE], $p[self::NAME_VALUE_TYPE]);
				} else {
					$this->query->bindValue($p[self::NAME_KEY], $p[self::NAME_VALUE]);
				}
			} else {
				throw new \Exception("Not Allowed placeholder format");
			}
		}
	}

	/**
	 * @return result
	 */
	public function execute()
	{
		return $this->query->execute();
	}

	/**
	 * @return bool|\PDOStatement|null
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @return \PDO|null
	 */
	public function getDriver()
	{
		return $this->pdo;
	}
}