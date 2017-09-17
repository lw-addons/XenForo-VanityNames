<?php

class LiamW_VanityNames_Addon
{
	/**
	 * Install the addon. Checks for correct XenForo version, and creates tables.
	 *
	 * @param $installedAddon
	 *
	 * @throws XenForo_Exception
	 */
	public static function install($installedAddon)
	{
		if (XenForo_Application::$versionId < 1020070)
		{
			throw new XenForo_Exception("This addon requires XenForo 1.2.0+. Please upgrade XenForo.",
				true);
		}

		$version = is_array($installedAddon) ? $installedAddon['version_id'] : 0;

		$userInstaller = LiamW_Shared_DatabaseSchema_Abstract2::create('LiamW_VanityNames_DatabaseSchema_User');
		$userInstaller->install($version);
	}

	/**
	 * Uninstall, remove tables...
	 */
	public static function uninstall()
	{
		$userInstaller = LiamW_Shared_DatabaseSchema_Abstract2::create('LiamW_VanityNames_DatabaseSchema_User');
		$userInstaller->uninstall();
	}

	/**
	 * Override the router, to make vanity names go to correct place.
	 *
	 * @param XenForo_FrontController $fc
	 * @param XenForo_RouteMatch      $routeMatch
	 */
	public static function routerOverride(XenForo_FrontController $fc, XenForo_RouteMatch &$routeMatch)
	{
		$routePath = $fc->getDependencies()->getRouter()->getRoutePath($fc->getRequest());
		$parts = array_filter(explode('/', $routePath));

		if (count($parts) > 1)
		{
			return;
		}

		$vanityName = reset($parts);

		if ($vanityName == '')
		{
			return;
		}

		/* @var $userModel LiamW_VanityNames_Extend_Model_User */
		$userModel = XenForo_Model::create('XenForo_Model_User');

		$user = $userModel->getUserByVanityName($vanityName);

		if ($user)
		{
			$routeMatch->setSections('members');
			$routeMatch->setControllerName('XenForo_ControllerPublic_Member');
			$routeMatch->setAction('');
			$fc->getRequest()->setParam('user_id', $user['user_id']);
		}
	}

	public static function changeLogField(XenForo_Model_UserChangeLog $logModel, array &$field)
	{
		if ($field['field'] == 'vanity_name')
		{
			$field['name'] = new XenForo_Phrase('liam_vanityname');
		}
	}

	public static function extendUserDataWriter($class, array &$extend)
	{
		$extend[] = 'LiamW_VanityNames_Extend_DataWriter_User';
	}

	public static function extendUserModel($class, array &$extend)
	{
		$extend[] = 'LiamW_VanityNames_Extend_Model_User';
	}

	public static function extendAccountController($class, array &$extend)
	{
		$extend[] = 'LiamW_VanityNames_Extend_ControllerPublic_Account';
	}

	public static function extendUserAdminController($class, array &$extend)
	{
		$extend[] = 'LiamW_VanityNames_Extend_ControllerAdmin_User';
	}

	public static function extendMembersRoute($class, array &$extend)
	{
		$extend[] = 'LiamW_VanityNames_Extend_Route_Prefix_Members';
	}
}