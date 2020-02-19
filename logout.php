<?php
  
  require('function.php');
  
  debug('[[[[[[[[[[[[[[[[[[ログアウト]]]]]]]]]]]]]]]]]]');
  
  debugStart();

  session_destroy();

  header("Location:login.php");