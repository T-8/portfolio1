<?PHP
require('function.php');

debug('[[[[[[[[[[[[[[[[[[[[[[[[カテゴリー登録]]]]]]]]]]]]]]]]]]]]]]]]');
debugStart();

require('auth.php');

if(!empty($_POST))
{
  //POSTを代入
  $category = $_POST['category'];
    
  //未入力チェック
  validRequired($category,'category');
    
  validMaxCate($category,'category');
    
  //このユーザーのカテゴリデーターの名前とiDを代入
  $categoryUserData = getCategory($_SESSION['user_id']);
    
  //登録上限
  if( count($categoryUserData) >= 10 ){
      
      global $err;
      $err['category'] = msg11;
      
    }
  

  //データベースに同じcategoryがある場合エラー処置
  foreach ($categoryUserData as $key){
      
    $result = $key['category'];

    if($category === $result){
      global $err;
      $err['category'] = msg03;
    }
      
  }

  if(empty($err))
  {
    try
    {
      $dbh = dbConnect();
      $sql = 'INSERT INTO category(category,user_id) VALUES(:category,:user_id)';
      $data = array(':category' => $category, ':user_id' => $_SESSION['user_id']);
      $stmt = queryPost($dbh,$sql,$data);
      // クエリ成功の場合
      if($stmt){
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
        
        <h2>カテゴリー登録</h2>
        
        
            <label class="category-input">
                
              <span class="err js-msg-category">
                  <?php if( !empty($err['category'])) echo $err['category']; ?>
              </span><br>
                
            <input type="text" name="category" class="js-valid-category" value="<?php echo getUserData('category'); ?>"><br>
                
                <p>※最大で10個まで登録可能です。</p>
                
                <a href="deleteCategory.php" class="delete-category" style="text-decoration:none">カテゴリー削除</a>
                
           </label>
        
        <div class="submit">
          <input type="submit" name="submid" class="submit" value="登録">
        </div>
        
        <a href="registNote.php" class="note-back" style="text-decoration:none">NOTE作成へ</a>
        
    </form>
    

</div>

<?php
  require('footer.php');
?>