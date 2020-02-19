<?php
  
  ini_set('log_errors','on');
  ini_set('error_log','php.log');

$debug_flg = true;
//デバッグログ関数
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ：'.$str);
  }
}

  //セッション処理
  session_save_path("C:/xampp/tmp/");
  ini_set('session.gc_maxlifetime',60*60*24*30);
  session_start();
  session_regenerate_id();

  function debugStart(){
      debug('>>>>>>>>>>>処理開始');
      debug('セッションID：'.session_id());
      debug('セッション変数の中身：'.print_r($_SESSION,true));
      debug('現在日時タイムスタンプ：'.time());
  }

  define('msg01','入力必須');
  define('msg02','名前またはパスワードが合っていません');
  define('msg03','既に登録されています');
  define('msg04','6文字以上で入力して下さい');
  define('msg05','入力が一致しません');
  define('msg06','不具合が発生しました');
  define('msg07','10文字以下で入力して下さい');
  define('msg08','半角英数字のみで入力して下さい');
  define('msg09','100文字以下で入力して下さい');
  define('msg10','30文字以下で入力して下さい');
  define('msg11','10個以上登録されています');
  define('msg12','20文字以下で入力して下さい');
  define('msg13','パスワードが合っていません');
  define('msg14','パスワードが変わっていません');
  define('SUC01','パスワードを変更しました');
  define('SUC02','プロフィールを変更しました');
  define('SUC03','メモを作成しました');
  define('SUC04','メモを削除しました');
  define('SUC05','メモを編集しました');

  $err = array();

  //データベース呼び出し---------------------------------
  function dbConnect(){
      
      $dsn = 'mysql:dbname=portfolio1;host=localhost;charset=utf8';
      $user = 'root';
      $password = '';
      $option = array(
          PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
      );
      
      $dbh = new PDO($dsn,$user,$password,$option);
      return $dbh;
  }

  //sql実行--------------------------------------------
  function queryPost($dbh, $sql, $data){
      
      $stmt = $dbh -> prepare($sql);
      if( !$stmt -> execute($data) ){
          
          debug('クエリに失敗しました');
          $err['common'] = msg06;
          return 0;
      }
      
      debug('クエリ成功');
      return $stmt;
  }

  //ユーザー情報-----------------------------------------
  function getUser($user_id){
      debug('ユーザー情報を取得します');
      
      try{
          $dbh = dbConnect();
          $sql = 'SELECT * FROM users WHERE id = :user_id';
          $data = array(':user_id' => $user_id);
          $stmt = queryPost($dbh, $sql,$data);
          
          if($stmt){
              debug('クエリ成功');
          }else{
              debug('クエリ失敗');
          }
      }catch (Exception $e){
          error_log('エラー発生:'.$e->getMessage());
      }
      return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  //ユーザー情報-----------------------------------------
  function getOneUser($name){
      debug('ユーザー情報を取得します');
      
      try{
          $dbh = dbConnect();
          $sql = 'SELECT id, name, header, icon, comment FROM users WHERE name = :name';
          $data = array(':name' => $name);
          $stmt = queryPost($dbh, $sql,$data);
          
          if($stmt){
              debug('クエリ成功');
              return $stmt->fetch(PDO::FETCH_ASSOC);
          }else{
              debug('クエリ失敗');
          }
      }catch (Exception $e){
          error_log('エラー発生:'.$e->getMessage());
      }
      
  }

  //フォーム入力保持--------------------------------------
  function getUserData($str,$flg=false){
      
      if($flg){
          $method = $_GET;
      }else{
          $method = $_POST;
      }
      
      global $dbUserData;
      //ユーザーデータがあるとき
      if( !empty($dbUserData) ){
          //フォームのエラーがある場合
          if( !empty($err_msg[$str]) ){
              //POSTにデータがあるとき
              if( isset($method[$str]) ){
                  
                  return sanitize($method[$str]);
                  
              }else{
                  //ないとき
                  return sanitize($dbUserData[$str]);
              }
          }else{
              //POSTにデータがありDBの情報と違うとき
              if(isset($method[$str]) && $method[$str] !== $dbUserData[$str]){
                  return sanitize($method[$str]);
              }else{
                  return sanitize($dbUserData[$str]);
              }
          }
      }else{
          if(isset($method[$str])){
              return sanitize($method[$str]);
          }
      }
  }

  //myNote情報--------------------------------------
  function getNote($user_id,$note_id){
      
      debug('noteを取得します。');
      debug('ユーザーID：'.$user_id);
      debug('noteID：'.$note_id);
      
      try{
          
          $dbh = dbConnect();
          $sql = 'SELECT * FROM notes WHERE user_id = :user_id AND note_id = :note_id AND delete_flg = 0';
          $data = array(':user_id' => $user_id, ':note_id' => $note_id);
          $stmt = queryPost($dbh,$sql,$data);
          
          if($stmt){
              return $stmt->fetch(PDO::FETCH_ASSOC);
          }else{
              return false;
          }
          
      }catch(Exception $e){
          error_log('エラー発生:'.$e->getMessage());
      }
      
  }

  //myカテゴリー情報--------------------------------------
  function getMyCategory($user_id){
      
      debug('マイカテゴリーを取得します。');
      
      try{
          
          $dbh = dbConnect();
          $sql = 'SELECT category_id, category FROM category WHERE user_id = :user_id AND delete_flg = 0';
          $data = array(':user_id' => $user_id);
          $stmt = queryPost($dbh,$sql,$data);
          
          if($stmt){
              return $stmt->fetchAll();
          }else{
              return false;
          }
          
      }catch(Exception $e){
          error_log('エラー発生:'.$e->getMessage());
      }
      
  }

  //カテゴリー情報--------------------------------------
  function getCategory(){
      
      debug('カテゴリーを取得します。');
      
      try{
          
          $dbh = dbConnect();
          $sql = 'SELECT category_id, category FROM category';
          $data = array();
          $stmt = queryPost($dbh,$sql,$data);
          
          if($stmt){
              return $stmt->fetchAll();
          }else{
              return false;
          }
          
      }catch(Exception $e){
          error_log('エラー発生:'.$e->getMessage());
      }
      
  }


  //mynote-------------------------------------------------
function getMyNotes($user_id, $category, $currentMinNum, $span = 6){
  debug('note情報を取得します。');
  debug('ユーザーID：'.$user_id);
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT note_id FROM notes WHERE user_id = :user_id';
      
    if(!empty($category)) $sql .= ' AND category_id = '.$category;
      
    $data = array(':user_id' => $user_id);
      
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    $result['total'] = $stmt->rowCount(); //総レコード数
    $result['total_page'] = ceil($result['total']/$span); //総ページ数

    if(!$stmt){
      return false;
    }
    
    $sql = 'SELECT n.note_id, n.note, n.note_text, n.note_img, n.user_id, c.category, n.create_date, n.update FROM notes AS n LEFT JOIN category AS c ON n.category_id = c.category_id WHERE n.user_id = :user_id AND n.delete_flg = 0 AND c.delete_flg = 0';
      
    if(!empty($category)) $sql .= ' AND n.category_id = '.$category;
      
    $sql .= ' ORDER BY note_id DESC';
      
    $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
      
    $data = array(':user_id' => $user_id);
    debug('SQL：'.$sql);
    $stmt = queryPost($dbh, $sql, $data);
      
    //$result['total'] = $stmt->rowCount(); //総レコード数
    //$result['total_page'] = ceil($result['total']/$span); //総ページ数

    if($stmt){
      // クエリ結果のデータを全レコードを格納
      $result['data'] = $stmt->fetchAll();
      return $result;
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

//note情報-------------------------------------------------
function getNotes($category, $span = 6){
  debug('note情報を取得します。');
    
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT * FROM notes LEFT JOIN category ON notes.category_id = category.category_id LEFT JOIN users ON notes.user_id = users.id';
    // 件数用のSQL文作成
    if(!empty($category)) $sql .= ' WHERE notes.category_id = '.$category;
      
    $data = array();
      
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    $result['total'] = $stmt->rowCount(); //総レコード数
    $result['total_page'] = ceil($result['total']/$span); //総ページ数

    if(!$stmt){
      return false;
    }

    if($stmt){
      // クエリ結果のデータを全レコードを格納
      $result['data'] = $stmt->fetchAll();
      return $result;
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

//oneNote情報-------------------------------------------------
function getOneNote($note_id){
    
    try{
        $dbh = dbConnect();
        $sql = 'SELECT n.note, n.note_img, n.note_text, u.name, u.icon, c.category FROM notes AS n LEFT JOIN category AS c ON n.category_id = c.category_id LEFT JOIN users AS u ON n.user_id = u.id WHERE n.note_id = :note_id';
        $data = array(':note_id' => $note_id);
        $stmt = queryPost($dbh,$sql,$data);
        
        if($stmt){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
   
    }catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

//NoteList情報-------------------------------------------------
function getSearchNotes($search, $currentMinNum, $span = 6){
    
    try{
        $dbh = dbConnect();
        
        $sql = 'SELECT note_id FROM notes AS n LEFT JOIN category AS c ON n.category_id = c.category_id LEFT JOIN users AS u ON n.user_id = u.id';

        if(!empty($search)) $sql .= ' WHERE n.note LIKE "%'.$search.'%" OR n.note_text LIKE "%'.$search.'%" OR c.category LIKE "%'.$search.'%" OR u.name LIKE "%'.$search.'%" ';
        
        $sql .= ' ORDER BY n.note_id DESC'; 
        
        debug($sql);
      
        $data = array();
        
        $stmt = queryPost($dbh, $sql, $data);
        
        $result['total'] = $stmt->rowCount(); //総レコード数
        $result['total_page'] = ceil($result['total']/$span); //総ページ数
        
        if(!$stmt){
            return false;
        }
        
        $sql = 'SELECT n.note_id, n.note, n.note_img, c.category, u.name, u.icon FROM notes AS n LEFT JOIN category AS c ON n.category_id = c.category_id LEFT JOIN users AS u ON n.user_id = u.id';
        
        if(!empty($search)) $sql .= ' WHERE n.note LIKE "%'.$search.'%" OR n.note_text LIKE "%'.$search.'%" OR c.category LIKE "%'.$search.'%" OR u.name LIKE "%'.$search.'%" ';
        
        $sql .= ' ORDER BY n.note_id DESC';
        
        $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
        
        $data = array();
        
        $stmt = queryPost($dbh,$sql,$data);
        
        if($stmt){
            $result['data'] = $stmt->fetchAll();
            return $result;
        }else{
            return false;
        }
   
    }catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

//お気に入り(いいね)情報-------------------------------------------------
function isLike($user_id,$note_id){
    
    debug('お気に入り情報があるか確認します');
    debug('note_id:'.$note_id);
    debug('user_id:'.$user_id);
    
    try{
        
        $dbh = dbConnect();
        $sql = 'SELECT * FROM `like` WHERE note_id = :note_id AND user_id = :user_id';
        $data = array(':note_id' => $note_id, ':user_id' => $user_id);
        $stmt = queryPost($dbh, $sql, $data);
        
        if( $stmt->rowCount() ){
            
            debug('お気に入りです');
            return true;
        }else{
            debug('お気に入りではありません');
            return false;
        }
    }catch(Except $e){
        error_log('エラー発生：'.$e->getMessage());
    }
}

//お気に入り(いいね)の数-------------------------------------------------
function isLikeLen($note_id){
    
    debug('お気に入り数を調べます');
    debug('note_id:'.$note_id);
    
    try{
        
        $dbh = dbConnect();
        $sql = 'SELECT * FROM `like` WHERE note_id = :note_id';
        $data = array(':note_id' => $note_id);
        $stmt = queryPost($dbh, $sql, $data);
        
        $result = $stmt->rowCount();
        
        return $result;

    }catch(Except $e){
        error_log('エラー発生：'.$e->getMessage());
    }
}

//sessionを１回だけ取得---------------------------------------
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }
}

//getパラメータ付与-------------------------------------------
function appendGetParam( $array = array() ){
    
  if( !empty($_GET) ){
      
    $str = '?';
    foreach($_GET as $key => $val){
        
      if(!in_array($key,$array,true)){
        $str .= $key.'='.$val.'&';
      }
        
    }
      
    $str = mb_substr($str, 0, -1, "UTF-8");
    return $str;
  }
    
}
  //サニタイズ-------------------------------------------
  function sanitize($str){
      return htmlspecialchars($str,ENT_QUOTES);
  }

  //画像処理--------------------------------------------
function uploadImg($file, $key){
  debug('画像アップロード処理開始');
  debug('FILE情報：'.print_r($file,true));

  if (isset($file['error']) && is_int($file['error'])) {
    try {
      // バリデーション
      // $file['error'] の値を確認。配列内には「UPLOAD_ERR_OK」などの定数が入っている。
      //「UPLOAD_ERR_OK」などの定数はphpでファイルアップロード時に自動的に定義される。定数には値として0や1などの数値が入っている。
      switch ($file['error']) {
          case UPLOAD_ERR_OK: // OK
              break;
          case UPLOAD_ERR_NO_FILE:   // ファイル未選択の場合
              throw new RuntimeException('ファイルが選択されていません');
          case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズが超過した場合
          case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過した場合
              throw new RuntimeException('ファイルサイズが大きすぎます');
          default: // その他の場合
              throw new RuntimeException('その他のエラーが発生しました');
      }

      // $file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
      // exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
      $type = @exif_imagetype($file['tmp_name']);
      if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) { // 第三引数にはtrueを設定すると厳密にチェックしてくれるので必ずつける
          throw new RuntimeException('画像形式が未対応です');
      }

      // ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
      // ハッシュ化しておかないとアップロードされたファイル名そのままで保存してしまうと同じファイル名がアップロードされる可能性があり、
      // DBにパスを保存した場合、どっちの画像のパスなのか判断つかなくなってしまう
      // image_type_to_extension関数はファイルの拡張子を取得するもの
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
      if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
          throw new RuntimeException('ファイル保存時にエラーが発生しました');
      }
      // 保存したファイルパスのパーミッション（権限）を変更する
      chmod($path, 0644);

      debug('ファイルは正常にアップロードされました');
      debug('ファイルパス：'.$path);
      return $path;

    } catch (RuntimeException $e) {

      debug($e->getMessage());
      global $err;
      $err[$key] = $e->getMessage();

    }
  }
}

  //画像表示用関数
  function showImg($path){
    if(empty($path)){
      return 'img/sample-img.png';
    }else{
      return $path;
    }
  }

  //バリデーションチェック---------------------------------

  //未入力
  function validRequired($str, $key){
      if( empty($str) ){
      global $err;
      $err[$key] = msg01;
      }
  }

  //一致
  function validMatch($str1, $str2, $key){
      if( $str1 !== $str2 ){
          global $err;
          $err[$key] = msg05;
      }
  }

  //最大文字数(名前)
  function validMaxLen($str, $key, $max=10){
     if( mb_strlen($str) > $max){
     global $err;
     $err[$key] = msg07;
     }
  }

  //最大文字数(note)
  function validMaxNote($str, $key, $max=30){
     if( mb_strlen($str) >= $max){
     global $err;
     $err[$key] = msg10;
     }
  }

  //最大カテゴリ数
  function validMaxCate($str, $key, $max=20){
     if( mb_strlen($str) >= $max){
     global $err;
     $err[$key] = msg12;
     }
  }

  //最大文字数(コメント)
  function validMaxCom($str, $key, $max=100){
     if( mb_strlen($str) >= $max){
     global $err;
     $err[$key] = msg09;
     }
  }

  //最低文字数(pass)
  function validMinPass($str, $key, $max=6){
      if( strlen($str) < $max ){
          global $err;
          $err[$key] = msg04;
      }
  }

  //名前重複チェック
  function validDup($name){
      global $err;
      
      try{
          //データベースへ接続
          $dbh = dbConnect();
          //データベース内名前検索
          $sql = 'SELECT count(*) FROM users WHERE name = :name AND delete_flg = 0';
          //プレースホルダーにpostされた名前を代入
          $data = array(':name' => $name);

          //クエリ実行
          $stmt = queryPost($dbh,$sql,$data);
          //クエリの結果を取得
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          if(!empty(array_shift($result)))
          {
            $err['name'] = msg03;
          }

    }catch(Exception $e)
      {
        error_log('エラー発生：'.$e->getMessage());
        $err['common'] = msg06;
      }
    }

  //半角英数字
  function validHalf($str,$key){
      if(!preg_match("/^[0-9a-zA-Z]+$/",$str)){
          global $err;
          $err[$key] = msg08;
      }
  }

  //selectboxチェック
  function validSelect($str, $key){
    if(!preg_match("/^[0-9]+$/", $str)){
      global $err_msg;
      $err_msg[$key] = MSG10;
    }
  }

  function validpass($str,$key){
      validMinPass($str, $key);
      validHalf($str,$key);
  }

  //ページング
  function pagination($currentPageNum, $totalPageNum, $link, $pageColNum = 5){
      
      if( $currentPageNum == $totalPageNum && $totalPageNum > $pageColNum ){
          
          //現在のページが、総ページ数と同じ かつ 総ページ数が表示項目数以上なら,左にリンク４個出す
          $minPageNum = $currentPageNum - 4;
          $maxPageNum = $currentPageNum;
          
       }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum > $pageColNum ){
          
          //現在のページが、総ページ数の１ページ前なら、左にリンク３個、右に１個出す
          $minPageNum = $currentPageNum - 3;
          $maxPageNum = $currentPageNum + 1;
              
       }elseif( $currentPageNum == 2 && $totalPageNum > $pageColNum){
          
          //現ページが2の場合は左にリンク１個、右にリンク３個だす。
          $minPageNum = $currentPageNum - 1;
          $maxPageNum = $currentPageNum + 3;
          
       }elseif( $currentPageNum == 1 && $totalPageNum > $pageColNum){
          
          //現ページが1の場合は左に何も出さない。右に５個出す。
          $minPageNum = $currentPageNum;
          $maxPageNum = 5;
          
       }elseif($totalPageNum < $pageColNum){
          
          //総ページ数が表示項目数より少ない場合は、総ページ数をループのMax、ループのMinを１に設定
          $minPageNum = 1;
          $maxPageNum = $totalPageNum;
          
       }else{
          
          //それ以外は左に２個出す。
          $minPageNum = $currentPageNum - 2;
          $maxPageNum = $currentPageNum + 2;
       }
      
       echo '<div class="pagination">';
       echo '<ul class="pagination-list">';
      
       if($currentPageNum != 1){
           echo '<li class="list-item"><a href="?p=1'.$link.'">&lt;</a></li>';
       }
      
       for($i = $minPageNum; $i <= $maxPageNum; $i++){
           echo '<li class="list-item ';
           if($currentPageNum == $i ){ echo 'active'; }
           echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
       }
      
       if($currentPageNum != $maxPageNum && $maxPageNum > 1){
        echo '<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
       }
      
       echo '</ul>';
       echo '</div>';
  }

?>