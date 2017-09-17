<?php

class LiamW_VanityNames_Extend_DataWriter_User extends XFCP_LiamW_VanityNames_Extend_DataWriter_User
{
	protected function _getFields()
	{
		$existingFields = parent::_getFields();
		$existingFields['xf_user']['vanity_name'] = array(
			'type' => self::TYPE_STRING,
			'maxLength' => 50,
			'verification' => array(
				$this,
				'_verifyVanityName'
			)
		);

		return $existingFields;
	}

	/**
	 * Check the entered name to make sure it is valid. Checks non-modified names in case of modification to checks.
	 *
	 * @param $vanityName
	 *
	 * @return bool
	 * @throws XenForo_Exception
	 */
	protected function _verifyVanityName(&$vanityName)
	{
		// Change to lower case
		$vanityName = mb_strtolower($vanityName);

		// Empty name
		if (empty($vanityName))
		{
			return true;
		}

		if (!preg_match(LiamW_VanityNames_Listener::$vanityNameRegex, $vanityName))
		{
			$this->error(new XenForo_Phrase('liam_vanitynames_invalid_format'), 'vanity_name');

			return false;
		}

		// Change restricted names to lower case
		$restrictedNames = array_map('mb_strtolower',
			XenForo_Application::getOptions()->get('vanityNames_restrictedNames'));

		// Admins can use restricted names
		if (!$this->getOption(self::OPTION_ADMIN_EDIT) && in_array($vanityName, $restrictedNames))
		{
			$this->error(new XenForo_Phrase('liam_vanitynames_restricted'), 'vanity_name');

			return false;
		}

		/** @var $userModel LiamW_VanityNames_Extend_Model_User */
		$userModel = $this->_getUserModel();

		// Vanity names must be unique
		if ($vanityName != $this->getExisting('vanity_name') && $userModel->getUserByVanityName($vanityName))
		{
			$this->error(new XenForo_Phrase('liam_vanitynames_notunique'), 'vanity_name');

			return false;
		}

		// Don't allow vanity names to contain censored words.
		$censoredName = XenForo_Helper_String::censorString($vanityName);
		if ($censoredName !== $vanityName)
		{
			$this->error(new XenForo_Phrase('please_enter_name_that_does_not_contain_any_censored_words'),
				'vanity_name');

			return false;
		}

		// Don't allow users to use an existing route as a vanity name.
		$match = XenForo_Application::getFc()->getDependencies()
			->route(new Zend_Controller_Request_Http(), $vanityName);
		if ($match && $match->getControllerName())
		{
			$this->error(new XenForo_Phrase('liam_vanitynames_name_cannot_be_route'), 'vanity_name');

			return false;
		}

		return true;
	}

	protected function _preSave()
	{
		if (XenForo_Application::isRegistered('saveVanityName'))
		{
			$this->set('vanity_name', XenForo_Application::get('saveVanityName'));
		}

		if ($this->isInsert() && XenForo_Application::getOptions()->get('vanityNames_auto_apply', 'enabled'))
		{
			$this->setVanityNameFromUsername();
		}

		parent::_preSave();
	}

	public function setVanityNameFromUsername()
	{
		$usernamePlain = preg_replace(array(
			"/[_ ]/u",
			"/[^a-zA-Z0-9-\\pL]/u"
		), array(
			'-',
			''
		), $this->get('username'));

		/** @var $userModel LiamW_VanityNames_Extend_Model_User */
		$userModel = $this->_getUserModel();

		// Make generated name unique by appending a digit to the end of it until it is unique.
		$count = 1;

		while ($usernamePlain != $this->get('vanity_name') && $userModel->getUserByVanityName($usernamePlain))
		{
			$usernamePlain .= $count;

			$count++;
		}

		$this->set('vanity_name', mb_strtolower($usernamePlain));
	}

}

if (false)
{
	class XFCP_LiamW_VanityNames_Extend_DataWriter_User extends XenForo_DataWriter_User
	{
	}
}