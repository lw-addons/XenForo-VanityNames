<?php

class LiamW_VanityNames_DatabaseSchema_User extends LiamW_Shared_DatabaseSchema_Abstract2
{
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
	protected function _getInstallSql()
	{
		return array(
			0 => "ALTER TABLE xf_user ADD vanity_name VARCHAR(50) NOT NULL DEFAULT ''"
		);
	}

	/**
	 * Get the uninstall SQL.
	 *
	 * Unlike the install SQL, this should return an array of SQL code to run. All code will be run.
	 *
	 * @return array
	 */
	protected function _getUninstallSql()
	{
		return array(
			"ALTER TABLE xf_user DROP vanity_name"
		);
	}

	protected function _postInstall()
	{
		if ($this->_installedVersion > 0 && $this->_installedVersion <= 10201)
		{
			$this->_db->query("ALTER TABLE xf_user CHANGE vanity_name vanity_name VARCHAR(50) NOT NULL DEFAULT ''");
		}
	}
}