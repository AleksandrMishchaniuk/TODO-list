$(document).ready(function(){
    $('#comment_form').submit(addComment);
    $('.task_status').change(changeTaskStatus);
});