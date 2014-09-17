$('#home').animate({
    marginTop: 0,
    opacity: 1
  },{duration: 1000,
  easing: 'jswing'});

$('.javascript').hide();

$(function() {
  $('input, textarea').placeholder();

  $('.close').click(function() {
	var modalheight = $('.modal').outerHeight();
    $('.modal').addClass('remove-box').animate(
    	{marginTop: -modalheight}, 
    	{
    		duration: 1000, 
    		easing: 'jswing'
    	}
    );
});


$('.close').click(function() {
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




$('.packages .article:nth-child(4n+4) .package').css('margin-right','0');


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

$('.tosview').click(function(e) {
    $('.toslightbox').lightbox_me({
        centered: true, 
        overlayCSS: {background:'rgba(0,0,0,.9)'},
        });
    e.preventDefault();
});



$('.pay').click(function (e) {

    $('.payment-container').lightbox_me({
        centered: true,
    });

});

$(document).ready(function(){

  if($('input#steamid').attr("value"))  
    {    
        sendValue($('input#steamid').val());  
    }  
  if($('input#steamid-gift').attr("value"))  
  {    
      sendValue($('input#steamid-gift').val());  
  }  

   $('input#steamid').focus(function() {
        $(this).bind("change keyup input",function() {
          sendValue($(this).val());     
      });     
    }); 


    $('input#steamid-gift').focus(function() {
        $(this).bind("change keyup input",function() {
          SendValue2($(this).val());     
      });     
    }); 

    function sendValue(str){
        // $.post("php/get_image.php",{ sendValue: str },
        $('#steamid-avatar').fadeOut(500);
        $.post("/index.php/donate/get_img",{ sendValue: str },

        function(data){
            $('#steamid-avatar').wrap("<a target='_blank' class='generated_link' href='"+data.player_url+"'></a>");
            $('#steamid-avatar').fadeIn(500).attr('src', ''+data.returnValue+'');
            console.log(data);
        }, "json");
    }


    function SendValue2(str){
        // $.post("php/get_image.php",{ SendValue2: str },
        $('#steamid-avatar-gift').fadeOut(500);
        $.post("/index.php/donate/get_img",{ sendValue: str },
        function(data){
            $('#steamid-avatar-gift').wrap("<a target='_blank' class='generated_link' href='"+data.player_url+"'></a>");
            $('#steamid-avatar-gift').fadeIn(500).attr('src', ''+data.returnValue+'');
        }, "json");
    }
});


