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

jQuery(document).ready(function ($) {
  $(".js-slideshow").show();

  var excludeMovies = [];

  /* autoComplete {{ */
  $(".select-movies").autocomplete({
    source: function (request, response) {
      $.ajax({
        url: ajaxurl,
        dataType: "json",
        data: {
          action: "gmoat_search_movie_2021",
          search: request.term,
          excludeMovies: excludeMovies,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    // minLength: 3,
    select: function (event, ui) {
      $(event.target).data("id", ui.item.id);
      $(event.target).parent().find(".select-movies-id").val(ui.item.id);
      $(event.target).prop("disabled", true).addClass("disabled");
      $(event.target).parent().find(".edit-select-movie").removeClass("hidden");
      updateExcludeMovies();
    },
  });

  $(".edit-select-movie").on("click", function () {
    $(this).parent().find("input").prop("disabled", false).data("id", "");
    $(this).parent().find(".select-movies-id").val("");
    $(this).parent().find(".edit-select-movie").addClass("hidden");
    updateExcludeMovies();
  });

  function updateExcludeMovies() {
    excludeMovies = [];
    $(".select-movies").each(function (i) {
      var m = $(this).data("id");
      if (m) {
        excludeMovies.push(m);
      }
    });
  }
  /* }} autoComplete */

  /* $(".select-movies").select2({
    placeholder: "Select a movie",
  });

  $(".select-movies").one("select2:open", function (e) {
    $("input.select2-search__field").prop("placeholder", "Search for a movie");
    document
      .querySelector(".select2-container--open .select2-search__field")
      .focus();
  }); */

  $('select[name="select-movies-id[]"]').on("change", function () {
    var selectMovieElem = $(this);
    // var value = selectMovieElem;

    $(".select-movies option").attr("disabled", false);
    var selectMovies = [];
    $(".select-movies").each(function (i, e) {
      if ($(this).val() > 0) {
        selectMovies.push($(this).val());
      }
    });

    $.each(selectMovies, function (index, value) {
      $(".select-movies").each(function (i, e) {
        if ($(this).prop("id") != selectMovieElem.prop("id")) {
          var selectMovieIterateId = $(this).prop("id");

          if (value != $("#" + selectMovieIterateId).val()) {
            if (
              $("#" + selectMovieIterateId + " option[value=" + value + "]")
                .length
            ) {
              $(
                "#" + selectMovieIterateId + " option[value=" + value + "]"
              ).attr("disabled", true);
            }
          }
        }
      });
    });
  });

  $(document).on("submit", "#gmoat-vote-form-2021", function (e) {
    e.preventDefault();

    var theForm = $(this);
    var formData = new FormData(theForm[0]);
    // var select_movies = [];
    formData.append("action", "save_gmoat_vote_2021");

    /* $('select[name="select-movies[]"] option:selected').each(function () {
      select_movies.push($(this).val());
    }); */

    /* for (var i = 1; i <= 3; i++) {
      var m = theForm.find("#select-movies" + i).data("id");
      if (m) select_movies.push(m);
    }
    formData.append("select-movies-id", select_movies);
    */

    $("#voted_movies").html("");

    for (var i = 1; i <= 3; i++) {
      var elem = theForm.find("#select-movies" + i);
      if ("" != $.trim(elem.val())) {
        $("#voted_movies").append("<tr><td>" + elem.val() + "</td></tr>");
      }
    }

    /* for (var i = 1; i <= 3; i++) {
      var elem = theForm.find("#select-movies" + i);
      var selectedOption = elem.find("option:selected").text();
      console.log(selectedOption);
      if ("" != $.trim(selectedOption)) {
        $("#voted_movies").append("<tr><td>" + selectedOption + "</td></tr>");
      }
    } */

    theForm.find(".js-errors").text("").hide();
    $("#js-success").text("").hide();
    $("#voted_movies_wrap").addClass("hidden");
    changeLoadingState(theForm, true);

    $.ajax({
      type: "POST",
      url: ajaxurl,
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (res, textStatus, jqXHR) {
        console.log(res);
        if (res.success) {
          $("#js-success")
            .html(
              "Thanks for voting in <em>Australia's Favourite Movies of 2021</em>."
            )
            .show();

          console.log(res);

          if (res.data.entered_comp == true) {
            $("#gmoat-comp-form").find("input,select,textarea").val(null);
            $("#gmoat-comp-form")
              .find(".select-movies")
              .val(null)
              .trigger("change");
            $("#js-success")
              .html("Your submission has been confirmed, thanks for entering!")
              .show();
            $("#enter-comp-heading").detach();
            $("#gmoat-comp-form").find(".fields-wrap").detach();
          }

          theForm.slideUp();
          theForm.detach();
          $("#gmoat-comp-form-wrap")
            .slideDown()
            .find("#entry_id")
            .val(res.data.id);

          if (res.data.share_url != "undefined") {
            $("#share_facebook").prop(
              "href",
              "https://www.facebook.com/sharer.php?u=" + res.data.share_url
            );
            $("#share_twitter").prop(
              "href",
              "https://twitter.com/intent/tweet?text=&url=" + res.data.share_url
            );
          }

          $("#voted_movies_wrap").removeClass("hidden");
        } else {
          theForm.find(".js-errors").html(res.data).show();
        }
      },
      error: function (res, textStatus, jqXHR) {},
      complete: function (jqXHR, textStatus) {
        changeLoadingState(theForm, false);
      },
    });
  });

  $(document).on("submit", "#gmoat-comp-form", function (e) {
    e.preventDefault();

    var theForm = $(this);
    var formData = new FormData(theForm[0]);
    formData.append("action", "save_gmoat_comp_2021");
    formData.append(
      "user_entry_movie",
      theForm.find("#user_entry_movie").val()
    );

    theForm.find(".js-errors").text("").hide();
    $("#js-success").text("").hide();
    changeLoadingState(theForm, true);

    $.ajax({
      type: "POST",
      url: ajaxurl,
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (res, textStatus, jqXHR) {
        if (res.success) {
          theForm.find("input,select,textarea").val(null);
          theForm.find(".select-movies").val(null).trigger("change");
          $("#js-success")
            .html("Your submission has been confirmed, thanks for entering!")
            .show();
          $("#enter-comp-heading").detach();
          theForm.find(".fields-wrap").detach();
        } else {
          theForm.find(".js-errors").text(res.data).show();
        }
      },
      error: function (res, textStatus, jqXHR) {},
      complete: function (jqXHR, textStatus) {
        changeLoadingState(theForm, false);
      },
    });
  });

  var changeLoadingState = function (theForm, isLoading) {
    if (isLoading) {
      theForm.find(".spinner").removeClass("hidden").addClass("loading");
      theForm.find(".btn-submit").disabled = true;
      theForm.find(".button-text").addClass("hidden");
    } else {
      theForm.find(".btn-submit").disabled = false;
      theForm.find(".spinner").removeClass("loading").addClass("hidden");
      theForm.find(".button-text").removeClass("hidden");
    }
  };

  $(".btn-share").on("click", function (e) {
    e.preventDefault();
    popupCenter({ url: $(this).prop("href"), title: "", w: 500, h: 500 });
  });

  $("#reason").on("keyup", function (e) {
    var reason_wordcount = countWords($(this).val());
    $("#reason_wordcount").text(reason_wordcount);
    if (reason_wordcount > 0) {
      if (reason_wordcount > 25) {
        $("#reason_wordcount").parent().addClass("text-danger");
      } else {
        $("#reason_wordcount").parent().removeClass("text-danger");
      }
      $("#reason_wordcount").parent().show();
    } else {
      $("#reason_wordcount").parent().hide();
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
  function () {
    imagesLoaded(
      document.querySelector("#slideshow-wrap1"),
      function (instance) {
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
        flickity1.on("dragStart", function () {
          isPaused1 = true;
        });

        update1();
      }
    );

    imagesLoaded(
      document.querySelector("#slideshow-wrap2"),
      function (instance) {
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
        flickity2.on("dragStart", function () {
          isPaused2 = true;
        });

        update2();
      }
    );
  },
  false
);
