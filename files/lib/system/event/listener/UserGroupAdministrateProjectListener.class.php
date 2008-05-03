<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/group/GroupEditor.class.php');

/**
 * Shows additional project forms on the usercp page for projectleaders
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.projects
 */
class UserGroupAdministrateProjectListener implements EventListener {
	protected $project = 0;
	protected $licenses = array(); // for list

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($eventName == 'readData') {
			$group = new GroupEditor($eventObj->groupID);
			$this->project = $group->project;
			
			$sql = "SELECT 		l.licenseID, l.licenseName, l.licenseURL
				FROM 		wcf".WCF_N."_group_to_licenses
				NATURAL JOIN	wcf".WCF_N."_projectLicenses l
				WHERE 		groupID = ".intval($eventObj->groupID)." ;";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$this->licenses[] = $row;
			}
			
		}
		else if ($eventName == 'assignVariables') {
			if($this->project) {
				WCF::getTPL()->assign(array(
					'groupID' => $eventObj->groupID,
					'licenses' => $this->licenses
				));
				WCF::getTPL()->append('additionalFields', WCF::getTPL()->fetch('userGroupAdministrateProject'));
			}
		}
	}
}
?>
