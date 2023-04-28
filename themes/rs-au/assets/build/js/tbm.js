jQuery(document).ready(function ($) {
  jQuery(".sticky-side-ads-wrap").css(
    "top",
    jQuery(".l-header__wrap .l-header__content--sticky").outerHeight() + 25
  );

  if ($("#single-wrap").length) {
    $("#single-wrap").append(
      '<div class="load-more"><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>'
    );
    var button = $("#single-wrap .load-more");
    var loading = false;
    var scrollHandling = {
      allow: true,
      reallow: function () {
        scrollHandling.allow = true;
      },
      delay: 400,
    };
    var count_articles = 1;
  }

  var winTop = $(window).scrollTop();
  var page_title = document.title;
  var page_url =
    document.location.protocol +
    "//" +
    document.location.host +
    document.location.pathname;

  var $news_stories = $(".single_story");
  var top_news_story = $.grep($news_stories, function (item) {
    return $(item).position().top <= winTop + 10;
  });
  var visible_news_story = $.grep($news_stories, function (item) {
    return (
      $(item).position().top <=
      winTop + $(window).height() - $(".l-page__header").outerHeight()
    );
  });

  $.each($(".single-list-rs-nominees").find("h3"), function (index, elem) {
    var url_slug = $(this)
      .text()
      .toLowerCase()
      .replace(/ /g, "-")
      .replace(/[^\w-]+/g, "");
    var page_url_scroll = window.location.href + url_slug + "/";
    $(this).data("href", page_url_scroll);
    $(this).data("id", url_slug);
  });

  if ($(".l-article-content").find("h2").length >= 2) {
    $.each($(".l-article-content").find("h2"), function (index, elem) {
      var url_slug = $(this)
        .text()
        .toLowerCase()
        .replace(/ /g, "-")
        .replace(/[^\w-]+/g, "");
      var page_url_scroll =
        $(visible_news_story).last().find("h1").data("href") +
        "l/" +
        url_slug +
        "/";
      $(this).data("href", page_url_scroll);
      $(this).data("id", url_slug);
    });
  } else if ($(".l-article-content").find("h3").length >= 2) {
    $.each($(".l-article-content").find("h3"), function (index, elem) {
      var url_slug = $(this)
        .text()
        .toLowerCase()
        .replace(/ /g, "-")
        .replace(/[^\w-]+/g, "");
      var page_url_scroll =
        $(visible_news_story).last().find("h1").data("href") +
        "l/" +
        url_slug +
        "/";
      $(this).data("href", page_url_scroll);
      $(this).data("id", url_slug);
    });
  }

  $(window).scroll(function () {
    winTop = $(this).scrollTop();

    didScroll = true;

    if ($("#adm_leaderboard-mobile").length && screen.width <= 728) {
      if ($(window).scrollTop() >= 600) {
        $("#adm-header_mobile1").addClass("sticky-ad-bottom");
      } else {
        $("#adm-header_mobile1").removeClass("sticky-ad-bottom");
      }
    }

    if ($(".single-list-rs-nominees").length) {
      $.each($(".single-list-rs-nominees").find("h3"), function (index, elem) {
        var offset_top =
          $(window).scrollTop() + $(".l-header__wrap.tbm").outerHeight();

        if (
          offset_top <= $(this).offset().top + 50 &&
          offset_top >= $(this).offset().top - 50
        ) {
          var url_slug = $(this)
            .text()
            .toLowerCase()
            .replace(/ /g, "-")
            .replace(/[^\w-]+/g, "");
          var page_url_scroll =
            document.location.protocol +
            "//" +
            document.location.host +
            document.location.pathname;
          if ($(this).text() != "" && page_url_scroll != $(this).data("href")) {
            page_title_html_scroll = $(this).text();
            page_title_scroll = $("<textarea />")
              .html(page_title_html_scroll)
              .text();
            page_url_scroll = $(this).data("href");

            document.title = page_title_scroll;
            window.history.pushState(null, page_title_scroll, page_url_scroll);
          }
        }
      });
    }

    if ($(".l-article-content").length) {
      if ($(".l-article-content").find("h2").length >= 2) {
        $.each($(".l-article-content").find("h2"), function (index, elem) {
          if (!$(this).data("href")) {
            return;
          }

          var offset_top = $(window).scrollTop() + $("header").outerHeight();

          if (
            offset_top <= $(this).offset().top + 50 &&
            offset_top >= $(this).offset().top - 50
          ) {
            var page_url_scroll =
              document.location.protocol +
              "//" +
              document.location.host +
              document.location.pathname;
            if (
              $(this).text() != "" &&
              page_url_scroll != $(this).data("href")
            ) {
              page_title_html_scroll = $(this).text();
              page_title_scroll = $("<textarea />")
                .html(page_title_html_scroll)
                .text();
              page_url_scroll = $(this).data("href");

              document.title = page_title_scroll;
              window.history.pushState(
                null,
                page_title_scroll,
                page_url_scroll
              );
            }
          }
        });
      } else if ($(".l-article-content").find("h3").length >= 2) {
        $.each($(".l-article-content").find("h3"), function (index, elem) {
          if (!$(this).data("href")) {
            return;
          }

          var offset_top = $(window).scrollTop() + $("header").outerHeight();

          if (
            offset_top <= $(this).offset().top + 50 &&
            offset_top >= $(this).offset().top - 50
          ) {
            var page_url_scroll =
              document.location.protocol +
              "//" +
              document.location.host +
              document.location.pathname;
            if (
              $(this).text() != "" &&
              page_url_scroll != $(this).data("href")
            ) {
              page_title_html_scroll = $(this).text();
              page_title_scroll = $("<textarea />")
                .html(page_title_html_scroll)
                .text();
              page_url_scroll = $(this).data("href");

              document.title = page_title_scroll;
              window.history.pushState(
                null,
                page_title_scroll,
                page_url_scroll
              );
            }
          }
        });
      }
    }

    if ($("#single-wrap").length && count_articles < 9) {
      if (!loading && scrollHandling.allow) {
        scrollHandling.allow = false;
        setTimeout(scrollHandling.reallow, scrollHandling.delay);
        var offset =
          $(button).offset().top -
          $(window).scrollTop() -
          $(window).outerHeight();
        if (1000 > offset) {
          count_articles++;

          loading = true;
          var data = {
            action: "tbm_ajax_load_next_post",
            exclude_posts: tbm_load_next_post.exclude_posts,
            id: tbm_load_next_post.current_post,
            count_articles: count_articles,
          };
          $.post(tbm_load_next_post.url, data, function (res) {
            if (res.success) {
              tbm_load_next_post.current_post = res.data.loaded_post;
              tbm_load_next_post.exclude_posts += "," + res.data.loaded_post;
              $("#single-wrap").append(res.data.content);
              $("#single-wrap").append(button);

              var bbSlot = fusetag.getAdSlotsById("22378668229");
              if (typeof bbSlot != "undefined") {
                var slotResponseInformation =
                  bbSlot[0].getResponseInformation();
                if (typeof slotResponseInformation != "undefined") {
                  if (
                    typeof slotResponseInformation.lineItemId != "undefined"
                  ) {
                    fusetag.setTargeting("LineItemId", [
                      "'" + slotResponseInformation.lineItemId + "'",
                    ]);
                  }
                }
              }
              fusetag.setTargeting("pagepath", ["'" + res.data.pagepath + "'"]);

              loading = false;
            } else {
              button.remove();
            }
          }).fail(function (xhr, textStatus, e) {}); // AJAX post to get prev post
        }
      }
    } else {
      if (typeof button !== "undefined") {
        button.remove();
      }
    } // If $('#single-wrap').length

    if (typeof button !== "undefined") {
      $news_stories = $("#single-wrap article");
      top_news_story = $.grep($news_stories, function (item) {
        return $(item).position().top <= winTop + 100;
      });

      visible_news_story = $.grep($news_stories, function (item) {
        return $(item).position().top <= winTop + $(window).height() / 2;
      });

      if (
        $(visible_news_story).last().find("h1").text() != "" &&
        page_url != $(visible_news_story).last().find("h1").data("href")
      ) {
        page_title_html = $(visible_news_story).last().find("h1").data("title");
        page_title = $("<textarea />").html(page_title_html).text();
        page_url = $(visible_news_story).last().find("h1").data("href");

        var author = $(visible_news_story)
          .last()
          .find(".author")
          .data("author");
        var cats = $(visible_news_story).last().find(".cats").data("category");
        var tags = $(visible_news_story).last().find(".cats").data("tags");
        var pubdate = $(visible_news_story).last().find("time").data("pubdate");

        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
          AuthorCD: author,
          CategoryCD: cats,
          TagsCD: tags,
          PubdateCD: pubdate,
        });

        document.title = page_title;
        window.history.pushState(null, page_title, page_url);

        article_number = $(visible_news_story)
          .last()
          .find("h1")
          .data("article-number");
      } // If visible_news_story.last().find('h1')
    } // If button (load-more) exists
  });

  $("[data-video-crop2]").on("click", function () {
    var the_iframe = $(this).find("iframe");
    the_iframe.parent().removeAttr("hidden");
    the_iframe.prop("src", the_iframe.data("src"));
    $(".c-card__badge--play, .c-picture__badge, .c-crop__img").remove();
  });

  // $("a").on('click', function(event) {
  //   // Make sure this.hash has a value before overriding default behavior
  //   if (this.hash !== "") {
  //     // Prevent default anchor click behavior
  //     event.preventDefault();
  //     // Store hash
  //     var hash = this.hash;
  //     // Using jQuery's animate() method to add smooth page scroll
  //     // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
  //     $('html, body').animate({
  //       scrollTop: $(hash).offset().top - 80
  //     }, 800, function(){
  //       // Add hash (#) to URL when done scrolling (default click behavior)
  //       window.location.hash = hash;
  //     });
  //   } // End if
  // });
});
