<?php

  require('function.php');
  require('auth.php');

  debug('[[[[[[[[[[[[[[[[[[ログインページ]]]]]]]]]]]]]]]]]]');
  debugStart();

  if( !empty($_POST) ){
      
      $password = $_POST['password'];
      $name = $_POST['name'];
      $name_save = (!empty($_POST['name_save'])) ? true : false;
      
      if( empty($err) ){
          
          try{
              
              $dbh = dbConnect();
              $sql = 'SELECT password,id FROM users WHERE name = :name AND delete_flg = 0';
              $data = array(':name' => $name);
              $stmt = queryPost($dbh,$sql,$data);
              $result = $stmt->fetch(PDO::FETCH_ASSOC);
              
              debug('クエリの中身：'.print_r($result, true));
              
              if( !empty($result) && password_verify($password,array_shift($result)) ){
                  
                  debug('一致しました');
                  
                  $sessionLimit = 60 * 60;
                  $_SESSION['login'] = time();
                  
                  if($name_save){
                      
                      debug('ログイン保持にチェックがあります');
                      
                      //ログイン有効期限を30日にセット
                      $_SESSION['login_limit'] = $sessionLimit * 24 * 30;
                      
                  }else{
                      
                      debug('ログイン保持にチェックがありません');
                      
                      $_SESSION['login_limit'] = $sessionLimit;    
                  }
                  
                  //ユーザーIDを格納
                  $_SESSION['user_id'] = $result['id'];
                  
                  debug('セッション変数の中身：'.print_r($_SESSION,true));
                  debug('マイページへ遷移します。');
                  
                  header("Location:mypage.php");
                  
              }else{
                  
                  debug('名前がアンマッチです');
                  $err['password'] = msg02;
              }
              
          }catch(Exception $e){
              
              $err['common'] = msg06;
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
        
        <h2>ログイン</h2>
        
        <ul>
          
          <li>
          <label>ユーザー名
              <span class="err">
                  <?php if( !empty($err['password'])) echo $err['password']; ?>
              </span>
              <br>
            <input type="text" name="name" value="<?php if( !empty($_POST['name']) ) echo $_POST['name']; ?>">
          </label>
          </li>
        
          <li>
          <label>パスワード<br>
            <input type="text" name="password">
          </label>
          </li>
            
        </ul>
        
        <div class="submit">
          <input type="submit" name="submid" class="submit" value="ログイン">
        </div>
        
    </form>
    

</div>

<?php
  require('footer.php');
?>