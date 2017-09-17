<?php

abstract class LiamW_VanityNames_Option_RestrictedWords
{
	public static function renderOption(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
	{
		$words = $preparedOption['option_value'];

		$editLink = $view->createTemplateObject('option_list_option_editlink', array(
			'preparedOption' => $preparedOption,
			'canEditOptionDefinition' => $canEdit
		));

		return $view->createTemplateObject('vanityNames_option_restrictedWords', array(
			'fieldPrefix' => $fieldPrefix,
			'listedFieldName' => $fieldPrefix . '_listed[]',
			'preparedOption' => $preparedOption,
			'formatParams' => $preparedOption['formatParams'],
			'editLink' => $editLink,

			'words' => $words,
			'nextCounter' => count($words)
		));
	}

	public static function verifyOption(array &$words, XenForo_DataWriter $dw, $fieldName)
	{
		foreach ($words as $key => $word)
		{
			if ($word == '')
			{
				unset($words[$key]);
			}
		}

		return true;
	}

}