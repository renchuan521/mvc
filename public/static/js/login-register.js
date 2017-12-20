/*
 *
 * login-register modal
 * Autor: Creative Tim
 * Web-autor: creative.tim
 * Web script: #
 * 
 */


function showRegisterForm(){
    $('.loginBox').fadeOut('fast',function(){
        $('.registerBox').fadeIn('fast');
        $('.login-footer').fadeOut('fast',function(){
            $('.register-footer').fadeIn('fast');
        });
        $('.modal-title').html('Register with');
    }); 
    $('.error').removeClass('alert alert-danger').html('');
       
}
function showLoginForm(){
    $('#loginModal .registerBox').fadeOut('fast',function(){
        $('.loginBox').fadeIn('fast');
        $('.register-footer').fadeOut('fast',function(){
            $('.login-footer').fadeIn('fast');    
        });
        
        $('.modal-title').html('Login with');
    });       
     $('.error').removeClass('alert alert-danger').html(''); 
}

function openLoginModal(){
    showLoginForm();
    setTimeout(function(){
        $('#loginModal').modal('show');    
    }, 230);
    
}
function openRegisterModal(){
    showRegisterForm();
    setTimeout(function(){
        $('#loginModal').modal('show');    
    }, 230);
    
}

function loginAjax(){
    var form = new FormData();
    var serArray = $('.login-form').serializeArray();
    $.each(serArray, function(){
        form.append(this.name, this.value);
    });
    $.ajax({
        type: 'post',
        url: loginurl,
        data: form,
        dataType: 'json',
        cache           : false,
        contentType     : false,
        processData     : false,
        success: function(result){
            if(result.code==200){
                window.location.replace(homeurl);
            }else{
                shakeModal(result.msg);
            }
        }
    });
}

function registerAjax(){
    var form = new FormData();
    var serArray = $('.register-form').serializeArray();
    $.each(serArray, function(){
        form.append(this.name, this.value);
    });
    $.ajax({
        type: 'post',
        url: signupurl,
        data: form,
        dataType: 'json',
        cache           : false,
        contentType     : false,
        processData     : false,
        success: function(result){
            if(result.code==200){
                window.location.replace(homeurl);
            }else{
                shakeModal(result.msg);
            }
        }
    });
}

function insert(){
    if(uid==''){
        layer.msg('<i class="layui-icon" style="color: red;font-size: 50px;"><b>&#xe69c;</b></i>你必须要登录！<br>才可以编辑哦！', {
            time: 20000, //20s后自动关闭
            btn: ['明白了', '知道了', '哦']
        });
        return false;
    }
    $('.layui-this').removeClass('layui-this');
    $('.layui-tab-item').removeClass('layui-show');
    $('.edit').addClass('layui-this');
    $('.content-edit').addClass('layui-show');
}

function logout(){
    $.ajax({
        type: 'get',
        url: lgouturl,
        success: function(result){
            if(result.code==200){
                window.location.replace(homeurl);
            }else{
                shakeModal(result.msg);
            }
        }
    });
}

function shakeModal(msg){
    $('#loginModal .modal-dialog').addClass('shake');
             $('.error').addClass('alert alert-danger').html(msg);
             $('input[type="password"]').val('');
             setTimeout( function(){ 
                $('#loginModal .modal-dialog').removeClass('shake'); 
    }, 1000 ); 
}

   
