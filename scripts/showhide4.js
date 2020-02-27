$(document).ready(function() {
	$('#opislink1').click(function() {
		$('#opis1').slideToggle("slow");

		if ($('#opislink1').text()=="Правила проведения аукциона!") {
			$('#opislink1').text("Скрыть правила");
		} else {
			$('#opislink1').text("Правила проведения аукциона!");
		}
	});
});