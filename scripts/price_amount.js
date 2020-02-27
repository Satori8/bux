function gebi(id){
	return document.getElementById(id)
}

function obsch(){
	var add_plan = Math.round(gebi('add_plan').value);
	var serfprice = add_plan * <?php echo "$cena_serf";?>;
	gebi('serfprice').innerHTML = serfprice + "руб.";

}