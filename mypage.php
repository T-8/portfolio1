<?php
  require('function.php');
  require('auth.php');
  debug('[[[[[[[[[[[[[[[[[[[[[[[[[[[マイページ]]]]]]]]]]]]]]]]]]]]]]]]]]]');
  debugStart();

  $dbUserData = getUser($_SESSION['user_id']);

  $name = ( !empty($_GET['name']) ) ? $_GET['name'] : '' ;

  $_SESSION['name'] = $name;

  if( !empty($name) ){
    debug('GETパラメータがあります');
    header("Location:oneUser.php"); 
  }
?>

<?php
  require('head.php'); 
?>

  <p id="js-show-msg" style="display:none;" class="msg-slide">
    <?php echo getSessionFlash('msg_success'); ?>
  </p>

<?php
  require('header.php');
?>

<section class="background">

  <div class="mypage-area">
      
  <section class="profile">
      
    <label class="header-area">
      <img class="prof-header-img" src="<?php echo getUserData('header'); ?>" alt="" style="<?php if(empty(getUserData('header'))) echo 'display:none;'?>">
    </label>
      
    <label class="icon-area">
      <img class="prof-icon-img" src="<?php echo getUserData('icon'); ?>" alt="" style="<?php if(empty(getUserData('icon'))) echo 'display:none;'?>">
    </label>
      
    <label class="prof-edit-area">
      <div class="prof-edit">
        <a href="profEdit.php" style="text-decoration:none">プロフィール編集</a>
      </div>
    </label>

    <label class="prof-name">
      <div class="name">
        <?php echo getUserData('name'); ?>
      </div>
    </label>
      
    <label class="prof-comment">
      <div class="comment">
        <?php if( !empty(getUserData('comment')) ) echo getUserData('comment'); ?>
      </div>
    </label>
      
  </section>
    
  <section class="sidebar">
      
      <ul class="side-ul">
        <li><a href="myNoteList.php">マイノート</a></li>
        <li><a href="registCategory.php">カテゴリー登録</a></li>
        <li><a href="registNote.php">メモ作成</a></li>
        <li><a href="noteList.php">みんなのメモ</a></li>
        <li><a href="passEdit.php">パスワード変更</a></li>
        <li><a href="withdraw.php">退会</a></li>
      </ul>
      
  </section>
      
  </div>

</section>

<?php
  require('footer.php');
?>