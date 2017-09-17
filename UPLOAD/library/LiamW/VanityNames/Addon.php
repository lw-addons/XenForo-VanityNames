<?php

class LiamW_VanityNames_Addon
{
	/**
	 * Install the addon. Checks for correct xenforo version, and creates tables.
	 * @param $installedAddon
	 * @throws XenForo_Exception
	 */
	public static function install($installedAddon)
	{
		if (XenForo_Application::$versionId < 1020070)
		{
			throw new XenForo_Exception("This addon requires XenForo 1.2.0 stable or later. Please upgrade your XenForo install.", true);
		}

		$db = XenForo_Application::getDb();

		if (!$installedAddon)
		{
			$db->query("ALTER TABLE  `xf_user` ADD  `vanity_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Added by the Vanity Names addon';");
		}
	}

	/**
	 * Uninstall, remove tables...
	 */
	public static function uninstall()
	{
		$db = XenForo_Application::getDb();
		$db->query("ALTER TABLE `xf_user` DROP COLUMN `vanity_name`");
	}

	/**
	 * Override the router, to make vanity names go to correct place.
	 * @param XenForo_FrontController $fc
	 * @param XenForo_RouteMatch $routeMatch
	 */
	public static function routerOverride(XenForo_FrontController $fc, XenForo_RouteMatch &$routeMatch)
	{
		$mainForumUrl = parse_url(XenForo_Application::getOptions()->boardUrl, PHP_URL_HOST);
		$host = $fc->getRequest()->getHttpHost();

		if ($host != $mainForumUrl)
		{
			// check for subdomain
			$arrayHosts = explode('.', $host);

			if (sizeof($arrayHosts > 2) && !in_array('www', $arrayHosts))
			{
				header("Location: " . XenForo_Application::getOptions()->boardUrl . '/' . $arrayHosts[0]);
				print(ob_get_clean());
				exit();
			}
		}
		else
		{
			$slashCount = substr_count($fc->getRequest()->getRequestUri(), '/');

			if ($slashCount < 1 || $slashCount > 2)
			{
				return;
			}

			$vanityname = str_replace('/', '', $fc->getRequest()->getRequestUri());
			$vanityname = urldecode($vanityname);

			if ($vanityname == '')
			{
				return; //blank vanity name... BAD!
			}

			/* @var $model LiamW_VanityNames_Model_VanityNames */
			$model = XenForo_Model::create('LiamW_VanityNames_Model_VanityNames');

			$user = $model->getUserFromName($vanityname);

			if ($user)
			{
				$routeMatch->setControllerName('XenForo_ControllerPublic_Member');
				$routeMatch->setAction('');
				$fc->getRequest()->setParam('user_id', $user['user_id']);
			}
		}
	}

	/**
	 * Extender function
	 *
	 * @param $class Class name
	 * @param array $extend Array of class names
	 */
	public static function extend($class, array &$extend)
	{
		switch ($class)
		{
			case "XenForo_DataWriter_User":
				$extend[] = 'LiamW_VanityNames_Extend_DataWriter_User';
				break;
			case "XenForo_ControllerPublic_Account":
				$extend[] = 'LiamW_VanityNames_Extend_ControllerPublic_Account';
				break;
			case "XenForo_ControllerAdmin_User":
				$extend[] = 'LiamW_VanityNames_Extend_ControllerAdmin_User';
				break;
		}
	}

	public static function containerParams(&$templateName, array &$params, XenForo_Template_Abstract $template)
	{
		if ($templateName == 'account_personal_details')
		{
			$params['canEditVanityName'] = XenForo_Visitor::getInstance()
				->hasPermission('general', 'liam_vanitynames_use');
		}
	}
}