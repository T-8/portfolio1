<?php

  require('function.php');
  require('auth.php');

  debug('[[[[[[[[[[[[[[[[[[ノート一覧]]]]]]]]]]]]]]]]]]');
  debugStart();
  
  $search = ( !empty($_GET['search']) ) ? $_GET['search'] : '';

  $currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;

  // パラメータに不正な値が入っているかチェック
  if(!is_int((int)$currentPageNum)){
    error_log('エラー発生:指定ページに不正な値が入りました');
    header("Location:noteList.php"); //トップページへ
  }

  $listSpan = 6;

  $currentMinNum = (($currentPageNum-1)*$listSpan);

  $noteData = getSearchNotes($search,$currentMinNum);

  $totalPageNum = $noteData['total_page'];

  if($search){
      $link = '&search='.$search;
  }else{
      $link = '';
  }

?>

<?php
  require('head.php'); 
?>

<?php
  require('header.php');
?>

<div class="background">
    
    <section class="search-form-area">
        
        <form method="get" class="list-form">
            
          <div class="list-form-left">
            
            <input type="search" name="search" size="20" value="<?php echo $search; ?>" placeholder="キーワード">
              
            <input type="submit" value="検索" style="width:50px;height:30px;">
              
           </div>
            
           <div class="list-form-right">
                  
             <span class="num"><?php echo sanitize($noteData['total']); ?></span>件表示
              
           </div>
            
        </form>
        
      </section>
    
        <span class="none">
          <p><?php if( empty($noteData['data']) ) echo 'ノートがありません。'?></p>
          <a href="registNote.php" style="text-decoration:none;">
              <?php if( empty($noteData['data']) ) echo 'ノート作成へ'?>
          </a>
        </span>
        
        <section class="panel-list">
            
          <?php
            foreach ($noteData['data'] as $key => $val):
          ?>
            
          <a class="panel" href="registNote.php<?php echo ( !empty(appendGetParam()) ) ? appendGetParam().'&note_id='.$val['note_id'] : '?note_id='.$val['note_id'];?>">
              
            <div class="panel-value">
                
              <div class="panel-title"><?php echo $val['note'];?></div>
                
              <div class="panel-left">
                  
                <div class="panel-category">カテゴリー<br><?php echo $val['category'];?></div>
                  
                <div class="note-list-like">
                    <i class="fas fa-thumbs-up"></i>
                    <?php echo isLikeLen($val['note_id']); ?>
                </div>
                  
                <div class="panel-user">
                    
                  <div class="panel-icon">
                      
                    <img src="<?php echo $val['icon'];?>" alt="" style="<?php if(empty($val['icon'])) echo 'display:none;'?>">
                      
                  </div>
                    
                  <div class="user-name"><?php echo $val['name']?></div>
                    
                </div>
              </div>
                
              <div class="panel-right">
                <div class="panel-img">
                  <span style="<?php if(!empty($val['note_img'])){echo 'display:none';}?>">
                      NO IMAGE
                  </span>
                  <img src="<?php echo $val['note_img'];?>" alt="" style="<?php   if(empty($val['note_img'])) echo 'display:none;'?>">
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