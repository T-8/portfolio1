<?php
  require('function.php');

  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debug('「　ユーザー登録ページ　');
  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debugStart();
  
  //postされたとき
  if( !empty($_POST) ){
      $name = $_POST['name'];
      $password = $_POST['password'];
      $re_password = $_POST['re-password'];
      
      validMatch($password, $re_password, 'password');
      validDup($name);
      validMaxLen($name,'name');
      validMinPass($password, 'password');
      validHalf($password,'password');
      validRequired($name,'name');
      validRequired($password,'password');
      
      if( empty($err) ){
          
          try{
              $dbh = dbConnect();
              
              $sql = 'INSERT INTO users(name,password,create_date) VALUES(:name, :password, :create_date)';
              $data = array(':name' => $name, ':password' => password_hash($password, PASSWORD_DEFAULT), 'create_date' => date('Y-m-d H:i:s'));
              $stmt = queryPost($dbh, $sql, $data);
              
              $sql2 = 'SELECT id FROM users WHERE name = :name';
              $data2 = array(':name' => $name);
              $stmt2 = queryPost($dbh, $sql2, $data2);
              $result = $stmt2 -> fetch(PDO::FETCH_ASSOC);
              
              if($stmt && $stmt2){
                  $_SESSOIN['user_id'] = $result['id'];
                  header("Location:mypage.php");
              }else{
                  $err['common'] = msg06;
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
    
    <form class="f-signup" method="post">
        
        <h2>新規登録</h2>
        
        <ul>
          
          <li>
            <label>ユーザー名
              <span class="err js-msg-name">
                  <?php if( !empty($err['name'])) echo $err['name']; ?>
              </span><br>
            <input type="text" name="name" class="js-valid-name" value="<?php if(!empty($_POST['name'])) echo $_POST['name']; ?>">
           </label>
          </li>
        
          <li>
            <label>パスワード
                <span class="err">
                    <?php if( !empty($err['password'])) echo $err['password']; ?>
                </span><br>
                <input type="text" name="password">
           </label>
          </li>
            
          <li>
          <label>パスワード再入力<br>
            <input type="text" name="re-password">
          </label>
          </li>
            
        </ul>
        
        <div class="submit">
          <input type="submit" name="submit" value="登 録" class="submit">
        </div>
        
    </form>
    
</div>

<?php
  require('footer.php');
?>