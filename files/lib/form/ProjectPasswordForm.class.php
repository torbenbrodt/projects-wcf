<?php
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');

/**
 * Form to save the password as sha-1 hash, without salt
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.acp.form
 */
class ProjectPasswordForm extends AbstractForm {
	public $templateName = 'projectPassword';
	public $oldPassword = '';
	protected $success = false;
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['oldPassword'])) $this->oldPassword = $_POST['oldPassword'];
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		if (empty($this->oldPassword)) {
			throw new UserInputException('oldPassword');
		}
		
		if (!WCF::getUser()->checkPassword($this->oldPassword)) {
			throw new UserInputException('oldPassword', 'false');
		}
		
		parent::validate();
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();

		// update user
		$additionalFields = array();
		$additionalFields['projectPassword'] = sha1($this->oldPassword);
		
		$editor = WCF::getUser()->getEditor();
		$editor->updateFields($additionalFields);
		$this->success = true;
		
		$this->saved();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		if(WCF::getUser()->projectPassword || $this->success) {
			// show success message
			WCF::getTPL()->assign('success', true);
		}
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!WCF::getUser()->userID) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			throw new PermissionDeniedException();
		}

		// set active header menu item
                HeaderMenu::setActiveMenuItem('wcf.header.menu.projects');
                
                // show form
        	parent::show();
	}
}
?>
