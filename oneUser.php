<?PHP
require('function.php');

debug('[[[[[[[[[[[[[[[[[[[[[[[[カテゴリー登録]]]]]]]]]]]]]]]]]]]]]]]]');
debugStart();

require('auth.php');

$name = $_SESSION['name'];

$userData = getOneUser($name);

$user_id = $userData['id'];

$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;

$categoryData = getMyCategory($userData['id']);

$category = (!empty($_GET['category_id'])) ? $_GET['category_id'] : '';

$listSpan = 6;

$currentMinNum = (($currentPageNum-1)*$listSpan);

$noteData = getMyNotes($user_id,$category,$currentMinNum);

$totalPageNum = $noteData['total_page'];

if($category){
      $link = '&category_id='.$category;
  }else{
      $link = '';
  }

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
  require('head.php'); 
?>

<?php
  require('header.php');
?>

<section class="background">
    
    <div class="mypage-area">
        
          <section class="one-user-profile">
      
            <label class="header-area">
              <img class="prof-header-img" src="<?php echo $userData['header']; ?>" alt="" style="<?php if( empty($userData['header']) ) echo 'display:none;'?>">
            </label>
              
            <label class="icon-area">
              <img class="prof-icon-img" src="<?php echo $userData['icon']; ?>" alt="" style="<?php if( empty($userData['icon']) ) echo 'display:none;'?>">
            </label>
        
            <label class="prof-name">
              <div class="name">
                <?php echo $userData['name']; ?>
              </div>
            </label>
      
            <label class="prof-comment">
              <div class="comment">
                <?php if( !empty( $userData['comment']) ) echo $userData['comment']; ?>
              </div>
            </label>
      
          </section>
        
          <section class="note-area">
    
          <form method="get" class="list-form">
              
            <p><?php echo $userData['name'];?>さんのノート</p>
            
            <div class="list-form-left">
            
                <select name="category_id" class="list-select">
                  
                  <option value="0" <?php if(getUserData('category_id',true) == 0 ){ echo 'selected'; } ?> >
                      <?php echo $userData['name'];?>さんのカテゴリー
                  </option>
                  
                  <?php
                    foreach($categoryData as $key => $val){
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
                  
               <span class="num"><?php echo sanitize($noteData['total']); ?></span>件表示
              
             </div>
            
          </form>
    
          <span class="none">
            <p><?php if( empty($noteData['data']) ) echo 'メモがありません。'?></p>
          </span>
    
        <section class="panel-list">
            
          <?php
            foreach ($noteData['data'] as $key => $val):
          ?>
            
          <a class="panel" href="registNote.php<?php echo ( !empty(appendGetParam()) ) ? appendGetParam().'&note_id='.$val['note_id'] : '?note_id='.$val['note_id'];?>">
              
            <div class="panel-value">
                
              <div class="panel-title"><?php echo $val['note'];?></div>
                
              <div class="panel-left">
                
                  <div class="panel-category">
                    カテゴリー<br>
                    <?php echo $val['category'];?>
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
          
    </section>
        
        <?php pagination($currentPageNum, $totalPageNum, $link); ?>

        
      </div>
    
      
</section>

<?php
  require('footer.php');
?>