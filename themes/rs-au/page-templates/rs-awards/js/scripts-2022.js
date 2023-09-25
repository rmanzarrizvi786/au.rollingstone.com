jQuery(document).ready(function ($) {
  var datepickerArgs1 = {
    changeMonth: true,
    changeYear: true,
    dateFormat: "d M yy",
    minDate: new Date(2020, 11, 01),
    maxDate: new Date(2021, 10, 15),
  };

  var datepickerArgs2 = {
    changeMonth: true,
    changeYear: true,
    dateFormat: "d M yy",
    minDate: new Date(2020, 11, 01),
    maxDate: new Date(2021, 10, 15),
  };

  $(".datepicker").datepicker(datepickerArgs1);
  $(".datepicker2").datepicker(datepickerArgs2);

  $(".btn-add").on("click", function () {
    var target = $("#" + $(this).data("target"));
    var clonable = target.find(".clonable:first");
    if (target.length && clonable.length) {
      var clone = clonable.clone();
      clone.removeClass("clonable").addClass("clone");
      clone.prepend(
        '<div class="d-flex justify-content-center align-items-center separator"><hr class="flex-fill"><div class="text-right"><div class="btn btn-sm btn-danger btn-remove py-0">-</div></div></div>'
      );
      clone.find("input,textarea").each(function () {
        this.value = "";
      });
      clone.find(".datepicker").each(function () {
        $(this)
          .removeAttr("id")
          .removeClass("hasDatepicker")
          .datepicker(datepickerArgs1);
      });
      clone.find(".datepicker2").each(function () {
        $(this)
          .removeAttr("id")
          .removeClass("hasDatepicker")
          .datepicker(datepickerArgs2);
      });
      clone.appendTo(target);

      scrollToElem(clone);
    }
  });

  $(document).on("click", ".btn-remove", function () {
    // if ( confirm( 'Are you sure?' ) ) {
    var toRemove = $(this).closest(".clone");
    toRemove.fadeOut("fast", function () {
      toRemove.remove();
    }); // .remove();
    // }
  });

  $(document).on("submit", "#nomination-entries", function (e) {
    $("#btn-submit").prop("disabled", true);
    $("#main").hide();
    $("#page-loader").show();
  });

  function scrollToElem(elem) {
    jQuery([document.documentElement, document.body]).animate(
      {
        scrollTop: elem.offset().top - 160,
      },
      1000
    );
  }
});
