<?php

class LiamW_VanityNames_Extend_ControllerAdmin_User extends XFCP_LiamW_VanityNames_Extend_ControllerAdmin_User
{
	public function actionSave()
	{
		XenForo_Application::set('saveVanityName', $this->_input->filterSingle('vanity_name', XenForo_Input::STRING));

		return parent::actionSave();
	}
}