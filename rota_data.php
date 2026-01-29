<?php
if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	include("includes/includes.inc.php"); 
	if(isset($_POST["page"])){
		$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); 
		if(!is_numeric($page_number)){die('Invalid page number!');} 
	}else{
		$page_number = 1; 
	}
	$where = ' company_id = '.$_SESSION['company_id'];
	$url = '';
	if(isset($_POST["filterdate"])  && $_POST["filterdate"]<> ""){
		$filterdate = date("Y-m-d", strtotime($_POST['filterdate']));
		if(!is_numeric($page_number)){die('Invalid page number!');} 
		$where .='  AND idle_date=' ."'".$filterdate."'";  // filterdate
		$url .= '&idle_date='.$filterdate;
		//die('sss');
	}
	if(isset($_POST["diver_filter"]) && $_POST["diver_filter"]<> ""){
		$diver_filter = filter_var($_POST["diver_filter"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); 
		if(!is_numeric($page_number)){die('Invalid page number!');} 
		 $where .=' AND driver_no='.$diver_filter;
		 $url .= '&driver_no='.$diver_filter;
		//die('sss');
	}
	if(isset($_POST["filter_year"]) && $_POST["filter_year"]<> ""){
		$filter_year = filter_var($_POST["filter_year"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); 
		if(!is_numeric($page_number)){die('Invalid page number!');} 
		$where .='  AND rota_year =' ."'".$filter_year."'";  // filterdate
		$url .= '&rota_year='.$filter_year;
		//die('sss');
	}
	if(isset($_POST["filter_month"]) && $_POST["filter_month"]<> ""){
		$filter_month = filter_var($_POST["filter_month"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); 
		if(!is_numeric($page_number)){die('Invalid page number!');} 
		$where .='  AND rota_month =' ."'".$filter_month."'";  // filterdate
		$url .= '&rota_month='.$filter_month;
		//die('sss');
	}
	if(isset($_POST["week_filter"]) && $_POST["week_filter"] <> ""){
		$currentDate  = date('Y-m-d'); // current date
		$week_filter  = $_POST["week_filter"];
		if($week_filter == 2){ // last week
		   $last7DaysDate = date('Y-m-d', strtotime('-7 days'));
		   $where .=' AND idle_date BETWEEN "'.$last7DaysDate.'" AND "'.$currentDate.'"';  // filterdate 
		   $url .= '&idle_date_last_seven='.$last7DaysDate;
		   
		}else
		if($week_filter == 3){ // next week
			$next7DaysDate = date('Y-m-d', strtotime('+7 days'));
			$where .=' AND idle_date BETWEEN "'.$currentDate.'" AND "'.$next7DaysDate.'"';  // filterdate 
			$url .= '&idle_date_next_seven='.$next7DaysDate;
			
		}
	}
	
	$item_per_page = 7;
    $results = "SELECT * FROM cab_rota WHERE ".$where."";
	$q  = mysql_query($results);
	$get_total_rows  =  mysql_num_rows($q);
	$total_pages = ceil($get_total_rows/$item_per_page);
	$page_position = (($page_number-1) * $item_per_page);
	$results  = mysql_query("SELECT * FROM cab_rota WHERE ".$where."  ORDER BY id DESC LIMIT $page_position, $item_per_page");
	if(mysql_num_rows($results) > 0){
	while($row = mysql_fetch_array($results)){
		echo '<div id="content_row_'.$row['id'].'" class="fl  content_row">';
		
			$Q="SELECT name,driver_no FROM cab_fleet WHERE driver_no ='".$row['driver_no']."'";
			$sqlQuery =mysql_query($Q);
			$row1 = mysql_fetch_array($sqlQuery);
		    echo '<div class="box w1 bgcolor1 fl m2">'.$row1['driver_no'].'--'.$row1['name'].'</div>';
				echo '	<div class="box w2 bgcolor1 fl ">'.date("d-m-Y", strtotime($row['idle_date'])).'</div>';
				echo '<div class="box w2 bgcolor1 fl ">'.$row['from_time'].'</div>';
				echo '<div class="box w2 bgcolor1 fl ">'.$row['to_time'].'</div>';
				echo '<div class="box w3 bgcolor1 fl ">';
				echo '	<a href="javascript:void(0);" id="'.$row['id'].'" class="del">';
				echo '	 <img src="images/close_icon.png" alt="close" title="delete" >';
				echo '</a>';
			echo '</div>';
		echo '  </div>';
	}
	echo '<input type="hidden" name="hidden_where" id="hidden_where" value="'.$url.'">';
	echo '<div align="center">';
		echo paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
	echo '</div>';
}else{
  echo '<span style="color:red;">Record Not Found!</span>';	
  echo '<input type="hidden" name="hidden_where" id="hidden_where" value="0">';
}	
	
	exit;
}

function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
{
  
   
   $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ 
        $pagination .= '<ul class="pagination">';
        
        $right_links    = $current_page + 3; 
        $previous       = $current_page - 3; 
        $next           = $current_page + 1;
        $first_link     = true; 
        
        if($current_page > 1){
			$previous_link = ($previous==0)? 1: $previous;
            $pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>';
          //  $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>'; 
                for($i = ($current_page-2); $i < $current_page; $i++){ 
                    if($i > 0){
                        $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                    }
                }   
            $first_link = false; 
        }
        
        if($first_link){
            $pagination .= '<li class="first active">'.$current_page.'</li>';
        }elseif($current_page == $total_pages){
            $pagination .= '<li class="last active">'.$current_page.'</li>';
        }else{
            $pagination .= '<li class="active">'.$current_page.'</li>';
        }
                
        for($i = $current_page+1; $i < $right_links ; $i++){
            if($i<=$total_pages){
                $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){ 
				$next_link = ($i > $total_pages) ? $total_pages : $i;
               // $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">&gt;</a></li>'; //next link
                $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
        }
        
        $pagination .= '</ul>'; 
    }
    return $pagination; 
}



?>

