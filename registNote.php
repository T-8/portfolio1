<?php

  require('function.php');
  require('auth.php');

  debug('[[[[[[[[[[[[[[[[[[ノート作成]]]]]]]]]]]]]]]]]]');
  debugStart();

  $note_id = ( !empty($_GET['note_id']) ) ? $_GET['note_id'] : '' ;
  $_SESSION['note_id'] = $note_id;
  $dbUserData = ( !empty($note_id) ) ? getNote($_SESSION['user_id'], $note_id) : '';

  // 新規登録画面か編集画面か判別用フラグ
  $edit_flg = ( empty($dbUserData) ) ? false : true;

  debug('noteID：'.$note_id);
  debug('DBデータ：'.print_r($dbUserData,true));

  $category_id = getMyCategory($_SESSION['user_id']);

  if(!empty($note_id) && empty($dbUserData)){
    debug('他ユーザーのノートです');
    header("Location:mypage.php"); 
  }

  if( !empty($_POST) ){
      
      debug('POST送信があります。');
      debug('POST情報：'.print_r($_POST,true));
      debug('FILE情報：'.print_r($_FILES,true));
      
      $note = $_POST['note'];
      $category = $_POST['category_id'];
      $note_img = ( !empty($_FILES['note_img']['name']) ) ? uploadImg($_FILES['note_img'],'note_img') : '';
      $note_img = ( empty($note_img) && !empty($dbUserData['note_img']) ) ? $dbUserData['note_img'] : $note_img;
      $note_text = $_POST['note_text'];
      
      // 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
      if( empty($dbUserData) ){
          validRequired($note, 'note');
          
          validRequired($category, 'category');
          
          validMaxNote($note, 'note');
          
          validSelect($category, 'category_id');
          
      }else{
          
      if($dbUserData['note'] !== $note){
        
        validRequired($note, 'note');
        
        validMaxNote($note, 'note');
      }
          
      if($dbUserData['category_id'] !== $category){
        
        validSelect($category, 'category_id');
      }
          
    }
      
    if( empty($err) ){
        
      debug('バリデーションOKです。');

      
      try {
        
        $dbh = dbConnect();
          
        if($edit_flg){
            
          debug('DB更新です。');
            
          $sql = 'UPDATE notes SET note = :note, category_id = :category, note_img = :note_img, note_text = :note_text WHERE user_id = :user_id AND note_id = :note_id';
            
          $data = array(':note' => $note , ':category' => $category, ':note_img' => $note_img, ':note_text' => $note_text, ':user_id' => $_SESSION['user_id'], ':note_id' => $note_id);
            
        }else{
          debug('DB新規登録です。');
          $sql = 'INSERT INTO notes (note, category_id, note_text, note_img, user_id, create_date ) VALUES (:note, :category, :note_text, :note_img, :user_id, :date)';
          $data = array(':note' => $note, ':category' => $category, ':note_text' => $note_text, ':note_img' => $note_img, ':user_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
        }
        debug('SQL：'.$sql);
        debug('流し込みデータ：'.print_r($data,true));
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        // クエリ成功の場合
        if($stmt){
            
          if($edit_flg){
              $_SESSION['msg_success'] = SUC05;
          }else{
              $_SESSION['msg_success'] = SUC03;
          }
          
          debug('マイノートリストへ遷移します。');
          header("Location:myNoteList.php"); //マイページへ
        }

      } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err['common'] = MSG07;
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
    
    <form method="post" enctype="multipart/form-data">
        
        <h2><?php echo (!$edit_flg) ? 'メモ作成' : 'メモ編集'; ?></h2>
        
        <ul class="note-form-ul">
            
          <li>
          <label>カテゴリー<br>
              
              <span class="err">
                  <?php if( !empty($err['category'])) echo $err['category']; ?>
              </span>
              <br>
              
            <select name="category_id">
              <option value="0" <?php if(getUserData('category_id') === 0){ echo 'selected';}?> >選択してください</option>
                
              <?php
                foreach($category_id as $key => $val){
              ?>
                
              <option value="<?php echo $val['category_id'] ?>" <?php if(getUserData('category_id') == $val['category_id'] ){ echo 'selected'; } ?> >
                  
                <?php echo $val['category'];?>
                  
              </option>
                
              <?php
                }
              ?>
                
            </select>
              
          </label>
          <br>
            
          <a href="registCategory.php" class="category-regist" style="text-decoration:none;">カテゴリー登録</a>
              
          </li>
            
          <li>
          <label>タイトル
              <span class="err">
                  <?php if( !empty($err['note'])) echo $err['note']; ?>
              </span>
              <br>
            <input type="text" name="note" value="<?php if( !empty(getUserData('note')) ) echo getUserData('note'); ?>">
          </label>
          </li>           
        
          <li>
            画像<br>
            <label class="note-drop">
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input type="file" name="note_img" class="note-input" style="width:120px;">
              <img src="<?php echo getUserData('note_img');?>" alt="" class="note-img" style="<?php if(empty(getUserData('note_img'))) echo 'display:none;'?>" >
              <span>ドラッグ＆ドロップ</span>
            </label>
          </li>
            
          <li>
          <label>詳細
              <span class="err">
                  <?php if( !empty($err['note_text'])) echo $err['note_text']; ?>
              </span>
              <br>
              <textarea rows="12" cols="38" name="note_text"><?php echo getUserData('note_text');?></textarea>
          </label>
          </li>
            
        </ul>
        
        <div class="submit">
          <input type="submit" name="submid" class="submit" value="<?php echo (!empty($edit_flg)? '編集':'作成');?>">
        </div>
        
        <a href="deleteNote.php" class="delete" style="text-decoration:none;">
            <?php if( !empty($edit_flg) ) echo '削除する'?>
        </a>
        
    </form>
    

</div>

<?php
  require('footer.php');
?>