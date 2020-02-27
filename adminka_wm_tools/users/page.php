<style type="text/css">
.paginate {
  font: 12px;
	padding: 3px;
	margin: 3px;
	
}

.paginate a {
	border: solid 1px #3991d0;
  background-color: #F7F6D5;
  padding: 1px 3px;
  margin: 0 1px;
	
}

.paginate a.no {
	border: solid 0px #3991d0;
  background-color: #fff;
  padding: 1px 3px;
  margin: 0 1px;
  
	
}

.paginate a:hover, .paginate a:active {
	border: solid 1px #3991d0;
  background-color: #ddeffc;
  text-decoration: none;

}
.paginate a.no:hover {
  border:0px dotted #3991d0;
  border-bottom:1px dotted #3991d0;
	background-color: #fff;
  text-decoration: none;
  

}
.paginate span.current {
    border: solid 1px #3991d0;
    background-color: #3991d0;
    color: #fff;
    padding: 1px 3px;
    margin: 0 1px;
	}
	.paginate span.disabled {
		padding:2px 5px 2px 5px;
		margin:2px;
		border:0px solid #eee;
	  color:#dfdfdf;
	}
</style>
<?php  
session_start(); 


$stages = 3;
 $page = mysql_escape_string($_GET['page']);
	if($page){
		$start = ($page - 1) * $limit; 
	}else{
		$start = 0;	
		}	
 $qw3=" LIMIT $start, $limit";

 // Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	$paginate = '';

	if($lastpage > 1)
	{	
		$paginate .= "<div class='paginate'>";
		// Previous
		if ($page > 1){
			$paginate.= "<a class='no' href='$targetpage?page=$prev$var'>&larr; Предыдущая</a> ";
		}else{
		//echo "--$var--$prev--$targetpage?page=--";
			$paginate.= "<span class='disabled'>&larr; Предыдущая</span> ";	}
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<span class='current'>$counter</span>";
				}else{
					$paginate.= "<a href='$targetpage?page=$counter$var'>$counter</a>";}					
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage?page=$counter$var'>$counter</a>";}					
				}
				$paginate.= "...";
				$paginate.= "<a href='$targetpage?page=$LastPagem1$var'>$LastPagem1</a>";
				$paginate.= "<a href='$targetpage?page=$lastpage$var'>$lastpage</a>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<a href='$targetpage?page=1$var'>1</a>";
				$paginate.= "<a href='$targetpage?page=2$var'>2</a>";
				$paginate.= "...";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage?page=$counter$var'>$counter</a>";}					
				}
				$paginate.= "...";
				$paginate.= "<a href='$targetpage?page=$LastPagem1$var'>$LastPagem1</a>";
				$paginate.= "<a href='$targetpage?page=$lastpage$var'>$lastpage</a>";		
			}
			// End only hide early pages
			else
			{
				$paginate.= "<a href='$targetpage?page=1$var'>1</a>";
				$paginate.= "<a href='$targetpage?page=2$var'>2</a>";
				$paginate.= "...";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage?page=$counter$var'>$counter</a>";}					
				}
			}
		}			
				// Next
		if ($page < $counter - 1){ 
			$paginate.= " <a class='no' href='$targetpage?page=$next$var'>Следующая &rarr;</a>";
		}else{
			$paginate.= " <span class='disabled'>Следующая &rarr;</span>";
			}
			
		$paginate.= "</div>";		
}
?>