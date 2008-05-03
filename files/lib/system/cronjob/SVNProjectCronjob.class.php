<?php
require_once(WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');

/**
 * Fetchs SVN Log from servers and stores into database
 *
 * @package     de.easy-coding.wcf.data.cronjobs
 * @author      Torben Brodt
 */
class SVNProjectCronjob implements Cronjob {
	protected $userCache=array();
	
	/**
	 * access cache or database
	 * @param username
	 */
	private function getUserID($username) {
		if(!array_key_exists($username, $this->userCache)) {
			$sql = "SELECT		userID
				FROM		wcf".WCF_N."_user
				WHERE		username = '".escapeString($username)."'; ";
			$row = WCF::getDB()->getFirstRow($sql);
			if($row) {
				$this->userCache[$username] = $row['userID'];
			}
		}
		
		return array_key_exists($username, $this->userCache) ? $this->userCache[$username] : 0;
	}
	
	/**
	 * fetchs svn
	 *
	 * @param groupID
	 * @param url
	 * @param user
	 * @param password
	 * @param revision
	 */
	private function fetchSvn($groupID, $url, $user, $password, $revision) {
		if(strpos($url, 'https') !== FALSE)
			return;

		ob_start();
		passthru("sh ".WCF_DIR."lib/acp/action/svnFetch.sh {$url} {$revision} {$user} {$password}");
		$var = ob_get_contents();
		ob_end_clean();

		try {
			$xml = new SimpleXMLElement($var);
			foreach($xml->children() as $logentry) {
				$revision = $logentry['revision'];
				$author = (string)$logentry->author;
				$userID = $this->getUserID($author);
				$timestamp = strtotime((string)$logentry->date);
				$msg = trim((string)$logentry->msg);

				$sql = "INSERT INTO 	wcf".WCF_N."_projectSvn 
							(groupID,revision,userID,author,timestamp,msg) 
					VALUES		(
							{$groupID},
							{$revision},
							{$userID},
							'".escapeString($author)."',
							{$timestamp},
							'".escapeString($msg)."'
							);";
				WCF::getDB()->sendQuery($sql);
			}
		} catch(Exception $e) {};
	}
	
	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data) {
		$sql = "SELECT		g.groupID, 
					g.projectSvn,
					g.projectIntern,
					(SELECT IF(ISNULL(MAX(revision)),0,MAX(revision)) FROM wcf".WCF_N."_projectSvn svn WHERE svn.groupID = g.groupID) as revision
			FROM		wcf".WCF_N."_group g
			WHERE		projectSvn <> ''; ";
		$result = WCF::getDB()->sendQuery($sql);

		while ($row = WCF::getDB()->fetchArray($result)) {
			$rev = $row['revision']+1;
			
			preg_match('/(http[s]{0,1}):\/\/([^:]+):([^@]+)@(.+)/', $row['projectSvn'], $hits);
			list($protocol, $user, $password, $url) = array($hits[1], $hits[2], $hits[3], $hits[4]);
			$url = $protocol.'://'.$url;
	
			$this->fetchSvn($row['groupID'], $url, $user, $password, $rev);
		}
	}
}
?>
