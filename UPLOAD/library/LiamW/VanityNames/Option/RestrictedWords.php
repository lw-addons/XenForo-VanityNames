<?php

abstract class LiamW_VanityNames_Option_RestrictedWords
{

    /**
     * Render option for options page
     * @param XenForo_View $view The view class
     * @param $fieldPrefix The field prefix
     * @param array $preparedOption Information about option
     * @param $canEdit If true, display edit link
     * @return XenForo_Template_Abstract Template object
     */
    public static function renderOption(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
	{
		$words = $preparedOption['option_value'];

		$editLink = $view->createTemplateObject('option_list_option_editlink', array(
				'preparedOption' => $preparedOption,
				'canEditOptionDefinition' => $canEdit
		));

		return $view->createTemplateObject('liam_vanitynames_option_template_restrictedwords', array(
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
			if($word == '')
			{
				unset($words[$key]);
			}
		}

		return true;
	}

}