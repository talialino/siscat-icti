$(".modalButton").click(function() {
  $("#modal")
    .modal("show")
    .find("#modalContent")
    .load($(this).attr("value"));
  $("#modal")
    .find("h3")
    .text($(this).attr("title"));
});
