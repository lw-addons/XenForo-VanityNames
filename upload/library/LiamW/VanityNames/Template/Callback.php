<?php

class LiamW_VanityNames_Template_Callback
{
	public static function getVanityNameForDisplay($contents, array $params, XenForo_Template_Abstract $template)
	{
		$options = XenForo_Application::getOptions();

		if ($options->vanityNames_profile_name && $params['user']['vanity_name'])
		{
			$vanityNamesCss = $options->vanityNames_profile_name_css;

			return XenForo_Template_Helper_Core::helperUserNameHtml($params['user'],
				$options->vanityNames_prefix . $params['user']['vanity_name'] . $options->vanityNames_suffix, false,
				array('style' => $vanityNamesCss));
		}

		return '';
	}
}