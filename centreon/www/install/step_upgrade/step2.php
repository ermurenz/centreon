<?php
/*
 * Centreon is developped with GPL Licence 2.0 :
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 * Developped by : Julien Mathis - Romain Le Merlus 
 * 
 * The Software is provided to you AS IS and WITH ALL FAULTS.
 * Centreon makes no representation and gives no warranty whatsoever,
 * whether express or implied, and without limitation, with regard to the quality,
 * any particular or intended purpose of the Software found on the Centreon web site.
 * In no event will Centreon be liable for any direct, indirect, punitive, special,
 * incidental or consequential damages however they may arise and even if Centreon has
 * been previously advised of the possibility of such damages.
 * 
 * For information : contact@centreon.com
 */

	aff_header("Centreon Upgrade Wizard", "Licence", 2);
	$license_file_name = "./LICENSE.txt";
	$fh = fopen( $license_file_name, 'r' ) or die( "License file not found!" );
	$license_file = fread( $fh, filesize( $license_file_name ) );
	fclose($fh);
	$str = "<textarea cols='80' rows='20' readonly>".$license_file."</textarea>";
	$str .= "</td>
	</tr>
	<tr>
	  <td align=\"left\">
		<input type='checkbox' class='checkbox' name='setup_license_accept' onClick='LicenceAccepted();' value='0' /><a href=\"javascript:void(0)\" onClick=\"LicenceAcceptedByLink();\">I Accept</a>
	  </td>
	  <td align=right>
		&nbsp;
	  </td>
	</tr>";
	print $str;
	aff_middle();
	print "<input class='button' type='submit' name='goto' value='Next' id='button_next' disabled='disabled' />";
	aff_footer();

?>