<?php
$json = file_get_contents('php://input');
$myfile = fopen("./files/execute.txt", "w") or die("Unable to open file!");
fwrite($myfile, $json);
fclose($myfile);
echo '{"success": true}';
?>
