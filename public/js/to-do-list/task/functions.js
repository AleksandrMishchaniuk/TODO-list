function errorAction(xhr, str){
    $('.test_div').html('<hr/>'+xhr.responseText);
}

function showErrorInputs(inputs){
    for(var key in inputs){
        $('[name="'+key+'"]').parents('.form-group').addClass('has-error');
    }
}

function makeDefaultInputs(form){
    $('.form-group', form.eq(0)).removeClass('has-error');
}

function taskChangeStatusAction(data, task_tr){
    if(+data['ok']){
        if(task_tr.length){
            task_tr.hide(300);
            task_tr.queue(function () {
                $(this).toggleClass('checked');
                var task_date = getUnixTimeFromDotFormat($('.task_deadline', this).html());
                $(this).dequeue();
                insertToTaskList($(this), task_date);
            });
        }else if($('.task_text')){
            $('.task_text').toggleClass('checked');
        }
    }
}

function taskAddAction(data){
    if(+data['ok']){
        $('[name="text"]').add('[name="deadline"]').val('');
        makeDefaultInputs($('#task_form'));
        
        var task_tr = $('#task_template').clone();
        task_tr.removeAttr('id');
        $('.task_status', task_tr.eq(0)).attr('data-id', data['data']['id'])
                .change(changeTaskStatus);
        $('.task_text', task_tr.eq(0)).html(data['data']['text'])
                .attr('href', '/task/view/'+data['data']['id']);
        $('.task_deadline', task_tr.eq(0)).html(data['data']['deadline']);
        $('.task_comments_count', task_tr.eq(0)).html(0);
        var task_date = getUnixTimeFromDotFormat(data['data']['deadline']);
        insertToTaskList(task_tr, task_date);
    }else{
        showErrorInputs(data['msg']);
    }
}

function insertToTaskList(task, task_date){
    var status = $('.task_status:checked', task.eq(0)).length;
    var tr_set = undefined;
    if(!status){
        tr_set = $('tbody.unchecked_tasks tr');
    }else{
        tr_set = $('tbody.checked_tasks tr');
    }
    
    if(!tr_set.length && !status){
        $('tbody.unchecked_tasks').append(task);
    }else if(!tr_set.length && status){
        $('tbody.checked_tasks').append(task);
    }else{
        var is_inserted = false;
        tr_set.each(function(index){
            var curent_date = getUnixTimeFromDotFormat($('.task_deadline', this).html());
            if(task_date <= curent_date){
                $(this).before(task);
                is_inserted = true;
                return false;
            }
        }); 
        if(!is_inserted){
            tr_set.last().after(task);
        }
    }
    task.show(300);
}


function commentAddAction(data){
    if(+data['ok']){
        $('[name="text"]').add('[name="name"]').val('');
        makeDefaultInputs($('#comment_form'));
        
        var comment_div = $('#comment_template').clone();
        comment_div.removeAttr('id');
        $('.comment_date', comment_div.eq(0)).html(data['data']['date']);
        $('.comment_name', comment_div.eq(0)).html(data['data']['name']);
        $('.comment_text', comment_div.eq(0)).html(data['data']['text']);
        $('.comments_list').prepend(comment_div);
        comment_div.slideDown();
    }else{
        showErrorInputs(data['msg']);
    }
}

function getUnixTimeFromDotFormat(date_str){
    date_str = date_str.replace(/([0-9]+).([0-9]+).([0-9]+)/, '$2-$1-$3');
    return Date.parse(date_str);
}

function getFieldsObjectFromForm(form){
    var data_array = $(form).serializeArray();
    var data = {};
    for(var i=0; i<data_array.length; i++){
        data[data_array[i]['name']] = data_array[i]['value'];
    }
    return data;
}


function addComment(){
    var data = getFieldsObjectFromForm(this);
    data['text'] = htmlFilter(data['text']);
    var no_valid = {value: false};
    if(!isNameValid(data['name'])){
        no_valid['name'] = false;
        no_valid['value'] = true;
    }
    if(no_valid['value']){
        showErrorInputs(no_valid);
        return false;
    }
    $.ajax({
        type: 'POST',
        url: '/comment/add',
        data: $(this).serialize(),
        success: commentAddAction,
        error: errorAction
    });
    return false;
}

function addTask(){
    var data = getFieldsObjectFromForm(this);
//    data['text'] = htmlFilter(data['text']);
    var no_valid = {value: false};
    if(!isDateValid(data['deadline'])){
        no_valid['deadline'] = false;
        no_valid['value'] = true;
    }
    if(no_valid['value']){
        showErrorInputs(no_valid);
        return false;
    }
    $.ajax({
        type: 'POST',
        url: '/task/add',
        data: data,
        success: taskAddAction,
        error: errorAction
    });
    return false;
}

function changeTaskStatus(){
    var url = '/task/update/'+$(this).attr('data-id');
    var val = $(this).filter(':checked').length;
    var task = $(this).parents('.task');
    $.ajax({
        type: 'POST',
        url: url,
        data: {status: val},
        success: function(data){
            taskChangeStatusAction(data, task);
        },
        error: errorAction
    });
}

function htmlFilter(text){
    text = text.replace(/</, '&lt;');
    text = text.replace(/>/, '&gt;');
    return text;
}

function isDateValid(date){
    var arr = date.split('.');
    return (
            arr.length === 3 &&
            arr[0]>0 && arr[0]<=31 &&
            arr[1]>0 && arr[1]<=12 &&
            arr[2]>1970 && arr[2]<=2200
            );
}

function isNameValid(name){
    return name.match(/^[-а-яА-Я\w ]{1,255}$/);
}