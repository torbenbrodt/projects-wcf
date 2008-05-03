<?php
require_once(WCF_DIR.'lib/acp/page/UserSuggestPage.class.php');

/**
 * Outputs a xml document with a list of suggested licenses.
 *
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.acp.page
 */
class PublicLicenseSuggestPage extends UserSuggestPage {
	public $query= '';
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_POST['query'])) {
			$this->query = StringUtil::trim($_POST['query']);
			if (CHARSET != 'UTF-8') $this->query = StringUtil::convertEncoding('UTF-8', CHARSET, $this->query);
		}
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		//parent::show();
		header('Content-type: text/xml');
		echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\n<suggestions>\n";
		
		if (!empty($this->query)) {
			// get users
			$users = array();
			$sql = "SELECT		licenseName
				FROM		wcf".WCF_N."_projectLicenses
				WHERE		licenseName LIKE '%".escapeString($this->query)."%'
				ORDER BY	licenseName
				LIMIT		10";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				printf("<license><![CDATA[%s]]></license>\n", StringUtil::escapeCDATA($row['licenseName']));
			}
		}
		echo '</suggestions>';
		exit;
	}
}
?>
