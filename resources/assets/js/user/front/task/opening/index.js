window.onload = () => {
  $(document).on('click', '.up', function() {
      $(this).parent().parent().removeClass('active');
      $(this).removeClass('up');
      $(this).addClass('down');
  });
    $(document).on('click', '.down', function() {
        $(this).parent().parent().addClass('active');
        $(this).addClass('up');
        $(this).removeClass('down');
    });
};