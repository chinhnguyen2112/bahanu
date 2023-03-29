var page = 1;
var page2 = 1;
function load_history_card() {
	$(".list_card").hide();
	$("#card").find("#loading").show();
	$.post("/history_card", {
		page2: page2,
	}).done(function (data) {
		$(".list_card").html("");
		$(".list_card").empty().append(data);
		$("#card").find("#loading").hide();
		$(".list_card").show();
	});
}
load_history_card();
$(document).on("click", ".li_ls", function () {
	var this_page = $(".active").data("page");
	$("#" + this_page).hide();
	// turn all nav-items "active" class off
	$(".li_ls").removeClass("active");
	// turn on the "active" class for this item
	$(this).addClass("active");
	var this_page = $(".active").data("page");
	$("#" + this_page).show();
});
