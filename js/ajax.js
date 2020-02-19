$(function(){
    
//名前入力時のバリデーション
    $('.js-valid-name').on('keyup',function(e){
        
        var $that = $(this);
        
        $.ajax({
            type:'post',
            url:'name-ajax.php',
            dataType:'json',
            data:{
                name:$(this).val()
            }
        }).then( function(data) {
                
                console.log(data);
        
                if(data){
                    
                    if(data.errorFlg){
                        $('.js-msg-name').removeClass('success');
                    }else{
                        $('.js-msg-name').addClass('success');
                    }
                    
                    $('.js-msg-name').text(data.msg);
                } 
            });
    });
    
//カテゴリー入力時のバリデーション
    $('.js-valid-category').on('keyup',function(e){
        
        var $that = $(this);
        
        $.ajax({
            type:'post',
            url:'category-ajax.php',
            dataType:'json',
            data:{
                category:$(this).val()
            }
        }).then( function(data) {
                
                console.log(data);
        
                if(data){
                    
                    if(data.errorFlg){
                        $('.js-msg-category').removeClass('success');
                    }else{
                        $('.js-msg-category').addClass('success');
                    }
                    
                    $('.js-msg-category').text(data.msg);
                } 
            });
    });
    
//いいね機能
    var $like;
    var $likeNoteId;
    var $likeText;
    
    $like = $('.js-like') || null;
    $likeNoteId = $like.data('noteid') || null;
    $likeText = $('.js-like-text') || null;
    
    if($likeNoteId !== undefined && $likeNoteId !== null){
        
        $like.on('click',function(){
            
            var $this = $(this);
            
            $.ajax({
                
                type: "POST",
                url: "like-ajax.php",
                data:{ noteId : $likeNoteId }
                
            }).done(function(data){
                
                $this.toggleClass('active');
                
            }).fail(function(msg){
                
                console.log('Ajax Error');
            });
        });
    }
    
    
});