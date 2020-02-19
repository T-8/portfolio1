<?PHP
require('function.php');

debug('[[[[[[[[[[[[[[[[[[[[[[[[ノート削除]]]]]]]]]]]]]]]]]]]]]]]]');
debugStart();

require('auth.php');

  $note_id = $_SESSION['note_id'];

if(!empty($_POST))
{
    try
    {
      $dbh = dbConnect();
      $sql = 'DELETE FROM notes WHERE note_id = :note_id';
      $data = array(':note_id' => $note_id);      
      $stmt = queryPost($dbh,$sql,$data);

      // クエリ成功の場合
      if($stmt){
        debug('ノート一覧ページへ遷移します。');
        $_SESSION['msg_success'] = SUC04;
        header("Location:myNoteList.php");
      }
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = msg06;
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
    
    <form method="post" class="delete-note-form">
        
        <h2>メモ削除</h2>
        
        <p>本当に削除しますか？</p>
        
        <div class="submit">
          <input type="submit" name="submit" class="submit" value="削除">
        </div>
        
        <a href="noteList.php" class="note-back" style="text-decoration:none">マイノートへ戻る</a>
        
    </form>
    

</div>

<?php
  require('footer.php');
?>