<?php

abstract class LiamW_VanityNames_Option_PrefixSuffix
{
	public static function verifyOption(&$value, XenForo_DataWriter $dw, $fieldName)
	{
		$value = trim($value);

		if (strpos($value, ' ') !== false)
		{
			$dw->error(new XenForo_Phrase('vanityNames_no_spaces_allowed_in_prefix_and_suffix'), $fieldName);

			return false;
		}

		return true;
	}
}