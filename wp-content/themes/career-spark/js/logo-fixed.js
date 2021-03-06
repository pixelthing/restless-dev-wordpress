$(document).ready( function() {

  var redTop = $('.container-red .top');
  if (redTop.length > 0) {
    var redTopOff = redTop.offset().top;
  }
  var redBottom = $('.container-red .bottom');
  if (redBottom.length > 0) {
    var redBottomOff = redBottom.offset().top;
  }
  var footerTop = $('#colophon');
  if (footerTop.length > 0) {
    var footerTopOff = footerTop.offset().top;
  }

  $(window).scroll(function(){
    
    var top = $(window).scrollTop();
    
    // header - within
    if ( top < 5 ) {
      $('#logo-back1').attr('class','').css({'z-index' : 1, '-webkit-mask-position': '0 0'});
      $('#logo-back2').removeClass('red').css({'z-index' : 0, '-webkit-mask-position': '0 0'});
    // header - bottom
    } else if ( top >= 5 && top < 160 ) {
      $('#logo-back1').attr('class','').css({'z-index' : 0});
      $('#logo-back2').addClass('red').css({'z-index' : 1, '-webkit-mask-position': '0 -' + ( 60 + top ) + 'px'});
    // red section - top
    } else if ( redTop.length > 0 && top >= (redTopOff - 160) && top < redTopOff ) {
      $('#logo-back1').removeClass('green').addClass('yellow').css({'z-index' : 1, '-webkit-mask-position': '0 -' + ( 227 + (top - redTopOff) ) + 'px'});
      $('#logo-back2').addClass('red').css({'z-index' : 0, '-webkit-mask-position': '0 -250px'});
    // red section - within
    } else if ( redTop.length > 0 && top > redTopOff && top < redBottomOff - 160 ) {
      $('#logo-back1').removeClass('green').addClass('yellow').css({'z-index' : 1, '-webkit-mask-position': '0 -250px'});
      $('#logo-back2').addClass('red').css({'z-index' : 0, '-webkit-mask-position': '0 0'});
    // red section - bottom
    } else if ( redTop.length > 0 && top >= (redBottomOff - 160) && top < (redBottomOff + 80) ) {
      $('#logo-back1').removeClass('green').addClass('yellow').css({'z-index' : 0, '-webkit-mask-position': '0 -250px'});
      $('#logo-back2').addClass('red').addClass('red').css({'z-index' : 1, '-webkit-mask-position': '0 -' + ( 146 + (top - redBottomOff) ) + 'px'});
    // footer - top
    } else if ( top >= (footerTopOff - 170) && top < footerTopOff ) {
      $('#logo-back1').removeClass('yellow').addClass('green').css({'z-index' : 1, '-webkit-mask-position': '0 -' + ( 224 + (top - footerTopOff) ) + 'px'});
      $('#logo-back2').addClass('red').css({'z-index' : 0, '-webkit-mask-position': '0 -250px'});
    // red section - within
    } else if ( top > footerTopOff && top < 999999999 ) {
      $('#logo-back1').removeClass('yellow').addClass('green').css({'z-index' : 1, '-webkit-mask-position': '0 -250px'});
      $('#logo-back2').addClass('red').css({'z-index' : 0, '-webkit-mask-position': '0 0'});
    // default
    } else {
      $('#logo-back1').attr('class','').css({'z-index' : 0});
      $('#logo-back2').addClass('red').css({'z-index' : 1, '-webkit-mask-position': '0 -250px'});
    }
        
  })

})