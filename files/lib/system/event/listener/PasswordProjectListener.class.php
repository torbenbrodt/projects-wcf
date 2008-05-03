<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/acp/form/UserAddForm.class.php');
require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');

/**
 * password of the own user will be saved as sha1 string in an extra column
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.system.event.listener
 */
class PasswordProjectListener implements EventListener {
	protected $projectPassword = 0;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		switch($className) {
			case 'PasswordChangeForm':
				$password = $eventObj->newPassword;
				$editor = WCF::getUser()->getEditor();
				break;

			case 'UserLoginForm':
			case 'UserAddForm':
			case 'RegisterForm':
			case 'UserEditForm':
				$password = $eventObj->password;
				$editor = new UserEditor($eventObj->user->userID);
				break;
		}
			
		if(isset($password) && !empty($password)) {
			$additionalFields = array();
			$additionalFields['projectPassword'] = sha1($password);
			$editor->updateFields($additionalFields);
		}
	}
}
?>
