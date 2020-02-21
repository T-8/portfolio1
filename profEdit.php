<?php
  require('function.php');
  require('auth.php');

  debug('[[[[[[[[[[[[[[[[[[[[プロフィール変更]]]]]]]]]]]]]]]]]]]]');
  debugStart();

  $dbUserData = getUser($_SESSION['user_id']);

  debug('取得したユーザー情報：'.print_r($dbUserData,true));

  if( !empty($_POST) ){
      
      debug('POST送信');
      debug('POST情報：'.print_r($_POST,true));
      debug('FILE情報：'.print_r($_FILES,true));
      
      $name = $_POST['name'];
      $comment = $_POST['comment'];
      
      $header = ( !empty($_FILES['header']['name']) ) ? uploadImg($_FILES['header'],'header') : '';
      $icon = ( !empty($_FILES['icon']['name']) ) ? uploadImg($_FILES['icon'],'icon') : '';
      $header = ( empty($header) && !empty($dbUserData['header']) ) ? $dbUserData['header'] : $header;
      $icon = ( empty($icon) && !empty($dbUserData['icon']) ) ? $dbUserData['icon'] : $icon;
      
      if($dbUserData['name'] !== $name){
          
          validRequired($name,'name');
          
          if( empty($err['name']) ){
              validDup($name,'name');
          }
      }
      
      if($dbUserData['comment'] !== $comment){
          
          validMaxCom($comment,'comment');

      }
      
      
      if( empty($err) ){
          
          debug('バリデーションOK');
          
          try{
              
              $dbh = dbConnect();
              $sql = 'UPDATE users SET header = :header, icon = :icon, name = :name, comment = :comment WHERE id = :user_id';
              $data = array(':header' => $header, ':icon' => $icon, ':name' => $name, ':comment' => $comment, ':user_id' => $_SESSION['user_id']);
              $stmt = queryPost($dbh,$sql,$data);
             
              if($stmt){
                  debug('マイページへ');
                  $_SESSION['msg_success'] = SUC02;
                  header("Location:mypage.php");
              }
          }catch(Excetion $e){
              error_log('エラー発生：'.$e->getMessage());
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

<section class="background">
    
    <form method="post" class="prof-form" enctype="multipart/form-data">
        
        <h2>プロフィール編集</h2>
        <span><?PHP if(!empty($err['common'])) echo $err['common'];?></span>
        
        <ul class="prof-form-ul">
        
        <li>
            ヘッダー画像<br>
            <label class="header-drop">
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input type="file" name="header" class="header-input">
              <img src="<?php echo getUserData('header');?>" alt="" class="header-img" style="<?php if(empty(getUserData('header'))) echo 'display:none;'?>" >
              ドラッグ＆ドロップ
            </label>
        </li>
            
        <li>
            プロフィール画像<br>
            <label class="icon-drop">
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input type="file" name="icon" class="icon-input" style="width:120px;">
              <img src="<?php echo getUserData('icon');?>" alt="" class="icon-img" style="<?php if(empty(getUserData('icon'))) echo 'display:none;'?>" >
              <span>ドラッグ＆ドロップ</span>
            </label>
        </li>
            
        <li>
            ユーザー名<br>
            <label>
              <span class="err js-msg-name">
                  <?php if( !empty($err['name'])) echo $err['name']; ?>
              </span><br>
            <input type="text" name="name" class="js-valid-name" value="<?php echo getUserData('name'); ?>">
           </label>
        </li>
        
        <li>
            自己紹介
            <span class="err"><?PHP if(!empty($err['comment'])) echo $err['comment'];?></span>
            <br>
            <label>
               <textarea rows="8" cols="46" name="comment"><?php echo getUserData('comment');?></textarea>
            </label>
        </li>
            
        </ul>
        
        <div class="submit">
          <input type="submit" name="submid" class="submit" value="保存">
        </div>
    
    </form>

</section>

<?php
  require('footer.php');
?>