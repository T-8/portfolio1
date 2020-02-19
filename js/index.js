$(function(){
    
    var effect_pos = 50; // 画面下からどの位置でフェードさせるか(px)
    var effect_move = 30; // どのぐらい要素を動かすか(px)
    var effect_time = 1000;// エフェクトの時間(ms) 1秒なら1000
    var effect_time2 = 5000;

    // フェードする前のcssを定義
    //aboutのフェードイン
    $('.js-fadein').css({
        opacity: 0,
        transform: 'translateY('+ effect_move +'px)',
        transition: effect_time + 'ms'
    });
    
    // スクロールまたはロードするたびに実行
    $(window).on('scroll load', function(){
        
        var scroll_top = $(this).scrollTop();
        var scroll_btm = scroll_top + $(this).height();
        effect_pos = scroll_btm - effect_pos;

        // effect_posがthis_posを超えたとき、エフェクトが発動
        $('.js-fadein').each( function() {
            var this_pos = $(this).offset().top;
            if ( effect_pos > this_pos ) {
                
                $(this).css({
                    opacity: 1,
                    transform: 'translateY(0)'
                });
            }
            
          });
    });
    
//top-banerのフェードイン
    $('.js-first-text').css({
        opacity: 0,
        transform: 'translateY('+ effect_move +'px)',
        transition: effect_time + 'ms'
    });
    
    $(window).on('load', function(){

        // effect_posがthis_posを超えたとき、エフェクトが発動
        $('.js-first-text').each( function() {
           
                $(this).css({
                    opacity: 1,
                    transform: 'translateY(0)'
                });
          });
    });
    
        $('.js-second-text').css({
        opacity: 0,
        transform: 'translateY('+ effect_move +'px)',
        transition: effect_time2 + 'ms'
    });
    
    $(window).on('load', function(){

        // effect_posがthis_posを超えたとき、エフェクトが発動
        $('.js-second-text').each( function() {
           
                $(this).css({
                    opacity: 1,
                    transform: 'translateY(0)'
                });
          });
    });
    
    
    // メッセージ表示
    var $jsShowMsg = $('#js-show-msg');
    var msg = $jsShowMsg.text();
    if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
      $jsShowMsg.slideToggle('slow');
      setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
    }
    
    //header画像ライブレビュー--------------------------------
    var $headerDrop = $('.header-drop');
    var $headerInput = $('.header-input');
    
    $headerDrop.on('dropover',function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).css('border','none');
    });
    
    $headerInput.on('change', function(e){
      $headerDrop.css('border', 'none');
      var file = this.files[0],// 2. files配列にファイルが入っています
          $img = $(this).siblings('.header-img'),// 3. jQueryのsiblingsメソッドで兄弟のimgを取得
          fileReader = new FileReader();// 4. ファイルを読み込むFileReaderオブジェクト
        
      // 5. 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event) {
        // 読み込んだデータをimgに設定
        $img.attr('src', event.target.result).show();
      };
        
      // 6. 画像読み込み
      fileReader.readAsDataURL(file);
        
    });
    
    //icon画像ライブレビュー--------------------------------
    var $iconDrop = $('.icon-drop');
    var $iconInput = $('.icon-input');
    
    $iconDrop.on('dropover',function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).css('border','none');
    });
    
    $iconInput.on('change', function(e){
      $iconDrop.css('border', 'none');
      var file = this.files[0],// 2. files配列にファイルが入っています
          $img = $(this).siblings('.icon-img'),// 3. jQueryのsiblingsメソッドで兄弟のimgを取得
          fileReader = new FileReader();// 4. ファイルを読み込むFileReaderオブジェクト
        
      // 5. 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event) {
        // 読み込んだデータをimgに設定
        $img.attr('src', event.target.result).show();
      };
        
      // 6. 画像読み込み
      fileReader.readAsDataURL(file);
        
    });
    
    //note画像ライブレビュー--------------------------------
    var $noteDrop = $('.note-drop');
    var $noteInput = $('.note-input');
    
    $noteDrop.on('dropover',function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).css('border','none');
    });
    
    $noteInput.on('change', function(e){
      $noteDrop.css('border', 'none');
      var file = this.files[0],// 2. files配列にファイルが入っています
          $img = $(this).siblings('.note-img'),// 3. jQueryのsiblingsメソッドで兄弟のimgを取得
          fileReader = new FileReader();// 4. ファイルを読み込むFileReaderオブジェクト
        
      // 5. 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event) {
        // 読み込んだデータをimgに設定
        $img.attr('src', event.target.result).show();
      };
        
      // 6. 画像読み込み
      fileReader.readAsDataURL(file);
        
    });
    
    
  
  });