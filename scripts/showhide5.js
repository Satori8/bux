$(document).ready(function() {$('#opislink1').click(function() {
if ( jQuery.browser.msie && parseInt(jQuery.browser.version) == 6) {
if ($('#opis1').css("display")=="block") {$('#opis1').css("display", "none");
} else {$('#opis1').css("display", "block");
}
} else {$('#opis1').toggle("slow");
}
if ($('#opislink1').text()=='Правила ЧАТА') {$('#opislink1').text('Скрыть правила');
} else {$('#opislink1').text('Правила ЧАТА');
}
});
});