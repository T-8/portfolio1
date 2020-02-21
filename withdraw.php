<?PHP
  require('function.php');

  debug('[[[[[[[[[[[[[[[[[[[[[[[[[[退会]]]]]]]]]]]]]]]]]]]]]]]]]]');
  debugStart();

  require('auth.php');

  if(!empty($_POST)){
      
      debug('POST送信があります');
      
      try{
          $dbh = dbConnect();
          
          $sql = 'UPDATE users SET delete_flg = 1 WHERE id = :user_id';
          $data = array(':user_id' => $_SESSION['user_id']);
          $stmt = queryPost($dbh, $sql, $data);
          
          $sql2 = 'DELETE FROM notes WHERE user_id = :user_id';
          $data2 = array(':user_id' => $_SESSION['user_id']);
          $stmt2 = queryPost($dbh, $sql2, $data2);
          
          $sql3 = 'DELETE FROM category WHERE user_id = :user_id';
          $data3 = array(':user_id' => $_SESSION['user_id']);
          $stmt3 = queryPost($dbh, $sql3, $data3);          
          
          if( $stmt && $stmt2 && $stmt3){
              
              session_destroy();
              header("Location:signup.php");
              
          }else{
              $err['common'] = msg06;
          }
      }catch(Exception $e){
          
       error_log('エラー発生'.$e->getMessage());
       $err['common'] = msg06;
     }
      
  }

  
?>

<?php
  require('head.php'); 
?>

<?PHP require('header.php');?>

      <section class="background">
          
          <form method="post" class="withdraw-form">
              
              <div class="withdraw-text">
                本当に退会しますか？
              </div>
              
              <div class = "err">
                 <?php
                   if(!empty($err['common'])) echo $err['common'];
                  ?>
              </div>

              <input type="submit" name="submit" value="退会する" class="withdraw-btn">
          
          </form>
          
      </section>

<?PHP require('footer.php');?>