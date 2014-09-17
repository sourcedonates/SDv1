$('#home').animate({
    marginTop: 0,
    opacity: 1
  },{duration: 1000,
  easing: 'jswing'});

$('.javascript').hide();

$(function() {
  $('input, textarea').placeholder();

  $('.close-error').click(function() {
	var modalheight = $('.modal').outerHeight();
    $('.modal').addClass('remove-box').animate(
    	{marginTop: -modalheight}, 
    	{
    		duration: 1000, 
    		easing: 'jswing'
    	}
    );
});


$('.close-error').click(function() {
    $('footer').addClass('sticky').delay(1000).queue(function(next){
        $(this).addClass("non-sticky");
        $('#wrapper').addClass("stickymodal");
        next();
    });
  });
});



$("#home .packages article").each(function(index) {
    $(this).delay(150*index).fadeIn(1000);
});


$(function () {
    var sWidth = "auto";
    var liLength = $(".package-overview article").length;
    if (liLength > 0) {
        sWidth = (100 / liLength) + "%";
    }
    $(".package-overview article").width(sWidth);
});


$.fn.setAllToMaxHeight = function () {
        return this.height(Math.max.apply(this, $.map(this, function (e) {
            return $(e).height()
        })));
    }

$(window).load(function () {
    $('.package-overview div').setAllToMaxHeight();
    
}); /*end load*/

window.onresize = function() {
$('.package-overview div').setAllToMaxHeight();
}



$(window).load(function () {
    $.fn.setAllToMaxHeight = function () {
        return this.height(Math.max.apply(this, $.map(this, function (e) {
            return $(e).height()
        })));
    }
    $('.payment article.evenheight').setAllToMaxHeight();
    
}); /*end load*/





$('.gift').click(function(e) {
    $('.gift-container').lightbox_me({
        centered: true, 
        overlayCSS: {background:'rgba(220,80,18,.9)'},

        onLoad: function() { 
            $('#giftform').find('input:first').focus()
            }
        });
    e.preventDefault();
});



$('.pay').click(function (e) {

    $('.payment-container').lightbox_me({
        centered: true,
    });

});

$(document).ready(function () {
  $('#sourcedonatesform').isHappy({
    fields: {
      // reference the field you're talking about, probably by `id`
      // but you could certainly do $('[name=name]') as well.
      '#nickname': {
        required: true,
        message: 'Enter a relevant nickname please',
      },
      '#steamid': {
        required: true,
        message: 'Enter a valid SteamID please',
        test: happy.steamid // this can be *any* function that returns true or false
      },
      '#payment-provider': {
        required: true,
        message: 'How do you want to pay?'
      },
      '#email': {
        required: true,
        message: 'Enter a valid email.',
        test: happy.email // this can be *any* function that returns true or false
      }
    }
  });

  $('#giftform').isHappy({
    fields: {
      // reference the field you're talking about, probably by `id`
      // but you could certainly do $('[name=name]') as well.
      '#nickname-gift': {
        required: true,
        message: 'Enter a relevant nickname please',
      },
      '#steamid-gift': {
        required: true,
        message: 'Enter a valid SteamID please',
        test: happy.steamid // this can be *any* function that returns true or false
      },
      '#payment-provider-gift': {
        required: true,
        message: 'How do you want to pay?'
      },
      '#email-gift': {
        required: true,
        message: 'Enter your friends forum email.',
        test: happy.email // this can be *any* function that returns true or false
      }
    }
  });
}); 