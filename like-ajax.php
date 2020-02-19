<?php

require('function.php');

debug('[[[[[[[[[[[[[[[[[[Ajax]]]]]]]]]]]]]]]]]]');
debugStart();

if( isset($_POST['noteId']) && isset($_SESSION['user_id']) ){
    
    debug('POST送信があります');
    $note_id = $_POST['noteId'];
    debug('noteID:'.$note_id);
    
    try{
        
        $dbh = dbConnect();
    
        $sql = 'SELECT * FROM `like` WHERE note_id = :note_id AND user_id = :user_id';
    
        $data = array(':note_id' => $note_id, ':user_id' => $_SESSION['user_id']);
    
        $stmt = queryPost($dbh, $sql, $data);
        
        $result = $stmt->rowCount();
        debug('結果の中身'.$result);
        
        //レコードがある場合
        if( !empty($result) ){
            
            $sql = 'DELETE FROM `like` WHERE note_id = :note_id AND user_id = :user_id';
            
            $data = array(':note_id' => $note_id, ':user_id' => $_SESSION['user_id']);
            
            $stmt = queryPost($dbh, $sql, $data);
            
        }else{
            
            $sql = 'INSERT INTO `like` (note_id, user_id, create_date) VALUES (:note_id, :user_id, :date)';
            
            $data = array(':note_id' => $note_id, ':user_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
            
            $stmt = queryPost($dbh, $sql, $data);
            
        }
        
      }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }
}
debug('Ajax終了');

?>