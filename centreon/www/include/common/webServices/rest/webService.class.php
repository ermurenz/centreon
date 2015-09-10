<?php
/*
 * Copyright 2005-2015 Centreon
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation ; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * As a special exception, the copyright holders of this program give Centreon
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of Centreon choice, provided that
 * Centreon also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 * SVN : $URL$
 * SVN : $Id$
 *
 */

class CentreonWebService {
    protected $pearDB;
    protected $webServicePaths;

    /**
     * Constructor
     *
     * @param CentreonDB $db
     * @return void
     */
    public function __construct()
    {
        global $centreon_path;
        $this->pearDB = new CentreonDB();

        $this->webServicePaths = glob($centreon_path . '/www/include/common/webServices/rest/*.class.php');

        $DBRESULT = $this->pearDB->query("SELECT name FROM modules_informations");
        while ($row = $DBRESULT->fetchRow()) {
            $this->webServicePaths = array_merge($this->webServicePaths, glob($centreon_path . '/www/modules/' . $row['name'] . '/webServices/rest/*.class.php'));
        }
    }

    /**
     * Get webservice
     *
     * @return array
     */
    public function getWebService($object = "", $action = "")
    {
        $webServiceClass = array();
        foreach ($this->webServicePaths as $webServicePath) {
            if (false !== strpos($webServicePath, '/rest/' . $object . '.class.php')) {
                require_once $webServicePath;
                $explodedClassName = explode('_', $object);
                $className = "";
                foreach ($explodedClassName as $partClassName) {
                    $className .= ucfirst(strtolower($partClassName));
                }
                if (class_exists($className)) {
                    $objectClass = new $className();
                    if (method_exists($objectClass, $action)) {
                        $webServiceClass = array(
                            'path' => $webServicePath,
                            'class' => $className
                        );
                    }
                }
            }
        }

        return $webServiceClass;
    }
    
    /**
     * Send json return
     *
     * @param mixed $data The values
     * @param integer $code The HTTP code
     */
    public static function sendJson($data, $code = 200)
    {
        switch ($code) {
            case 500:
                header("HTTP/1.1 500 Internal Server Error");
                break;
            case 400:
                header("HTTP/1.1 400 Bad Request");
                break;
            case 401:
                header("HTTP/1.1 401 Unauthorized");
                break;
            case 403:
                header("HTTP/1.1 403 Forbidden");
                break;
            case 404:
                header("HTTP/1.1 404 Object not found");
                break;
            case 405:
                header("HTTP/1.1 405 Method not allowed");
                break;
        }
        header('Content-type: application/json');
        print json_encode($data);
        exit();
    }
}
?>
