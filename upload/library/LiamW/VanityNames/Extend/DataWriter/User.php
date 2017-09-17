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
				'$this',
				'_verifyVanityName'
			)
		);

		return $existingFields;
	}

	protected function _verifyVanityName($vanityName)
	{
		// Change to lower case
		$vanityName = mb_strtolower($vanityName);

		// Unchanged
		if ($vanityName === $this->getExisting('vanity_name'))
		{
			return true;
		}

		// Empty name
		if ($vanityName == "")
		{
			return true;
		}

		// Change restricted names to lower case
		$restrictedNames = array_map('mb_strtolower',
			XenForo_Application::getOptions()->get('vanityNames_restrictedNames'));

		// Admins can use restricted names
		if (in_array($vanityName, $restrictedNames) && !$this->getOption(self::OPTION_ADMIN_EDIT))
		{
			$this->error(new XenForo_Phrase('liam_vanitynames_restricted'), 'vanity_name');

			return false;
		}

		/** @var $userModel LiamW_VanityNames_Extend_Model_User */
		$userModel = $this->_getUserModel();

		// Vanity names must be unique
		if ($userModel->getUserByVanityName($vanityName))
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
		$request = XenForo_Application::getFc()->getRequest();
		$match = XenForo_Application::getFc()->getDependencies()->route($request, $vanityName);
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

		parent::_preSave();
	}

}

/*class XFCP_LiamW_VanityNames_Extend_DataWriter_User extends XenForo_DataWriter_User
{
}*/