
$(function() {
  var scrolltop = window.pageYOffset;
  window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame || function(f) {
        setTimeout(f, 1000 / 60)
      }
  paralax_details = new Array();
  paralax_array = new Array();
  $('#menubtnwrapper').click(function() {
    $(this).find('#menubtn').toggleClass('open');
    $('body').toggleClass('menu');
    $('.nav-wrapper').slideToggle();

  });
  $('.menuclick').click(function(e){
    e.preventDefault();
    $('#menubtnwrapper').click();
  });


  $('.parallax').each(function() {

    paralax_details.push({
      element: $(this),
      active: $(this).data('active'),
      multiplier: $(this).data('multiplier'),
      direction: $(this).data('direction')
    });
  });
  // s9_paralax = function(inputs) {
  //   if (inputs.active == null) {
  //     inputs.active = true;
  //   }
  //   if (inputs.multiplier == null) {
  //     inputs.multiplier = "0.2";
  //   }
  //   if (inputs.direction == null) {
  //     inputs.direction = "up";
  //   }
  //   this.redraw = function() {
  //     if (inputs.active == true) {
  //       if (inputs.direction == "up") {
  //         move = -Math.abs(-scrolltop * inputs.multiplier)
  //         inputs.element.css("marginTop", move + 'px');
  //       }
  //       if (inputs.direction == "down") {
  //         move = Math.abs(-scrolltop * inputs.multiplier)
  //         inputs.element.css("marginTop", move + 'px');
  //       }
  //     }
  //   };
  //   this.redraw();
  // };
  // for (i = 0; i < paralax_details.length; i++) {
  //   paralax_array.push(new s9_paralax(paralax_details[i]));
  // }
  //
  // function paralax_redraw() {
  //   scrolltop = window.pageYOffset;
  //   for (i = 0; i < paralax_array.length; i++) {
  //     paralax_array[i].redraw();
  //   }
  // }
  // window.addEventListener('scroll', function() {
  //   requestAnimationFrame(paralax_redraw);
  // }, false);



});

///////////////////////PARALAX.JS
/* detect touch */
if("ontouchstart" in window){
  document.documentElement.className = document.documentElement.className + " touch";
}
if(!$("html").hasClass("touch")){
  /* background fix */
  $(".parallax").css("background-attachment", "fixed");
}
/* fix vertical when not overflow
 call fullscreenFix() if .fullscreen content changes */
function fullscreenFix(){
  var h = $('body').height();
  // set .fullscreen height
  $(".content-b").each(function(i){
    if($(this).innerHeight() <= h){
      $(this).closest(".fullscreen").addClass("not-overflow");
    }
  });
}
$(window).resize(fullscreenFix);
fullscreenFix();
/* resize background images */
function backgroundResize(){
  var windowH = $(window).height();
  $(".background").each(function(i){
    var path = $(this);
    // variables
    var contW = path.width();
    var contH = path.height();
    var imgW = path.attr("data-img-width");
    var imgH = path.attr("data-img-height");
    var ratio = imgW / imgH;
    // overflowing difference
    var diff = parseFloat(path.attr("data-diff"));
    diff = diff ? diff : 0;
    // remaining height to have fullscreen image only on parallax
    var remainingH = 0;
    if(path.hasClass("parallax") && !$("html").hasClass("touch")){
      var maxH = contH > windowH ? contH : windowH;
      remainingH = windowH - contH;
    }
    // set img values depending on cont
    imgH = contH + remainingH + diff;
    imgW = imgH * ratio;
    // fix when too large
    if(contW > imgW){
      imgW = contW;
      imgH = imgW / ratio;
    }
    //
    path.data("resized-imgW", imgW);
    path.data("resized-imgH", imgH);
    path.css("background-size", imgW + "px " + imgH + "px");
  });
}
$(window).resize(backgroundResize);
$(window).focus(backgroundResize);
backgroundResize();
/* set parallax background-position */
function parallaxPosition(e){
  var heightWindow = $(window).height();
  var topWindow = $(window).scrollTop();
  var bottomWindow = topWindow + heightWindow;
  var currentWindow = (topWindow + bottomWindow) / 2;
  $(".parallax").each(function(i){
    var path = $(this);
    var height = path.height();
    var top = path.offset().top;
    var bottom = top + height;
    // only when in range
    if(bottomWindow > top && topWindow < bottom){
      var imgW = path.data("resized-imgW");
      var imgH = path.data("resized-imgH");
      // min when image touch top of window
      var min = 0;
      // max when image touch bottom of window
      var max = - imgH + heightWindow;
      // overflow changes parallax
      var overflowH = height < heightWindow ? imgH - height : imgH - heightWindow; // fix height on overflow
      top = top - overflowH;
      bottom = bottom + overflowH;
      // value with linear interpolation
      var value = min + (max - min) * (currentWindow - top) / (bottom - top);
      // set background-position
      var orizontalPosition = path.attr("data-oriz-pos");
      orizontalPosition = orizontalPosition ? orizontalPosition : "50%";
      $(this).css("background-position", orizontalPosition + " " + value + "px");
    }
  });
}
if(!$("html").hasClass("touch")){
  $(window).resize(parallaxPosition);
  //$(window).focus(parallaxPosition);
  $(window).scroll(parallaxPosition);
  parallaxPosition();
}
$(function(){
  function runUpdate(element){
    let price = $(element).find(':selected').data('price');
    let name = $(element).find(':selected').data('prdnam');
    let id = $(element).find(':selected').data('prd_id');
    data_element = $('.image-link:not(#productPopupLink)');

    $('#productName').html( data_element.data('prdnam'));
    $('#productPrice').html( price );
    $('input[name="prd_id"]').val( id );

  }
  runUpdate('#varients');
  $('#varients').change(function(){
    runUpdate('#varients');
  })
});

$(function(){
  $('.qty-selector').change(function(){
    $('.qty-input input').val($(this).val());
    if($(this).val() >= $(this).data('max')){
      $('.qty-select').hide()

      $('.qty-input').show()
    }
  })
});

document.querySelector('.form-number').addEventListener('wheel', function(event)
{
  event.preventDefault()
  if (event.deltaY < 0)
  {
    if(this.value >= 5){
      this.value =  parseInt(this.value) +1;
    }else{
      this.value = 5;
    }
  }
  else if (event.deltaY > 0)
  {
    if(this.value > 5){
      this.value =  parseInt(this.value) -1;
    }else{
      this.value = 5;
    }
  }

});



// $('.image-link:not(#productPopupLink)').click(function (e) {
//
//   e.preventDefault();
//
//   $(this).parent().parent().find('.active').removeClass('active');
//   $(this).parent().addClass('active');
//
//   $('#heroImage').attr("src", $(this).attr('href')).data('arr_id', $(this).data('arr_id'));
//   $('#productPopupLink').attr("href", $(this).find('img').attr('href'));
//
//   $('[name="prd_id"]', $('#productForm')).val( $(this).data('prd_id') );
//   $('#productName').html( $(this).data('prdnam') );
//   $('#productPrice').html( $(this).data('unipri') );
