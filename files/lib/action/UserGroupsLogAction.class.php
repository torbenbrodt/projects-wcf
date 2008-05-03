<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/user/group/GroupApplicationEditor.class.php');

/**
 * clears log
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.projects
 */
class UserGroupsLogAction extends AbstractAction {
	protected $remove = false;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// check permission
		if (WCF::getUser()->getPermission('admin.user.canDeleteProjectLog')) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			throw new PermissionDeniedException();
		}

		$this->remove = true;
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		AbstractAction::execute();
		
		if($this->remove) {
			system('sh '.WCF_DIR.'lib/action/acp/projectLogRemove.sh '.WCF_DIR);
		}

		header('Location: '.FileUtil::addTrailingSlash(dirname(WCF::getSession()->requestURI)).'index.php?form=UserGroups'.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
