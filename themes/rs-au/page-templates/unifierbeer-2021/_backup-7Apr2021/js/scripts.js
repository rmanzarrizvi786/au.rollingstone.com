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

  var s;

  $(function() {
    var windowHeight;
    init();

    $(window).resize(function() {
      init();
    });

    // $('[rel="popover"]').popover();

    var popoverTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl, {
        container: popoverTriggerEl,
      });
    });

    var videoPlayed = false;

    $(window).scroll(function(e) {
      var winTop = $(this).scrollTop();

      if (winTop >= 100) {
        if (!videoPlayed) {
          videoPlayed = !videoPlayed;
          introDMPlayer.play();
          introDMPlayer.unmute();
        }
      }

      if (winTop <= $(window).height()) {
        $(".app-nav").addClass("nav-intro");
        return;
      }
      $(".app-nav")
        .removeClass("nav-intro")
        .removeClass("d-none");

      if (winTop >= 7801) {
        $(".app-nav").addClass("d-none");
        return;
      }

      if ($(window).width() < 1281 || $("html").hasClass("lt-ie9")) {
        return;
      }

      activeSlide = Math.floor(winTop / 1000) - 1;

      if (activeSlide > -1) {
        $(".app-nav li")
          .removeClass("active")
          .eq(activeSlide)
          .addClass("active");
      }
    });

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
        var dest = $(this).attr("data-position");
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

      s = skrollr.init();
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
