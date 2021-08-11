/* jshint curly:true, debug:true */
/* globals $ */
/* globals Vue */

// ログイン、新規登録関連操作
$('#sign-in').on('click',(e) => {
    e.preventDefault();
    $('.modal-title').text('新規登録');
    $('#button-signin').show();
    $('#button-login').hide();
});

$('#log-in').on('click',(e) => {
    e.preventDefault();
    $('.modal-title').text('ログイン');
    $('#button-login').show();
    $('#button-signin').hide();
});

$('.btn-submit').click(function() {
  $(this).parents('form').attr('action', $(this).data('action'));
  $(this).parents('form').submit();
});