<?php

include_once('example.php');

/**
* REST call to
* api/example/1?name=firstname&lname=lastname
*/

try {
  $rest = new ExampleAPI($_REQUEST['request'], $_SERVER['REMOTE_ADDR']);
  echo $rest->processAPI();
} catch (Exception $e) {
  echo json_encode(['Error' => $e->getMessage()]);
}