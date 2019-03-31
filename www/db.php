<?php

class MyDB extends SQLite3 {
    function __construct() {
       $this->open($_SERVER["DOCUMENT_ROOT"] . '/../db/database.sqlite');
    }
  }
  $db = new MyDB();
  if(!$db) {
    echo $db->lastErrorMsg();
  };




?>