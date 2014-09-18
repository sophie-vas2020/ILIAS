<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */#

/**
* interface for the (external) WBD-Connector
* retrieve and set eduPoint-relevant data
*
* @author	Nils Haagen <nhaagen@concepts-and-training.de>
* @version	$Id$
*
*
*/

//obsolete, if done in implementing class, but neverless:
$basedir = __DIR__; 
$basedir = str_replace('/Services/WBDData/classes', '', $basedir);
chdir($basedir);

if( !isset($ilAuth) ) {
	// switch context to something without authentication
    require_once "./Services/Context/classes/class.ilContext.php";
    ilContext::init(ilContext::CONTEXT_WEB_NOAUTH);
    require_once("./Services/Init/classes/class.ilInitialisation.php");
    ilInitialisation::initILIAS();

	//better ask for some shared secret or redirect...
	$url = str_replace('Services/GEV/WBD/classes/class.gevWBDDataConnector.php', '', $_SERVER['REQUEST_URI']);
	$url .= 'login.php';
	//ilUtil::redirect($url);
}

require_once("./include/inc.header.php");


abstract class wbdDataConnector {

	public $ilDB;

	public $WBD_USER_RECORD;
	public $WBD_EDU_RECORD;
	public $CSV_LABELS;

	public $csv_text_delimiter = '"';
	public $csv_field_delimiter = ';';

	public function __construct() {
		global $ilDB;
		$this->ilDB = &$ilDB;
		
		require_once("./Services/WBDData/wbdBlueprints.php");
		$this->WBD_USER_RECORD = $WBD_USER_RECORD;
		$this->WBD_EDU_RECORD = $WBD_EDU_RECORD;
		$this->CSV_LABELS = $CSV_LABELS;
	}

	/**
	* BLUEPRINTS
	**/
	protected function new_user_record($data=array()){
		$user_record = $this->WBD_USER_RECORD;
		foreach ($data as $key => $value) {
			$user_record[$key] = $value;
		}
		
		return $user_record;
	}
		
	protected function new_edu_record($data=array()){
		$edu_record = $this->WBD_EDU_RECORD;
		foreach ($data as $key => $value) {
			$edu_record[$key] = $value;
		}
		
		return $edu_record;
	}


	/**
	* EXPORT FUNCTIONS, CSV
	**/
		
	private function csv_dump($data, $header=False, $as_file=False){
		if($header) {
			//data must have at least one entry!
			$headerrow = $this->csv_labels(array_keys($data[0]));
			array_unshift($data, $headerrow);
		}

		if( $as_file) {
			//set header
			header("Content-Type: application/csv; charset=ISO-8859-1");
			header("Content-Disposition:attachment; filename=\"".$as_file.".csv\"");
		} else {
			header("Content-Type: text/plain, charset=utf-8");
		}

		foreach ($data as $row){
			$r = $this->csv_text_delimiter 
				.join(   $this->csv_text_delimiter 
						.$this->csv_field_delimiter
						.' '
						.$this->csv_text_delimiter,
						 $row)
				.$this->csv_text_delimiter
				."\n";
	
			print $r;
		}

		/*
		Fabi goes like this:

		function escape_quotes($str) {
			return str_replace("\"", "\"\"", $str); // This seems to be the way how excel likes it....
		}

		// Output
		foreach ($ret as $row) {
			echo mb_convert_encoding("\"".implode("\";\"", array_map("escape_quotes", $row))."\"\n", "ISO-8859-1", "UTF-8");
		}

		*/
	}



	private function csv_labels($keys){
		$ret = array();
		foreach ($keys as $key) {
			$ret[] = $this->CSV_LABELS[$key];
		}
		return $ret;		
	}


	public function export_get_new_users($as_file=False){
		$data = $this->get_new_users();
		$this->csv_dump($data, True, $as_file);
	}
	public function export_get_updated_users($as_file=False){
		$data = $this->get_new_users();
		$this->csv_dump($data, True, $as_file);
	}
	public function export_get_new_edu_records($as_file=False){
		$data = $this->get_new_users();
		$this->csv_dump($data, True, $as_file);
	}
	public function export_get_changed_edu_records($as_file=False){
		$data = $this->get_new_users();
		$this->csv_dump($data, True, $as_file);
	}




	/*
	* ------------- IMPLEMENT THE FOLLOWING ------------
	*/

	/**
	* EXPORT FUNCTIONS
	**/

	/**
	 * get users that do not have a BWV-ID yet
	 * 
	 * @param 
	 * @return array of user-records
	 */

	public function get_new_users() {}


	/**
	 * get users with outdated records in BWV-DB:
	 * userdata changed after last reporting
	 *
	 * @param 
	 * @return array of user-records
	 */

	public function get_updated_users() {}


	/**
	 * get edu-records for courses that 
	 * started 3 months ago (or more)
	 * and have not been submitted to the WBD
	 *
	 *
	 * @param 
	 * @return array of edu-records
	 */

	public function get_new_edu_records() {}


	/**
	 * get edu-records for courses that 
	 * started 3 months ago (or more)
	 * if the current record differs from a record
	 * that was allready sent to the WBD
	 *  
	 * @param 
	 * @return array of edu-records
	 */

	public function get_changed_edu_records() {}


	/**
	* IMPORT FUNCTIONS
	**/

	/**
	 * set BWV-ID for user
	 *  
	 * @param string $user_id
	 * @param string $bwv_id
	 * @param date $certification_begin
	 * @return boolean
	 */

	public function set_bwv_id($user_id, $bwv_id, $certification_begin) {}

	
	/**
	 * set edu-record for user
	 *  
	 * @param array $edu_record
	 * @return boolean
	 */

	public function set_edu_record($edu_record) {}


}

?>
