<?php

  //===================
  //ログイン認証
  //===================

  if(!empty($_SESSION['login'])){
      
      debug('ログインユーザーです。');
      
      //ログイン期限日時を超えていた場合
      if(($_SESSION['login'] + $_SESSION['login_limit']) < time()){
          
          debug('ログイン有効期限オーバーです。');
          //セッションを削除
          session_destroy();
          //ログインページへ
          header("Location:login.php");
          
      }else{
          
          debug('ログイン有効期限内です');
          //ログイン期限を更新
          $_SESSION['login'] = time();
          
          if(basename($_SERVER['PHP_SELF']) === 'login.php'){
              
              debug('マイページへ遷移します');
              header("Location:mypage.php");
          }
      }
      
  }else{
      
      debug('未ログインユーザーです。');
      
      if(basename($_SERVER['PHP_SELF']) === 'mypage.php'){
          
          header("Location:login.php");
          
      }
      
      if(basename($_SERVER['PHP_SELF']) === 'registCategory.php'){
          
          header("Location:login.php");
          
      }
      
      if(basename($_SERVER['PHP_SELF']) === 'registNote.php'){
          
          header("Location:login.php");
          
      }
      
      if(basename($_SERVER['PHP_SELF']) === 'withdraw.php'){
          
          header("Location:login.php");
          
      }

      if(basename($_SERVER['PHP_SELF']) === 'noteList.php'){
          
          header("Location:login.php");
          
      }
      
      if(basename($_SERVER['PHP_SELF']) === 'myNoteList.php'){
          
          header("Location:login.php");
          
      }

      
  }

?>