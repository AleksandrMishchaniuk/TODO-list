$(document).ready(function(){
    $('[name="deadline"]').datepicker({ constrainInput: true});
    
    $('#task_form').submit(addTask);
    
    $('.task_status').change(changeTaskStatus);
    
});