<?php

require('function.php');

debug('[[[[[[[[[[[[[[[[[[Ajax]]]]]]]]]]]]]]]]]]');
debugStart();

if(!empty($_POST)){
    
      $dbh = dbConnect();
    
      $sql = 'SELECT * FROM category WHERE user_id = :user_id AND category = :category';
    
      $data = array(':user_id' => $_SESSION['user_id'], ':category' => $_POST['category']);
    
      $stmt = queryPost($dbh, $sql, $data);
    
      $result = 0;
    
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
      validMaxCate($_POST['category'], 'category');
    
      if( empty($result) && empty($err) ){
          
          echo json_encode(array(
              
              'errorFlg' => false,
              'msg' => '登録可能です'
          
          ));

      }else if( !empty($result) ){
          
            echo json_encode(array(
              
            'errorFlg' => true,
            'msg' => '既に登録されています'
          
          ));
          
      }else if( !empty($err) ){
          
          echo json_encode(array(
              
            'errorFlg' => true,
            'msg' => '20文字以下にしてください'
          
          ));
          
      }
    
    exit();

}

?>