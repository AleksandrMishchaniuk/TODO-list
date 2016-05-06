function errorAction(xhr, str){
    $('.test_div').html('<hr/>'+xhr.responseText);
}

function errorInputAction(inputs){
    for(var key in inputs){
        $('[name="'+key+'"]').parents('.form-group').addClass('has-error');
    }
}

function defaultInputAction(form){
    $('.form-group', form.eq(0)).removeClass('has-error');
}

function taskAddAction(data){
    if(+data['ok']){
        $('[name="text"]').add('[name="deadline"]').val('');
        defaultInputAction($('#task_form'));
    }else{
        errorInputAction(data['msg']);
    }
    
}