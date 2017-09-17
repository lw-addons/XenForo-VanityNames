<?php

/**
 * Abstract class for handling SQL installation code for add-ons.
 *
 * @author Liam W
 */
abstract class LiamW_Shared_DatabaseSchema_Abstract2
{
	/**
	 * Holds the versionId set in the constructor.
	 *
	 * @var int
	 */
	protected $_installedVersion;

	/**
	 * If false, returns false instead of throwing exception on error.
	 *
	 * @var bool
	 */
	protected $_throw = true;

	/**
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_db = null;

	/**
	 * Factory method to create class. This should be used, the constructor is private.
	 *
	 * @param string $class            Name of class to instantiate.
	 * @param int    $installedVersion Addon versionId.
	 * @param bool   $throw            If false, no exception will be thrown on error.
	 *
	 * @return LiamW_Shared_DatabaseSchema_Abstract2|false
	 * @throws XenForo_Exception
	 */
	final public static function create($class, $installedVersion = 0, $throw = true)
	{
		$class = XenForo_Application::resolveDynamicClass($class);

		if (!$class)
		{
			return false;
		}

		$instance = new $class($installedVersion, $throw);
		$instance->_db = XenForo_Application::getDb();

		return $instance;
	}

	final protected function __construct($version = 0, $throw = true)
	{
		$this->_installedVersion = $version;
		$this->_throw = $throw;
	}

	/**
	 * Install the tables from the {@link _getInstallSql} method.
	 *
	 * @param int $installedVersion Overrides the versionId passed to the constructor.
	 *
	 * @return bool True on success, false on failure if {@link $_throw} is false.
	 * @throws XenForo_Exception
	 */
	final public function install($installedVersion = null)
	{
		if ($installedVersion != null)
		{
			$this->_installedVersion = $installedVersion;
		}

		if ($this->_preInstall() === false)
		{
			return false;
		}

		$installSql = $this->_getInstallSql();
		$db = $this->_db;
		XenForo_Db::beginTransaction($db);

		foreach ($installSql as $version => $sqlArray)
		{
			if ($this->_installedVersion <= $version)
			{
				if (!is_array($sqlArray))
				{
					$sqlArray = array($sqlArray);
				}

				$this->_executeSqlArray($sqlArray, $db, $version);
			}
		}

		XenForo_Db::commit($db);

		$this->_postInstall();

		return true;
	}

	/**
	 * Uninstalls tables based on SQL in the _getUninstallSql() method.
	 *
	 * @see _getUninstallSql()
	 */
	final public function uninstall()
	{
		if ($this->_preUninstall() === false)
		{
			return false;
		}

		$uninstallSql = $this->_getUninstallSql();

		$db = $this->_db;
		XenForo_Db::beginTransaction($db);

		$this->_executeSqlArray($uninstallSql, $db);

		XenForo_Db::commit($db);

		$this->_postUninstall();

		return true;
	}

	final protected function _executeSqlArray(array $sqlArray, Zend_Db_Adapter_Abstract $db, $version = null)
	{
		if (!$db)
		{
			throw new XenForo_Exception("Invalid db object passed to executeSqlArray method.");
		}

		foreach ($sqlArray as $sql)
		{
			try
			{
				$db->query($sql);
			} catch (Zend_Db_Exception $e)
			{
				return $this->_error($e->getMessage(), $version, $sql, $db, $e);
			}
		}

		return true;
	}

	/**
	 * @param $sqlError string The error from the SQL.
	 * @param $version  int The version being installed.
	 * @param $sql      string The SQL code that caused the error.
	 * @param $db       Zend_Db_Adapter_Abstract The database instance.
	 * @param $e        Exception The exception instance.
	 *
	 * @return bool
	 * @throws XenForo_Exception
	 */
	final protected function _error($sqlError, $version, $sql, Zend_Db_Adapter_Abstract $db, Exception $e)
	{
		XenForo_Db::rollbackAll($db);

		if ($this->_throw)
		{
			throw new XenForo_Exception($this->_getErrorMessage($sqlError, $version, $sql), true);
		}
		else
		{
			return false;
		}
	}

	/**
	 * @param $sqlError string The error message from the database adapter.
	 * @param $version  int The version being run. If null, error occurred during uninstall.
	 * @param $sql      string The SQL string the error occurred on.
	 *
	 * @return string
	 */
	protected function _getErrorMessage($sqlError, $version, $sql)
	{
		return "An error occurred while running SQL in class " . __CLASS__ . " <!-- Error: $sqlError | SQL Version: $version | SQL: $sql -->";
	}

	/**
	 * Runs before SQL installation. If this returns boolean false, the SQL installation is aborted.
	 *
	 * @return boolean
	 */
	protected function _preInstall()
	{
		return true;
	}

	/**
	 * Called after all install SQL has been executed. <b>Not called if an error occurs!</b>
	 *
	 * Designed to be overridden by child classes.
	 */
	protected function _postInstall()
	{
	}

	/**
	 * Runs before SQL uninstallation. If this returns boolean false, the SQL uninstallation is aborted.
	 *
	 * @return boolean
	 */
	protected function _preUninstall()
	{
		return true;
	}

	/**
	 * Called after all uninstall SQL has been executed. <b>Not called if an error occurs!</b>
	 *
	 * Designed to be overridden by child classes.
	 */
	protected function _postUninstall()
	{
	}

	/**
	 * Get the install SQL.
	 *
	 * The array should be an associative array of fields, like so:
	 *
	 * versionId =>
	 *        array =>
	 *                SQL Strings
	 *
	 *
	 * @return array
	 */
	abstract protected function _getInstallSql();

	/**
	 * Get the uninstall SQL.
	 *
	 * Unlike the install SQL, this should return an array of SQL code to run. All code will be run.
	 *
	 * @return array
	 */
	abstract protected function _getUninstallSql();
}