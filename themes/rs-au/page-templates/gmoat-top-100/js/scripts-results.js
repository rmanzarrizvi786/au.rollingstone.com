const popupCenter = ({ url, title, w, h }) => {
  // Fixes dual-screen position                             Most browsers      Firefox
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

  $(".btn-share").on("click", function(e) {
    e.preventDefault();
    popupCenter({ url: $(this).prop("href"), title: "", w: 500, h: 500 });
  });
});

let tickerSpeed = 0.5;
let flickity2 = null;
let isPaused2 = false;

const slideshowEl2 = document.querySelector("#js-slideshow2");

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

function pause2() {
  isPaused2 = true;
}

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
