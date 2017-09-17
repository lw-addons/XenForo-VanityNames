<?php

class LiamW_VanityNames_Extend_DataWriter_User extends XFCP_LiamW_VanityNames_Extend_DataWriter_User
{
	protected function _getFields()
	{
		$original = parent::_getFields();
		$original['xf_user']['vanity_name'] = array('type' => self::TYPE_STRING, 'maxLength' => 50, 'verification' => array('$this', '_verifyVanityName'));

		return $original;
	}

	protected function _verifyVanityName($name)
	{
		$name = strtolower($name);

		if ($name === $this->getExisting('vanity_name'))
		{
			return true; // unchanged, always pass
		}

		if ($name == "")
		{
			return true; // user deleted vanity name, or didn't set one. The addon doesn't allow empty vanity names to be processed.
		}

		$restrictedNames = array_map('strtolower', XenForo_Application::getOptions()->liam_vanitynames_restrictednames);

		if (in_array($name, $restrictedNames) && !$this->getOption(self::OPTION_ADMIN_EDIT)) // admins can set restricted names from admin edit
		{
			$this->error(new XenForo_Phrase('liam_vanitynames_restricted'), 'vanity_name');
		}

		/* @var $vanityNameModel LiamW_VanityNames_Model_VanityNames */
		$vanityNameModel = XenForo_Model::create('LiamW_VanityNames_Model_VanityNames');

		if ($vanityNameModel->getUserFromName($name))
		{
			$this->error(new XenForo_Phrase('liam_vanitynames_notunique'), 'vanity_name');
		}

		$validChars = array('-');

		if (!ctype_alnum(str_replace($validChars, '', $name)))
		{
			$this->error(new XenForo_Phrase('liam_vanitynames_invalidchars'), 'vanity_name');
		}

		$censoredName = XenForo_Helper_String::censorString($name);
		if ($censoredName !== $name)
		{
			$this->error(new XenForo_Phrase('please_enter_name_that_does_not_contain_any_censored_words'), 'vanity_name');
		}

		$request = XenForo_Application::getFc()->getRequest();
		$match = XenForo_Application::getFc()->getDependencies()->route($request, $name);

		if ($match && $match->getControllerName())
		{
			$this->error(new XenForo_Phrase('liam_vanitynames_name_cannot_be_route'), 403);
		}

		return true;
	}

	protected function _preSave()
	{
		if (XenForo_Application::isRegistered('saveVanityName'))
		{
			$this->set('vanity_name', XenForo_Application::get('saveVanityName'));
		}

		return parent::_preSave();
	}

}

//class XFCP_LiamW_VanityNames_Extend_DataWriter_User extends XenForo_DataWriter_User {}