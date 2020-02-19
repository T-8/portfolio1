<?php

  require('function.php');

  require('auth.php');

  debug('[[[[[[[[[[[[[[[[[[ログインページ]]]]]]]]]]]]]]]]]]');
  debugStart();

  $userData = getUser($_SESSION['user_id']);

  if( !empty($_POST) ){
      
      $old_pass = $_POST['old_pass'];
      $new_pass = $_POST['new_pass'];
      $new_pass_re = $_POST['new_pass_re'];
      
      //未入力チェック
      validRequired($old_pass, 'old_pass');
      validRequired($new_pass, 'new_pass');
      validRequired($new_pass_re, 'new_pass_re');
      
   if( empty($err) ){
       
       validPass($old_pass, 'old_pass');
       validPass($new_pass, 'new_pass');

      if(!password_verify($old_pass, $userData['password'])){
        $err['old_pass'] = msg13;
      }
       
      if($old_pass === $new_pass){
        $err['old_pass'] = msg14;          
      }
       
      validMatch($new_pass, $new_pass_re, 'new_pass');
       
      if( empty($err) ){
          
          try{
              
              $dbh = dbConnect();
              
              $sql = 'UPDATE users SET password = :password WHERE id = :id';
              
              $data = array(':password' => password_hash($new_pass, PASSWORD_DEFAULT), ':id' => $_SESSION['user_id']);
              
              $stmt = queryPost($dbh, $sql, $data);
              
              if($stmt){
                  debug('pass変更しました');
                  $_SESSION['msg_success'] = SUC01;
                  header("Location:mypage.php");
              }
              
          }catch(Exception $e){
              
             error_log('エラー発生:' . $e->getMessage());
             $err_msg['old_pass'] = msg06;
            
          }
          
      }
          
   }
      
  }

?>

<?php
  require('head.php'); 
?>

<?php
  require('header.php');
?>

<div class="background">
    
    <form class="login-form" method="post">
        
        <h2>パスワード変更</h2>
        
        <ul>
          
          <li>
              
          <label>現在のパスワード
              
              <span class="err">
                  <?php if( !empty($err['old_pass'])) echo $err['old_pass']; ?>
              </span>
              <br>
              
            <input type="text" name="old_pass" value="<?php if( !empty($_POST['old_pass']) ) echo $_POST['old_pass']; ?>">
              
          </label>
              
          </li>
        
          <li>
              
            <label>新しいパスワード
              <span class="err">
                  <?php if( !empty($err['new_pass'])) echo $err['new_pass']; ?>
              </span>
              <br>
                
              <input type="text" name="new_pass" value="<?php if( !empty($_POST['new_pass']) ) echo $_POST['new_pass']; ?>">
                
            </label>
              
          </li>
            
          <li>
              
            <label>新しいパスワード再入力<br>
                
              <input type="text" name="new_pass_re" value="<?php if( !empty($_POST['new_pass_re']) ) echo $_POST['new_pass_re']; ?>">
                
            </label>
              
          </li>
            
        </ul>
        
        <div class="submit">
          <input type="submit" name="submid" class="submit" value="変更">
        </div>
        
    </form>

</div>

<?php
  require('footer.php');
?>