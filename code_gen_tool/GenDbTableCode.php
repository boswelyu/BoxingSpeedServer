<?php
date_default_timezone_set("PRC");

define('SERVER_ROOT_PATH', dirname(__FILE__) . "/..");

require_once("CodeGenConfig.php");
require_once("DbParser.php");

define('TEMPLATE_PATH', dirname(__FILE__)."/template/");
define('TARGET_PATH', SERVER_ROOT_PATH . "/dbtable/");

$targetTables = array();
if($argc > 1){
    for($i=1;$i<$argc;$i++){
        $targetTables[]=$argv[$i];
    }
}

$dbParser = new DbParser(TEMPLATE_PATH, TARGET_PATH);

$dbParser->parse($targetTables);

echo "Finished!\n";
