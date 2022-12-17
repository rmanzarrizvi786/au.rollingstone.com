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
  $(".js-slideshow").show();

  $(document).on("submit", "#scu-scholarship-form", function(e) {
    e.preventDefault();

    var theForm = $(this);
    var formData = new FormData(theForm[0]);
    formData.append("action", "save_scu_2020");

    $("#thank-you-popup")
      .text("")
      .removeClass()
      .hide();

    changeLoadingState(theForm, true);

    $.ajax({
      type: "POST",
      url: ajaxurl,
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function(res, textStatus, jqXHR) {
        console.clear();
        console.log(res);
        if (res.success) {
          $("#thank-you-popup")
            .html(res.data)
            .slideDown()
            .removeClass()
            .addClass("success");
          theForm.trigger("reset");
          $("#reason_wordcount")
            .text("")
            .parent()
            .hide();
          setTimeout(function() {
            $("#thank-you-popup").slideUp();
          }, 3000);
        } else {
          $("#thank-you-popup")
            .html(res.data.message)
            .slideDown()
            .removeClass()
            .addClass("danger");
          if ($("[name='" + res.data.f + "']").length) {
            $("[name='" + res.data.f + "']").focus();
          }
        }
      },
      error: function(res, textStatus, jqXHR) {},
      complete: function(jqXHR, textStatus) {
        changeLoadingState(theForm, false);
      },
    });
  });

  $("#thank-you-popup").on("click", function() {
    $(this).slideUp();
  });

  var changeLoadingState = function(theForm, isLoading) {
    if (isLoading) {
      theForm
        .find(".spinner")
        .removeClass("hidden")
        .addClass("loading");
      theForm.find(".btn-submit").disabled = true;
      theForm.find(".button-text").addClass("hidden");
    } else {
      theForm.find(".btn-submit").disabled = false;
      theForm
        .find(".spinner")
        .removeClass("loading")
        .addClass("hidden");
      theForm.find(".button-text").removeClass("hidden");
    }
  };

  $(".btn-share").on("click", function(e) {
    e.preventDefault();
    popupCenter({ url: $(this).prop("href"), title: "", w: 500, h: 500 });
  });

  $("#reason").on("keyup", function(e) {
    var reason_wordcount = countWords($(this).val());
    $("#reason_wordcount").text(reason_wordcount);
    if (reason_wordcount > 0) {
      if (reason_wordcount > 50) {
        $("#reason_wordcount")
          .parent()
          .addClass("text-danger");
      } else {
        $("#reason_wordcount")
          .parent()
          .removeClass("text-danger");
      }
      $("#reason_wordcount")
        .parent()
        .show();
    } else {
      $("#reason_wordcount")
        .parent()
        .hide();
    }
  });
  function countWords(tx) {
    return tx.replace(/\w+/g, "x").replace(/[^x]+/g, "").length;
  }
});

let tickerSpeed = 0.5;
let flickity1 = null;
let flickity2 = null;
let isPaused1 = false;
let isPaused2 = false;

const slideshowEl1 = document.querySelector("#js-slideshow1");
const slideshowEl2 = document.querySelector("#js-slideshow2");

function update1() {
  if (isPaused1) return;
  if (flickity1.slides) {
    flickity1.x = (flickity1.x - tickerSpeed) % flickity1.slideableWidth;
    flickity1.selectedIndex = flickity1.dragEndRestingSelect();
    flickity1.updateSelectedSlide();
    flickity1.settle(flickity1.x);
  }
  window.requestAnimationFrame(update1);
}

function update2() {
  if (isPaused2) return;
  if (flickity2.slides) {
    flickity2.x = (flickity2.x - tickerSpeed * -1) % flickity2.slideableWidth;
    flickity2.selectedIndex = flickity2.dragEndRestingSelect();
    flickity2.updateSelectedSlide();
    flickity2.settle(flickity2.x);
  }
  window.requestAnimationFrame(update2);
}

function pause1() {
  isPaused1 = true;
}
function pause2() {
  isPaused2 = true;
}

function play1() {
  if (isPaused1) {
    isPaused1 = false;
    window.requestAnimationFrame(update1);
  }
}
function play2() {
  if (isPaused2) {
    isPaused2 = false;
    window.requestAnimationFrame(update2);
  }
}
slideshowEl1.addEventListener("mouseenter", pause1, false);
slideshowEl1.addEventListener("focusin", pause1, false);
slideshowEl1.addEventListener("mouseleave", play1, false);
slideshowEl1.addEventListener("focusout", play1, false);

function play2() {
  if (isPaused2) {
    isPaused2 = false;
    window.requestAnimationFrame(update2);
  }
}
slideshowEl2.addEventListener("mouseenter", pause2, false);
slideshowEl2.addEventListener("focusin", pause2, false);
slideshowEl2.addEventListener("mouseleave", play2, false);
slideshowEl2.addEventListener("focusout", play2, false);

document.addEventListener(
  "DOMContentLoaded",
  function() {
    imagesLoaded(document.querySelector("#slideshow-wrap1"), function(
      instance
    ) {
      flickity1 = new Flickity(slideshowEl1, {
        autoPlay: false,
        prevNextButtons: true,
        pageDots: false,
        draggable: true,
        wrapAround: true,
        selectedAttraction: 0.015,
        friction: 0.25,
      });

      flickity1.x = 0;
      flickity1.on("dragStart", function() {
        isPaused1 = true;
      });

      update1();
    });

    imagesLoaded(document.querySelector("#slideshow-wrap2"), function(
      instance
    ) {
      flickity2 = new Flickity(slideshowEl2, {
        autoPlay: false,
        prevNextButtons: true,
        pageDots: false,
        draggable: true,
        wrapAround: true,
        selectedAttraction: 0.015,
        friction: 0.25,
      });

      flickity2.x = 0;
      flickity2.on("dragStart", function() {
        isPaused2 = true;
      });

      update2();
    });
  },
  false
);
