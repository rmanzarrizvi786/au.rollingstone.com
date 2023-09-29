const popupCenter = ({ url, title, w, h }) => {
  const dualScreenLeft =
    window.screenLeft !== undefined ? window.screenLeft : window.screenX;
  const dualScreenTop =
    window.screenTop !== undefined ? window.screenTop : window.screenY;

  const width = window.innerWidth
    ? window.innerWidth
    : document.documentElement.clientWidth
    ? document.documentElement.clientWidth
    : screen.width;
  const height = window.innerHeight
    ? window.innerHeight
    : document.documentElement.clientHeight
    ? document.documentElement.clientHeight
    : screen.height;

  const systemZoom = width / window.screen.availWidth;
  const left = (width - w) / 2 / systemZoom + dualScreenLeft;
  const top = (height - h) / 2 / systemZoom + dualScreenTop;
  const newWindow = window.open(
    url,
    title,
    `
      scrollbars=yes,
      width=${w / systemZoom},
      height=${h / systemZoom},
      top=${top},
      left=${left}
      `
  );
  if (window.focus) newWindow.focus();
};

jQuery(document).ready(function($) {
  $(".btn-share").on("click", function(e) {
    e.preventDefault();
    popupCenter({ url: $(this).prop("href"), title: "", w: 500, h: 500 });
  });

  $(function() {
    $.each($(".decade"), function(i, elem) {
      elem_id = $(elem).prop("id");
      $("[data-target='" + elem_id + "']").attr(
        "data-offset",
        $(elem).offset().top
      );
    });

    $(window).scroll(function(e) {
      var winTop = $(this).scrollTop();

      var top_of_video = $("#section-video").offset().top;
      var bottom_of_video =
        $("#section-video").offset().top + $("#section-video").outerHeight();
      var bottom_of_screen = $(window).scrollTop() + $(window).innerHeight();
      var top_of_screen = $(window).scrollTop();
      var height_of_video = $("#section-video").outerHeight();

      if (
        bottom_of_screen > top_of_video + height_of_video / 2 &&
        top_of_screen < bottom_of_video - height_of_video / 2
      ) {
        introDMPlayer.play();
      } else {
        introDMPlayer.pause();
      }

      if (
        winTop >=
          $("#section-video").offset().top -
            $("#section-video").outerHeight() / 2 &&
        winTop <=
          $("#section-video").offset().top +
            $("#section-video").outerHeight() / 2
      ) {
        // introDMPlayer.play();
      } else {
        // introDMPlayer.pause();
      }

      if (winTop <= $(window).height()) {
        $(".app-nav").addClass("nav-intro");
        return;
      }
      $(".app-nav")
        .removeClass("nav-intro")
        .removeClass("d-none");

      if (
        winTop >=
        parseInt($("#charities").offset().top - $("#charities").outerHeight())
      ) {
        $(".app-nav").addClass("d-none");
        return;
      }

      // if (winTop >= 7801) {

      if ($(window).width() < 1281 || $("html").hasClass("lt-ie9")) {
        return;
      }

      $.each($(".app-nav li"), function(i, elem) {
        if (winTop >= parseInt($(elem).data("offset") - 50)) {
          activeSlide = $(elem);
          if (activeSlide.length) {
            $(".app-nav li").removeClass("active");
            activeSlide.addClass("active");
          }
        }
      });
      // activeSlide = Math.floor(winTop / 1000) - 1;
    }); // $(window).scroll

    $(window).trigger('scroll');

    /* $(".scroll-arrow").click(function() {
      $("html, body").animate({
        scrollTop: 1500, // $(".decade1960").offset().top,
      });
      // $(".scroll-arrow").hide();
    }); */

    $('a[href^="#"]').on("click", function(e) {
      e.preventDefault();
      var target = $(this.hash);
      if (
        $(window).width() < 950 ||
        $(window).height() < 700 ||
        $("html").hasClass("lt-ie9")
      ) {
        var dest = target.offset().top - 24;
      } else {
        var dest = target.offset().top - 24; // $(this).attr("data-position");
      }
      $("html, body")
        .stop()
        .animate(
          {
            scrollTop: dest,
          },
          900,
          "swing"
        );
    });

    function init() {
      if (
        $(window).width() < 950 ||
        $(window).height() <= 760 ||
        $("html").hasClass("lt-ie9")
      ) {
        if (typeof s !== "undefined" && s) {
          s.destroy();
        }
        $("html")
          .removeClass("js")
          .addClass("no-js no-js-mode");
      } else {
        $("html")
          .removeClass("no-js")
          .removeClass("no-js-mode")
          .addClass("js");
      }

      if ($("html").hasClass("no-js")) return;

      windowHeight = $(window).height();

      // $("#site_wrap .app-nav").remove();
      // $("#site_wrap").append('<nav class="app-nav"><ul></ul></nav>');

      // s = skrollr.init();
      // skrollr.menu.init(s);

      /* var YPos = 1000;
      $(".decades > .decade").each(function(index, el) {
        $("#site_wrap .app-nav > ul").append(
          '<li><a data-position="' +
            YPos +
            '" href="#' +
            $(this).attr("id") +
            '"><span>' +
            $(this).data("nav") +
            "</span></a></li>"
        );
        YPos += 1000;
      });

      var navOffset = (windowHeight - $(".app-nav > ul").height()) / 2;
      $(".app-nav > ul").css("top", navOffset); */

      return;
    }
  });
});
