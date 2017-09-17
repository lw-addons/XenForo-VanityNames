<?php

class LiamW_VanityNames_Extend_ControllerPublic_Account extends XFCP_LiamW_VanityNames_Extend_ControllerPublic_Account
{
	public function actionPersonalDetails()
	{
		$response = parent::actionPersonalDetails();

		if ($response instanceof XenForo_ControllerResponse_View && $response->subView)
		{
			$response->subView->params['canEditVanityName'] = XenForo_Visitor::getInstance()
				->hasPermission('general', 'liam_vanitynames_use');
		}

		return $response;
	}

	public function actionPersonalDetailsSave()
	{
		$visitor = XenForo_Visitor::getInstance();

		if ($visitor->hasPermission('general', 'liam_vanitynames_use'))
		{
			XenForo_Application::set('saveVanityName',
				$this->_input->filterSingle('vanity_name', XenForo_Input::STRING));
		}

		return parent::actionPersonalDetailsSave();
	}
}

/*class XFCP_LiamW_VanityNames_Extend_ControllerPublic_Account extends XenForo_ControllerPublic_Account
{
}*/