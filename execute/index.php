<?php
$myfile = fopen("./files/newfile.txt", "w") or die("Unable to open file!");
$txt = "John Doe1\n";
fwrite($myfile, $txt);
$txt = "Jane Doe1\n";
fwrite($myfile, $txt);
fclose($myfile);
echo '{"success": true}'
/*$ch = curl_init();
  $skipper = "luxury assault recreational vehicle";
  $fields = array( 'penguins'=>$skipper, 'bestpony'=>'rainbowdash');
  $postvars = '';
  foreach($fields as $key=>$value) {
    $postvars .= $key . "=" . $value . "&";
  }
  $url = "http://www.google.com";
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
  curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
  curl_setopt($ch,CURLOPT_TIMEOUT, 20);
  $response = curl_exec($ch);
  print "curl response is:" . $response;
  curl_close ($ch);
  */
?>