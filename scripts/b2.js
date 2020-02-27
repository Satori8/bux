function banners(i) {
	i1 = 0;
	sek = 2000;
	var picturs;
	m = i;
	picturs = imgArray[i];

	document.images[i1].src = picturs;
	i = i + 1;

	if (i == imgArray.length) { 
		i = 0;
	}

	j = i;
	timerID = setTimeout("banners(j)",sek);
}
