$(document).ready(function(){
    $('#task_form').submit(function(){
        $.ajax({
            type: 'POST',
            url: 'task/add',
            data: $(this).serialize(),
            success: taskAddAction,
            error: errorAction
        });
        return false;
    });
});