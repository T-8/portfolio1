<?php

require('function.php');

debug('[[[[[[[[[[[[[[[[[[Ajax]]]]]]]]]]]]]]]]]]');
debugStart();

if(!empty($_POST)){
    
      $dbh = dbConnect();
    
      $sql = 'SELECT * FROM users WHERE name = :name';
    
      $data = array(':name' => $_POST['name']);
    
      $stmt = queryPost($dbh, $sql, $data);
    
      $result = 0;
    
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
      validMaxLen($_POST['name'], 'name');
    
      if( empty($result) && empty($err) ){
          
          echo json_encode(array(
              
              'errorFlg' => false,
              'msg' => '使用可能です'
          
          ));

      }else if(!empty($result)){
          
            echo json_encode(array(
              
            'errorFlg' => true,
            'msg' => '既に登録されています'
          
          ));
          
      }else if( !empty($err) ){
          
          echo json_encode(array(
              
            'errorFlg' => true,
            'msg' => '10文字以下にしてください'
          
          ));
          
      }
    
    exit();

}

?>