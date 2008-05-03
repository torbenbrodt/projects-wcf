<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/acp/form/GroupEditForm.class.php');

/**
 * ACP: additional project options in the group-configuration
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.projects
 */
class GroupProjectListener implements EventListener {
	protected $project = 0;
	protected $projectIntern = 0;
	protected $projectWebsite = "";
	protected $projectSvn = "";
	protected $projectShortName = "";
	
	protected $projectShortNameOld = "";
	protected $projectInternOld = 0;
	protected $groupLeadersOld = "";
	
	protected $groupLeaders = "";
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		switch ($eventName) {
		case 'readFormParameters':
			if (isset($_POST['project'])) $this->project = intval($_POST['project']);
			if (isset($_POST['projectIntern'])) $this->projectIntern = intval($_POST['projectIntern']);
			if (isset($_POST['projectInternOld'])) $this->projectInternOld = intval($_POST['projectInternOld']);
			if (isset($_POST['projectWebsite'])) $this->projectWebsite = $_POST['projectWebsite'];
			if (isset($_POST['projectSvn'])) $this->projectSvn = $_POST['projectSvn'];
			if (isset($_POST['projectShortName'])) $this->projectShortName = $_POST['projectShortName'];
			if (isset($_POST['projectShortNameOld'])) $this->projectShortNameOld = $_POST['projectShortNameOld'];
			if (isset($_POST['groupLeaders'])) $this->groupLeaders = $_POST['groupLeaders'];
			break;
		
		
		case 'validate':
			if($this->project) {
				if(empty($this->projectShortName)) {
					throw new UserInputException('projectShortName', 'notUnique');
				}
				
				// is there another project using the same shortname?
				$sql = "SELECT 	COUNT(*) AS c 
					FROM 	wcf".WCF_N."_group 
					WHERE 	projectShortName='{$this->projectShortName}'
					AND	groupID != {$eventObj->group->groupID};";
				$row = WCF::getDB()->getFirstRow($sql);

				if (intval($row['c']) > 0) {
					throw new UserInputException('projectShortName', 'notUnique');
				}
				
				// get group leaders
                                $this->groupLeadersOld = '';
                                $sql = "SELECT          user.username
                                        FROM            wcf".WCF_N."_group_leader leader
                                        LEFT JOIN       wcf".WCF_N."_user user
                                        ON              (leader.userID = user.userID)
                                        WHERE           leader.groupID = ".$eventObj->group->groupID."
                                        ORDER BY        user.username";
                                $result = WCF::getDB()->sendQuery($sql);
                                while ($row = WCF::getDB()->fetchArray($result)) {
                                        if (!empty($this->groupLeaders)) $this->groupLeaders .= ', ';
                                        $this->groupLeadersOld .= $row['username'];
                                }
			}
			
			// remove
			if($this->projectInternOld && !$this->projectIntern) {
				throw new UserInputException('cannot remove');
			}
			// move
			else if($this->projectInternOld && $this->projectIntern && $this->projectShortNameOld != $this->projectShortName) {
				throw new UserInputException('cannot move');
			}
			break;
		
		
		case 'save':
			$eventObj->additionalFields['project'] = $this->project;
			$eventObj->additionalFields['projectIntern'] = $this->projectIntern;
			$eventObj->additionalFields['projectWebsite'] = $this->projectWebsite;
			$eventObj->additionalFields['projectSvn'] = $this->projectSvn;
			$eventObj->additionalFields['projectShortName'] = $this->projectShortName;
			break;
		
		
		case 'saved':
			// create new trac and svn environment
			if($this->projectIntern && !$this->projectInternOld) {

				// execute shellscript
				system("sh ".WCF_DIR."lib/acp/action/project.sh ".SVN_DIR." ".TRAC_DIR." {$this->projectShortName} {$eventObj->group->groupName} >> ".WCF_DIR."lib/acp/project.log");

				$s['src'] = 'http://www.easy-coding.de/trac/'.$this->projectShortName.'/attachment/wiki/WikiStart/logo.png?format=raw';
				$s['link'] = 'http://www.easy-coding.de/trac/'.$this->projectShortName;
				$s['width'] = '500';
				$s['footer'] = '<script type=\"text/javascript\" src=\"http://www.torbenbrodt.com/counter/statistik.js\"></script>'.
						'<a href=\"http://www.easy-coding.de/trac/'.$this->projectShortName.'/wiki/Impress\">Impressum</a>';

				$filename = TRAC_DIR.'/trac/'.$this->projectShortName.'/conf/trac.ini';
				$file = @file_get_contents($filename);
				foreach($s as $search => $replace) {
					$file = preg_replace("/\n".$search." = .+\n/", "\n$search = $replace\n", $file);
				}
				
				$handle = @fopen($filename, 'w');
				@fwrite($handle, $file);
				@fclose($handle);
			}
			
			// give groupleaders special rights
			if($this->projectIntern && $this->groupLeaders != $this->groupLeadersOld) {
				$old = ArrayUtil::trim(explode(",",$this->groupLeadersOld));
				$new = ArrayUtil::trim(explode(",",$this->groupLeaders));
				
				foreach(array_diff($old, $new) as $leader) {
					system("sh ".WCF_DIR."lib/acp/action/projectLeader.sh remove ".TRAC_DIR." {$this->projectShortName} {$leader} >> ".WCF_DIR."lib/acp/project.log");
				}
				
				foreach(array_diff($new, $old) as $leader) {
					system("sh ".WCF_DIR."lib/acp/action/projectLeader.sh add ".TRAC_DIR." {$this->projectShortName} {$leader} >> ".WCF_DIR."lib/acp/project.log");
				}
			}
			break;
		
		
		case 'assignVariables':
			if (!count($_POST) && $eventObj instanceof GroupEditForm) {
				$this->project = $eventObj->group->project;
				$this->projectIntern = $eventObj->group->projectIntern;
				$this->projectWebsite = $eventObj->group->projectWebsite;
				$this->projectSvn = $eventObj->group->projectSvn;
				$this->projectShortName = $eventObj->group->projectShortName;
			}

			WCF::getTPL()->assign(array(
				'project' => $this->project,
				'projectIntern' => $this->projectIntern,
				'projectWebsite' => $this->projectWebsite,
				'projectSvn' => $this->projectSvn,
				'projectShortName' => $this->projectShortName
			));
			WCF::getTPL()->append('additionalFields', WCF::getTPL()->fetch('groupAddProject'));
			break;
		}
	}
}
?>
