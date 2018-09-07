$(function() {
  $('#likeTopic').click(function(){
    var url = $(this).attr('data-url');
    $.post(url, function(data) {
      window.location.reload();
    });
  })

  $('.replyAble').click(function(){
    var replyId = $(this).attr('data-replyId');
    var userName = $(this).attr('data-userName');
    var content = $('#replyContent').val();
    $('#replyId').val(replyId);
    $('#replyContent').val('@' + userName + ' ' + content);
  })

  $('.input-group-captcha img').click(function(){
    var src = $(this).attr('src')
    var srcs = src.split('?')
    $(this).attr('src', srcs[0] + '?random=' + Math.random())
  })

  $('#thread-create-submit').click(function(e){
    if (/^\s*$/.test($('#thread_title').val())) {
      alert('请填写标题！');
      e.preventDefault();
      return;
    }
    if(!$('#category_id').val()) {
      alert('请选择节点！');
      e.preventDefault();
      return;
    }

    if(!$('#body_field').val()) {
      alert('请填写内容！');
      e.preventDefault();
      return;
    }
    if(!Array.isArray($('#tags').val())) {
      alert('请填写标签！');
      e.preventDefault();
      return;
    }
  })

  $('#search_icon').click(function(e) {
    $('#search_form').submit();
  })
})
