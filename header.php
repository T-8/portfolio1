
        <header>
            <h1 class="header-left">
                <a href="index.php" style="text-decoration:none;">
                    <i class="fas fa-paste"></i>MemoLand
                </a>
            </h1>
            
            <nav class="header-right">
                <ul>
                    <?php
                      if(empty($_SESSION['user_id'])){
                    ?>
                    
                    <li><a href="signup.php" style="text-decoration:none;">新規登録</a></li>
                    <li><a href="login.php" style="text-decoration:none;">ログイン</a></li>
                    
                    <?php
                      }else{
                    ?>
                      <li><a href="mypage.php" style="text-decoration:none;">マイページ</a></li>
                      <li><a href="logout.php" style="text-decoration:none;">ログアウト</a></li>

                    <?php
                      }
                    ?>
                            
                </ul>
            </nav>
        
        </header>