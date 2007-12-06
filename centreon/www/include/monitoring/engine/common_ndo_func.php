<?php
/**
Centreon is developped with GPL Licence 2.0 :
http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
Developped by : Cedrick Facon

The Software is provided to you AS IS and WITH ALL FAULTS.
OREON makes no representation and gives no warranty whatsoever,
whether express or implied, and without limitation, with regard to the quality,
safety, contents, performance, merchantability, non-infringement or suitability for
any particular or intended purpose of the Software found on the OREON web site.
In no event will OREON be liable for any direct, indirect, punitive, special,
incidental or consequential damages however they may arise and even if OREON has
been previously advised of the possibility of such damages.

For information : contact@oreon-project.org
*/

function get_Host_Status($host_name,$pearDBndo,$general_opt){

	$rq = "SELECT nhs.current_state" .
			" FROM `" .$ndo_base_prefix."_hoststatus` nhs, `" .$ndo_base_prefix."_objects` no" .
			" WHERE no.object_id = nhs.host_object_id" ;

	$DBRESULT =& $pearDBndo->query($rq);
	if (PEAR::isError($DBRESULT))
		print "DB Error : ".$DBRESULT->getDebugInfo()."<br>";	
	$status = array();
//	$status[0] = array();
//	$status[0]["current_state"] = "0";	
	$DBRESULT->fetchInto($status);


	return $status["current_state"];
}

?>