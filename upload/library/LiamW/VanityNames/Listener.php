<?php

class LiamW_VanityNames_Listener
{
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

		if (empty($vanityName) || !preg_match("/^[a-zA-Z0-9-\\pL]+$/u", $vanityName))
		{
			// If it isn't valid, don't treat as vanity name.
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