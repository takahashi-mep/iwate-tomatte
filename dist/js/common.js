// $(function(){
//   $('.topBtn').click(function(){
//     var speed = 500;
//     var href= $(this).attr("href");
//     var target = $(href == "#" || href == "" ? 'html' : href);
//     var position = target.offset().top;
//     $("html, body").animate({scrollTop:position}, speed, "swing");
//     return false;
//   });
// });


$(function () {
    $("#nav-toggle").on("click", function() {
    $("body").toggleClass("open");
    });
});


$(document).ready(function(){
  $("#topBtn").hide();
  $(window).on("scroll", function() {
      if ($(this).scrollTop() > 100) {
          $("#topBtn").fadeIn(500);
      } else {
          $("#topBtn").fadeOut(500);
      }
      scrollHeight = $(document).height(); //ドキュメントの高さ 
      scrollPosition = $(window).height() + $(window).scrollTop(); //現在地 
      footHeight = $("footer").innerHeight(); //footerの高さ（＝止めたい位置）
      if ( scrollHeight - scrollPosition  <= footHeight - 55 ) { //ドキュメントの高さと現在地の差がfooterの高さ以下になったら
          $("#topBtn").css({
              "position":"absolute", //pisitionをabsolute（親：wrapperからの絶対値）に変更
              "bottom": footHeight + 10 //下からfooterの高さ + 20px上げた位置に配置
          });
      } else { //それ以外の場合は
          $("#topBtn").css({
              "position":"fixed", //固定表示
              "bottom": "20px" //下から20px上げた位置に
          });
      }
  });
  $('#topBtn').click(function () {
    $('body,html').animate({
    scrollTop: 0
    }, 400);
    return false;
  });

  // #で始まるリンクをクリックしたら実行されます
  // $('.container-xl a[href^="#"],.container-lg a[href^="#"]').click(function() {
  //   // スクロールの速度
  //   let speed = 400; // ミリ秒で記述
  //   let href= $(this).attr("href");
  //   let target = $(href == "#" || href == "" ? 'html' : href);
  //   let position = target.offset().top;
  //   $('body,html').animate({scrollTop:position}, speed, 'swing');
  //   return false;
  // });
});




