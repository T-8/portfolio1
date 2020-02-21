<?PHP
require('function.php');

debug('[[[[[[[[[[[[[[[[[[[[[[[[カテゴリー削除]]]]]]]]]]]]]]]]]]]]]]]]');
debugStart();

require('auth.php');

  $user_id = $_SESSION['user_id'];

  $categoryUserData = getMyCategory($user_id);
    
  $dbUserData = ( !empty($note_id) ) ?  getNote($user_id, $note_id) : '';


if(!empty($_POST))
{
  
  $category_id = $_POST['category_id'];
    
  validRequired($category_id,'category_id');
    
  if(empty($err))
  {
    try
    {
      $dbh = dbConnect();
        
      $sql = 'DELETE FROM category WHERE category_id = :category_id';
      $data = array(':category_id' => $category_id);
        
      $sql1 = 'DELETE FROM notes WHERE category_id = :category_id';
      $data1 = array(':category_id' => $category_id);
        
      $stmt = queryPost($dbh,$sql,$data);
      $stmt1 = queryPost($dbh,$sql1,$data1);

      // クエリ成功の場合
      if($stmt && $stmt1){
        debug('プロダクト画面へ遷移します。');
          
        header("Location:registNote.php");
      }
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = msg06;
    }
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
  require('head.php'); 
?>

<?php
  require('header.php');
?>

<div class="background">
    
    <form method="post" class="category-form">
        
        <h2>カテゴリー削除</h2>
        
            <label class="category-select">
                
                <span class="err">
                  <?php if( !empty($err['category_id'])) echo $err['category_id']; ?>
                </span><br>
                
                <select name="category_id" class="list-select">
                  
                <option value="0" <?php if(getUserData('category_id',true) == 0 ){ echo 'selected'; } ?> >
                    選択してください
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
                
              <p>※カテゴリー削除を行うと同カテゴリーに属するメモも消えてしまいます</p>

           </label>
        
        <div class="submit">
          <input type="submit" name="submid" class="submit" value="削除">
        </div>
        
        <a href="registNote.php" class="note-back" style="text-decoration:none">メモ作成へ</a>
        
    </form>
    

</div>

<?php
  require('footer.php');
?>