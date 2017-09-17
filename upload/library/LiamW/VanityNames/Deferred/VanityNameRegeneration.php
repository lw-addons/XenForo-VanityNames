<?php

class LiamW_VanityNames_Deferred_VanityNameRegeneration extends XenForo_Deferred_Abstract
{
	public function execute(array $deferred, array $data, $targetRunTime, &$status)
	{
		$data = array_merge(array(
			'position' => 0,
			'batch' => 70
		), $data);
		$data['batch'] = max(1, $data['batch']);

		/* @var $userModel LiamW_VanityNames_Extend_Model_User */
		$userModel = XenForo_Model::create('XenForo_Model_User');

		$userIds = $userModel->getUserIdsInRange($data['position'], $data['batch']);
		if (sizeof($userIds) == 0)
		{
			return true;
		}

		foreach ($userIds AS $userId)
		{
			$data['position'] = $userId;

			/* @var $userDw LiamW_VanityNames_Extend_DataWriter_User */
			$userDw = XenForo_DataWriter::create('XenForo_DataWriter_User', XenForo_DataWriter::ERROR_SILENT);
			if ($userDw->setExistingData($userId))
			{
				$userDw->setVanityNameFromUsername();
				$userDw->save();
			}
		}

		$actionPhrase = new XenForo_Phrase('vanityNames_regenerating');
		$typePhrase = new XenForo_Phrase('vanityNames_vanity_names');
		$status = sprintf('%s... %s (%s)', $actionPhrase, $typePhrase, XenForo_Locale::numberFormat($data['position']));

		return $data;
	}

	public function canCancel()
	{
		return true;
	}
}