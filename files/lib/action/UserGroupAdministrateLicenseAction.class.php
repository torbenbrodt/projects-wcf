<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/user/group/GroupApplicationEditor.class.php');

/**
 * adds/removes licenses from a project.
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.projects
 */
class UserGroupAdministrateLicenseAction extends AbstractAction {
	protected $licenseAddString = '';
	protected $licenseAddIDs = array();
	protected $licenseRemoveIDs = array();
	protected $groupID = 0;
	
	/**
	 * my validation
	 */
	protected function myValidate() {
		// check existance
		$group = new Group($this->groupID);
		if (!$group->groupID) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
		
		// check permission
		if (!GroupApplicationEditor::isGroupLeader(WCF::getUser()->userID, $this->groupID)) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			throw new PermissionDeniedException();
		}

		// explode multiple licenses to an array
		$arr = explode(',', $this->licenseAddString);
		
		// loop through licenses
		foreach ($arr as $license) {
			$license = StringUtil::trim($license);
			if (empty($license)) continue;
			
			$sql = "SELECT 			licenseID
				FROM 			wcf".WCF_N."_projectLicenses 
				NATURAL LEFT JOIN	wcf".WCF_N."_group_to_licenses
				WHERE 			licenseName = '".escapeString($license)."'
				AND			ISNULL(groupID) ;";
			$row = WCF::getDB()->getFirstRow($sql);
			if($row) {
				$this->licenseAddIDs[] = $row['licenseID'];
			}
		}
	}
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_POST['licenseAddString'])) $this->licenseAddString = StringUtil::trim($_POST['licenseAddString']);
		if (isset($_POST['licenseRemoveIDs'])) $this->licenseRemoveIDs = ArrayUtil::toIntegerArray($_POST['licenseRemoveIDs']);
		if (isset($_POST['groupID'])) $this->groupID = intval($_POST['groupID']);

		$this->myValidate();
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		AbstractAction::execute();
		
		// add licenses
		foreach($this->licenseAddIDs as $licenseID) {
			$sql = "INSERT INTO	wcf".WCF_N."_group_to_licenses
						(groupID, licenseID)
				VALUES		({$this->groupID}, {$licenseID})";
			WCF::getDB()->sendQuery($sql);
		}

		// remove licenses
		if (count($this->licenseRemoveIDs)) {
			$sql = "DELETE FROM	wcf".WCF_N."_group_to_licenses
				WHERE		licenseID IN (".implode(',', $this->licenseRemoveIDs).")
						AND groupID = ".$this->groupID;
			WCF::getDB()->sendQuery($sql);
		}
		$this->executed();

		header('Location: '.FileUtil::addTrailingSlash(dirname(WCF::getSession()->requestURI)).'index.php?form=UserGroupAdministrate&groupID='.$this->groupID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
