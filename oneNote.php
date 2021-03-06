<?PHP
require('function.php');

debug('[[[[[[[[[[[[[[[[[[[[[[[[onenote]]]]]]]]]]]]]]]]]]]]]]]]');
debugStart();

require('auth.php');

$note_id = $_SESSION['note_id'];

$noteData = getOneNote($note_id);

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
  require('head.php'); 
?>

<?php
  require('header.php');
?>

<section class="background">
    
    <section class="one-note-area">
    
    <div class="one-note">
        
        <div class="one-note-left">
        
          <h2 class="one-note-title">
              <?php echo $noteData['note'];?>
          </h2>
        
          <div class="one-note-category">
              <p>カテゴリー:
                  <?php echo $noteData['category'];?>
              </p>
          </div>
            
          <div class="one-note-user">
              
                  <div class="one-note-icon">
                    <img src="<?php echo $noteData['icon'];?>" alt="" style="<?php if(empty($noteData['icon'])) echo 'display:none;'?>">
                  </div>
              
                  <a href="mypage.php<?php echo ( !empty(appendGetParam()) ) ? appendGetParam().'&name='.$noteData['name'] : '?name='.$noteData['name'];?>" style="color:black;">
                      <?php echo $noteData['name'];?>
                  </a>
              
          </div>
            
          <div class="one-note-like">
              
            <i class="fas fa-thumbs-up js-like <?php if(isLike($_SESSION['user_id'], $note_id)){ echo 'active';}?>" aria-hidden="true" data-noteid="<?php echo $note_id;?>">
            </i>
              
          </div>
        
          <div class="one-note-img">
              <span style="<?php if( !empty($noteData['note_img']) ){echo 'display:none';}?>">
                NO IMAGE
              </span>
              <img src="<?php echo $noteData['note_img'];?>" alt="" style="<?php   if(empty($noteData['note_img'])) echo 'display:none;'?>">
          </div>
            
        </div>
        
        <div class="one-note-right">
        
          <div class="one-note-text js-text">
              <?php echo $noteData['note_text'];?>
          </div>
            
          <a href="noteList.php" class="note-list-back">
            みんなのメモ一覧
          </a>
        </div>
    
    </div>
        
    </section>

</section>

<?php
  require('footer.php');
?>