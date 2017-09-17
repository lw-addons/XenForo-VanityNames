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
			'library/LiamW/VanityNames/Deferred/VanityNameRegeneration.php' => 'c69a696dbe4e195864b4285eda2b1245',
			'library/LiamW/VanityNames/Extend/ControllerAdmin/User.php' => '842be084a95184765b04b3c12ca8b63e',
			'library/LiamW/VanityNames/Extend/ControllerPublic/Account.php' => 'f65a4cc0b5610f6a19ec3fbc4bab4df9',
			'library/LiamW/VanityNames/Extend/DataWriter/User.php' => '6b0d8c4325aa13261b4cca31a3ac645c',
			'library/LiamW/VanityNames/Extend/Model/User.php' => '893c0e58fb0849380197898f56e7263b',
			'library/LiamW/VanityNames/Extend/Route/Prefix/Members.php' => '0d08dae2c6b0a6d5fee9235d492a947c',
			'library/LiamW/VanityNames/Installer.php' => 'a46a7d32eca065d9f93e75f692c5d01f',
			'library/LiamW/VanityNames/Listener.php' => '7e2156411cb573485a38f5c588425267',
			'library/LiamW/VanityNames/Option/RestrictedWords.php' => '2dead049024a0950a3b7e7c5fd51fb5c',
		);
	}
}