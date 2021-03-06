/**
 * Created by Administrator on 2017/9/6 0006.
 */

$(function(){
    //图片预览
    $('.add' ).on('click',function(){
        var this_ = $(this );
        var ua = navigator.userAgent.toLowerCase();
        var isiOS = (ua.indexOf('iphone') != -1) || (ua.indexOf('ipad') != -1);  // ios终端
        if(!isiOS){
            this_.parent(".imgs").siblings("input").attr('capture','camera');
        }
        this_.parent(".imgs").siblings("input").fadeOut();
        this_.parent(".imgs").after('<input type="file" class="hide" id="upimg" accept="image/*">');
        var imglen = $('.img img' ).length;
        $('#upimg').click().off("change").on('change',function(){
            var size = ($(this)[0].files[0].size / 1024).toFixed(2);
            if(size <= 5120){
                var img = $(this)[0].files[0];
                var formData = new FormData();
                formData.append("picture",img);
                $.ajax({
                    type:"post",
                    url:"/home/File/uploadPicture",
                    data:formData,
                    processData : false,
                    contentType : false,
                    beforeSend: function(XMLHttpRequest){
                        $(".showbox").show();
                    },
                    success:function(data){
                        $(".showbox").hide();
                        swal.close();
                        var msg = $.parseJSON(data);
                        if(msg.code == 1){
                            if(this_.hasClass('add')){
                                //图片添加
                                if(imglen == 2){
                                    $('.add' ).fadeOut();
                                }
                                $('.img').eq(imglen).removeClass('hide' )
                                    .append('<img src='+msg.data.path+' alt="图片" data-tab='+msg.data.id+'>');
                            }else{
                                //图片修改
                                this_.find('img').remove();
                                this_.append('<img src='+msg.data.path+' alt="图片" data-tab='+msg.data.id+'>');
                            }
                            imgresize();

                        } else {
                        }
                    },
                    error : function (date) {
                        $(".showbox").hide();
                        //alert(JSON.stringify(date));
                        swal({
                            title: ' ',
                            text: '上传失败，请重新上传',
                            type: 'warning',
                            showConfirmButton:false,
                            timer:1500
                        });
                    }
                });
            } else {
                swal({
                    title: ' ',
                    text: '您的图片超过5M',
                    type: 'warning',
                    showConfirmButton:false,
                    timer:1500
                });
            }
        });

    });
});


//剪裁图片
function imgresize(){
    var font = parseInt($("html").css("font-size"))*2;
    setTimeout(function(){
        var img = $('.img img');
        var img2 = $('.img2 img');
        var img3 = $('.img3 img');
        img.each(function(){
            if($(this).width() == $(this).height()){
                $(this).height('2rem');
                $(this).width('2rem');
            }else if($(this).width() > $(this).height()){
                $(this).height('2rem' ).css({'left':-$(this).width()/2+font/2});
            }else{
                $(this).width('2rem').css({'top':-$(this).height()/2+font/2});
            }
        });
        img2.each(function(){
            if($(this).width() == $(this).height()){
                $(this).height('2rem');
                $(this).width('2rem');
            }else if($(this).width() > $(this).height()){
                $(this).height('2rem' ).css({'left':-$(this).width()/2+font/2});
            }else{
                $(this).width('2rem').css({'top':-$(this).height()/2+font/2});
            }
        });
        img3.each(function(){
            if($(this).width() == $(this).height()){
                $(this).height('2rem');
                $(this).width('2rem');
            }else if($(this).width() > $(this).height()){
                $(this).height('2rem' ).css({'left':-$(this).width()/2+font/2});
            }else{
                $(this).width('2rem').css({'top':-$(this).height()/2+font/2});
            }
        });
    },100);
}