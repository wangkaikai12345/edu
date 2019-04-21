$(document).on('click', '.nav-link', function() {
  if ($(this).is('.focus-fans'))  {
    $(document).on('click', '.focus-fans', function() {
      $('.nav.second').css('display', 'block');
      $('.nav-item.second').css('float', 'left');
    });
  } else {
    $('.nav.second').css('display', 'none');
  }
});

$(document).on('click', '.focus-fans', function() {
  $('.nav.second').css('display', 'block');
  $('.nav-item.second').css('float', 'left');
});