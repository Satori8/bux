$(document).ready(function() {
	$('#opislink1').click(function() {
		$('#opis1').slideToggle("slow");

		if ($('#opislink1').text()=="������� ���������� ��������!") {
			$('#opislink1').text("������ �������");
		} else {
			$('#opislink1').text("������� ���������� ��������!");
		}
	});
});