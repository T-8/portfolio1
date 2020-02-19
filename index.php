<?php
  require('head.php'); 
?>

<?php
  require('header.php');
?>

<div class="site-height">

<div class="top-baner">
    
    <div class="bg-text">
      <p class="first-text js-first-text">Welcome！</p>
      <p class="js-second-text">このサイトは作成したメモを公開させる<br>
         情報共有サイトです。
      </p>
    </div>
  
</div>
    
<div class="about">
    
    <div class="about-text">
        
        <div class="about1">
          <h2>利用方法</h2>
          <p><a href="signup.php">新規登録</a>または<a href="login.php">ログイン</a>後</p>
        </div>
        
        <div class="js-fadein">
          <h3>ステップ１</h3>
          <p>まずは<a href="registCategory.php">カテゴリー登録</a>をしよう！</p>
          <p>このサイトでは自分だけのカテゴリーを作成することが可能です。</p>
          <p>メモ作成時にカテゴリーは必須となりますのであらかじめ作成してください。</p>
        
          <h3>ステップ２</h3>
          <p><a href="registNote.php">メモ作成</a>をしてみよう。</p>
          <p>メモを作成する際は自分で登録したカテゴリーの中から選んで作成します。</p>
          <p>作成されたメモは<a href="myNoteList.php">マイノート</a>の中に保存されます。</p>
            
          <h3>ステップ３</h3>
          <p><a href="noteList.php">みんなのメモ</a>を見てみよう！</p>
          <p>誰でも簡単にみんなのメモを見ることが出来ます。</p>
          <p>気に入ったものにはいいねしよう！</p>
        </div>
        
    </div>
    
</div>
    
</div>

<?php
  require('footer.php');
?>