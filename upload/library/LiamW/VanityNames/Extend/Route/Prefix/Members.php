<?php

class LiamW_VanityNames_Extend_Route_Prefix_Members extends XFCP_LiamW_VanityNames_Extend_Route_Prefix_Members
{
	public function buildLink($originalPrefix, $outputPrefix, $action, $extension, $data, array &$extraParams)
	{
		$forceVanity = false;

		if (isset($extraParams['force_vanity']))
		{
			$forceVanity = true;
			unset($extraParams['force_vanity']);
		}

		// Use vanity name if there's no action, the vanity name is defined and if the mode is park OR we're forcing vanity name
		// This is what causes the park/redirect to be applied - the member controller canonicalizes the URL, calling this method.
		if (!$action && !empty($data['vanity_name']) && (XenForo_Application::getOptions()->vanityNames_mode == 'park' || $forceVanity))
		{
			return XenForo_Link::buildBasicLink($data['vanity_name'], false, $extension);
		}

		return parent::buildLink($originalPrefix, $outputPrefix, $action, $extension, $data,
			$extraParams);
	}
}

if (false)
{
	class XFCP_LiamW_VanityNames_Extend_Route_Prefix_Members extends XenForo_Route_Prefix_Members
	{
	}
}