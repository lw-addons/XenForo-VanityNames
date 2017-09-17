<?php

class LiamW_VanityNames_FileSums
{
	public static function addHashes(XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
	{
		$hashes += self::getHashes();
	}

	/**
	 * @return array
	 */
	public static function getHashes()
	{
		return array(
			'library/LiamW/VanityNames/Deferred/VanityNameRegeneration.php' => '486f78645a37d539682b4b8c84bdc4e5',
			'library/LiamW/VanityNames/Extend/ControllerAdmin/User.php' => '842be084a95184765b04b3c12ca8b63e',
			'library/LiamW/VanityNames/Extend/ControllerPublic/Account.php' => 'f65a4cc0b5610f6a19ec3fbc4bab4df9',
			'library/LiamW/VanityNames/Extend/DataWriter/User.php' => 'c2fe964dabb7206c7f4693812fa3bb8f',
			'library/LiamW/VanityNames/Extend/Model/User.php' => '7ac918230074c4c6e35aae034a1f973d',
			'library/LiamW/VanityNames/Extend/Route/Prefix/Members.php' => '1417a8e42546c4a654eed1d839c44014',
			'library/LiamW/VanityNames/Installer.php' => '57adce361965acbedb8a6699ad9c7b7a',
			'library/LiamW/VanityNames/Listener.php' => '54bec138e0a69baa6c0015eb89e655f9',
			'library/LiamW/VanityNames/Option/PrefixSuffix.php' => '04432f168cfb5d0934b53a3db710656e',
			'library/LiamW/VanityNames/Option/RestrictedWords.php' => '2dead049024a0950a3b7e7c5fd51fb5c',
			'library/LiamW/VanityNames/Template/Callback.php' => 'dd578bc000f3f811550f523235bc98cc',
		);
	}
}