<?php

class LiamW_VanityNames_Extend_ControllerPublic_Account extends XFCP_LiamW_VanityNames_Extend_ControllerPublic_Account
{
	public function actionPersonalDetailsSave()
	{
		$visitor = XenForo_Visitor::getInstance();

		if ($visitor->hasPermission('general', 'liam_vanitynames_use'))
			XenForo_Application::set('saveVanityName', $this->_input->filterSingle('vanity_name', XenForo_Input::STRING));

		return parent::actionPersonalDetailsSave();
	}
}

//class XFCP_LiamW_VanityNames_Extend_ControllerPublic_Account extends XenForo_ControllerPublic_Account {}