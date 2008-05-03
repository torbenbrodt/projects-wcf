<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Shows the log of project installations
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.projects
 */
class UserGroupsPageProjectListener implements EventListener {
	protected $log = '';

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($eventName == 'readData') {
			if(WCF::getUser()->getPermission('admin.user.canReadProjectLog')) {
				$this->log = file_get_contents(WCF_DIR.'lib/acp/project.log');
			}
			
		}
		else if ($eventName == 'assignVariables') {
			if($this->log) {
				WCF::getTPL()->assign('log', $this->log);
				WCF::getTPL()->append('additionalFields', WCF::getTPL()->fetch('userGroupsPageProject'));
			}
		}
	}
}
?>
