<?php

class LiamW_VanityNames_Model_VanityNames extends XenForo_Model
{
	/**
	 * Gets the user from the vanity name if there is one.
	 *
	 * @param string $vanityname
	 * @return array|null
	 */
	public function getUserFromName($vanityname)
	{
		return $this->_getDb()
			->fetchRow("SELECT * FROM xf_user WHERE vanity_name=?", array($vanityname));
	}

	public function getVanityNameByUserId($userId)
	{
		return $this->_getDb()->fetchOne("SELECT vanity_name FROM xf_user WHERE user_id=?", array($userId));
	}

}