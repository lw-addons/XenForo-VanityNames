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
			'library/LiamW/VanityNames/Deferred/VanityNameRegeneration.php' => '682807c0a12184f93d8111161c9c8685',
			'library/LiamW/VanityNames/Extend/ControllerAdmin/User.php' => '842be084a95184765b04b3c12ca8b63e',
			'library/LiamW/VanityNames/Extend/ControllerPublic/Account.php' => 'f65a4cc0b5610f6a19ec3fbc4bab4df9',
			'library/LiamW/VanityNames/Extend/DataWriter/User.php' => '679a4b12303f0108e4c954cb8f8c197d',
			'library/LiamW/VanityNames/Extend/Model/User.php' => 'd42ffd2327fdafb9bd386a9ca7e4705a',
			'library/LiamW/VanityNames/Extend/Route/Prefix/Members.php' => '0d08dae2c6b0a6d5fee9235d492a947c',
			'library/LiamW/VanityNames/Installer.php' => 'a46a7d32eca065d9f93e75f692c5d01f',
			'library/LiamW/VanityNames/Listener.php' => 'e1dfc8c61283acc12378ee510b35d741',
			'library/LiamW/VanityNames/Option/RestrictedWords.php' => '2dead049024a0950a3b7e7c5fd51fb5c',
		);
	}
}