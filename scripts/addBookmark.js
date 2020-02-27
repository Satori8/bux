var url = location.href;
var title = document.title;

if ((typeof window.sidebar == "object") && (typeof window.sidebar.addPanel == "function")) {
	window.sidebar.addPanel (title, url, "");
} else if (typeof window.external == "object") {
	window.external.AddFavorite(url, title);
} else if (window.opera) {
	x.href = url;
	x.title = title;
	x.rel = "sidebar";
	return true;
} else {
	alert("Для добавления закладки вручную нажмите Ctrl+D");
	return true;
}