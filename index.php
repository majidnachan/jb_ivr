<?php
$header = apache_request_headers();
  
foreach ($header as $headers => $value) {
    echo "$headers: $value <br />\n";
}
?>
