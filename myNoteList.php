<?php

  require('function.php');
  require('auth.php');

  debug('[[[[[[[[[[[[[[[[[[マイノート一覧]]]]]]]]]]]]]]]]]]');
  debugStart();

  $user_id = $_SESSION['user_id'];

  $categoryUserData = getMyCategory($user_id);

  $category = (!empty($_GET['category_id'])) ? $_GET['category_id'] : '';

  $currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;

  // パラメータに不正な値が入っているかチェック
  if(!is_int((int)$currentPageNum)){
    error_log('エラー発生:指定ページに不正な値が入りました');
    header("Location:myNoteList.php"); //トップページへ
  }

  $listSpan = 6;

  $currentMinNum = (($currentPageNum-1)*$listSpan);

  $noteData = getMyNotes($user_id,$category,$currentMinNum);
  
  $totalPageNum = $noteData['total_page'];

  if($category){
      $link = '&category_id='.$category;
  }else{
      $link = '';
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

<div class="background">
    
    <section class="list-form-area">
        
        <form method="get" class="list-form">
            
          <div class="list-form-left">
            
              <select name="category_id" class="list-select">
                  
                <option value="0" <?php if(getUserData('category_id',true) == 0 ){ echo 'selected'; } ?> >
                    マイカテゴリー
                </option>
                  
                <?php
                  foreach($categoryUserData as $key => $val){
                ?>
                
                <option value="<?php echo $val['category_id'] ?>" <?php if(getUserData('category_id',true) == $val['category_id'] ){ echo 'selected'; } ?> >
                  <?php echo $val['category']; ?>
                </option>
                  
                <?php
                  }
                ?>
                  
              </select>
                
              <input type="submit" value="検索" style="width:50px;height:35px;">
              
           </div>
            
           <div class="list-form-right">
                  
             全<span class="num"><?php echo sanitize($noteData['total']); ?></span>件
              
           </div>
            
        </form>
        
      </section>
    
        <span class="none">
          <p>
              <?php if( empty($noteData['data']) ) echo 'メモがありません。'?>
          </p>
            
          <a href="registNote.php" style="text-decoration:none;">
              <?php if( empty($noteData['data']) ) echo 'メモ作成へ'?>
          </a>
        </span>
    
    
        <section class="panel-list">
            
          <?php
            foreach ($noteData['data'] as $key => $val):
          ?>
            
          <a class="panel" href="preview.php<?php echo ( !empty(appendGetParam()) ) ? appendGetParam().'&note_id='.$val['note_id'] : '?note_id='.$val['note_id'];?>">
              
            <div class="panel-value">
                
                <div class="panel-title">
                    <?php echo $val['note'];?>
                </div>
                
              <div class="panel-left">
                  
                <div class="panel-category">
                    カテゴリー<br>
                    <?php echo $val['category'];?>
                </div>
                  
                <div class="note-list-like">
                    <i class="fas fa-thumbs-up"></i>
                    <?php echo isLikeLen($val['note_id']); ?>
                </div>
                  
                </div>
                
              <div class="panel-right">
                  
                <div class="panel-img">
                    
                  <span style="<?php if(!empty($val['note_img'])){echo 'display:none';}?>">
                    NO IMAGE
                  </span>
                    
                  <img src="<?php echo $val['note_img'];?>" alt="" style="<?php if(empty($val['note_img'])) echo 'display:none;'?>">
                    
                </div>
                  
              </div>
                
            </div>
              
          </a>
            
          <?php
            endforeach;
          ?>
            
        </section>
    
        <?php pagination($currentPageNum, $totalPageNum, $link); ?>
    
    </div>


<?php
  require('footer.php');
?>