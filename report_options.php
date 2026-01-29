<?php
include("includes/includes.inc.php");
include("dompdf_config.inc.php");
ob_start();
ob_get_contents();
ini_set("memory_limit", "200M");
$tab = false;
$queryStr = "";
$aVal  = array(); 
	$defaultAtive = 'tab_show';
	 $invoice_active = 'a_active'; // active class
	 $checkInvoiceFor = 'invoice'; // default value
	 $html = 'No Data Found.';
	 $pdf = false;
	if(isset($_REQUEST['check_invoice_for']) && $_REQUEST['check_invoice_for']){
	  	$checkInvoiceFor  = $_REQUEST['check_invoice_for']; // check invoice for
		
		if($checkInvoiceFor == 'invoice'){ // invoice generation
			
			$tabpage  = 'new_reports.php'; // redirection page
			$checkInvoiceFor = 'invoice';
			/*******************Tab acative section***********************/
			$auto_invoice_active = '';
			$invoice_management_active ="";
			$invoice_active = 'a_active';
			$task_invoice_active = "";
			$operator_jobs_record_active = '';
			$driver_rota_active ='';
			$driver_report_active = '';
			$turn_over_active = '';
			/****************************************************/
			
			/****************Tab Content************************/
			$defaultAtive = "tab_show";
			/***************Tab Content*************************/
			
			$tab = true; // set it true for tab generations
			$defaultVal = 'bid=bid'; // query string start with
			$aVal['invoice_discount_price'] = $_REQUEST['invoice_discount_price'];  
			$aVal['invoice_account_user'] = $_REQUEST['invoice_account_user']; 
			$aVal['invoice_driver_no'] = $_REQUEST['invoice_driver_no']; 
			$aVal['invoice_payment_type'] = $_REQUEST['invoice_payment_type']; 
			$aVal['invoice_refernce_no'] = $_REQUEST['invoice_refernce_no']; 
			$aVal['booking_status'] = 6; 
			$aVal['report_fromdate'] = $_REQUEST['invoice_from_date']; 
			$aVal['report_todate'] = $_REQUEST['invoice_to_date']; 
			$queryStr = queryString($aVal,$defaultVal);
		}else
		if($checkInvoiceFor == 'auto_invoice'){ // auto invoice generation
			
			$tabpage  = 'new_reports.php'; // redirection page
			/*******************Tab active link***********************/
			$auto_invoice_active = 'a_active';
			$invoice_active = '';
			$invoice_management_active ="";
			$task_invoice_active = "";
			$operator_jobs_record_active = '';
			$driver_rota_active ='';
			$driver_report_active = '';
			$turn_over_active = '';
			/*******************Tab active link***********************/
			
			/****************Tab Content************************/
			$auto_invoice_tab_active = 'tab_show';
			$defaultAtive = "tab_hide";
			/***************Tab Content*************************/
			
			$checkInvoiceFor = 'auto_invoice';
			$tab = true;  // set it true for tab generations
			$defaultVal = 'bid=bid'; // query string start with
			$aVal['invoice_discount_price'] = $_REQUEST['auto_invoice_discount_price'];  
			$aVal['invoice_account_user'] = $_REQUEST['auto_invoice_account_user']; 
			$aVal['invoice_driver_no'] = $_REQUEST['auto_invoice_driver_no']; 
			$aVal['invoice_payment_type'] = $_REQUEST['auto_invoice_payment_type']; 
			$aVal['invoice_refernce_no'] = $_REQUEST['auto_invoice_refernce_no']; 
			$aVal['booking_status'] = 6; 
			$aVal['report_fromdate'] = $_REQUEST['auto_invoice_from_date']; 
			$aVal['report_todate'] = $_REQUEST['auto_invoice_to_date']; 
			$queryStr = queryString($aVal,$defaultVal);
		}else // driver_report
		if($checkInvoiceFor == 'driver_report'){ 
			// set it to true for driver report 
			$checkInvoiceFor = 'driver_report'; // set it for the check of activation
			$tab = true;  
			$tabpage  = 'new_driver_report.php'; // redirection page  set it to where you want redirect
			
			/*******************Tab active link***********************/
			$auto_invoice_active = '';
			$invoice_active = '';
			$invoice_management_active ="";
			$task_invoice_active = "";
			$operator_jobs_record_active = '';
			$driver_rota_active ='';
			$driver_report_active = 'a_active';
			$turn_over_active = '';
			/*******************Tab active link***********************/
			
			/****************Tab Content************************/
			$driver_report_tab_active = 'tab_show';
			$defaultAtive = "tab_hide";
			/***************Tab Content*************************/
			$defaultVal = 'bid=bid'; // query string start with
			$aVal['report_fromdate'] = $_REQUEST['driver_report_from_date']; 
			$aVal['report_todate'] = $_REQUEST['driver_report_to_date']; 
			$aVal['driver'] = $_REQUEST['driver_report_driver_no']; 
			$queryStr = queryString($aVal,$defaultVal);
		}else
		if($checkInvoiceFor == 'turn_over'){ 
		  
			$checkInvoiceFor = 'turn_over'; // set it for the check of activation
			$tab = true;  // demo.false
			$tabpage  = 'turn_over_reports.php'; // redirection page  set it to where you want redirect
			/*******************Tab active link***********************/
			$auto_invoice_active = '';
			$invoice_active = '';
			$invoice_management_active ="";
			$task_invoice_active = "";
			$operator_jobs_record_active = '';
			$driver_rota_active ='';
			$driver_report_active = '';
			$turn_over_active = 'a_active';
			/*******************Tab active link***********************/
			
			/****************Tab Content************************/
			$turn_over_tab_active = 'tab_show';
			$defaultAtive = "tab_hide";
			/***************Tab Content*************************/ 
			$defaultVal = 'bid=bid'; // query string start with
			$aVal['report_fromdate'] = $_REQUEST['turn_over_from_date']; 
			$aVal['report_todate'] = $_REQUEST['turn_over_to_date'];  
			$aVal['booking_status'] = $_REQUEST['booking_status'];  // here booking status comes
			$queryStr = queryString($aVal,$defaultVal); // make it defualt value
		}else
		if($checkInvoiceFor == 'task_invoice'){
			$pdf = true; //set true for pdf generations
			$checkInvoiceFor = 'task_invoice';
			
			/*******************Tab acative section***********************/
			$task_invoice_active = "a_active";
			$invoice_management_active ="";
			$auto_invoice_active = '';
			$invoice_active = '';
			$operator_jobs_record_active = '';
			$driver_rota_active ='';
			$driver_report_active = '';
			$turn_over_active = '';
			/*******************Tab acative section***********************/
			
			/****************Tab Content************************/
			$task_invoice_tab_active = 'tab_show';
			$defaultAtive = "tab_hide";
			/***************Tab Content*************************/
			$html = getTaskInvoiceHTML($_POST);
		}else
		if($checkInvoiceFor == 'driver_rota'){  // driver rota
			$pdf = true; //set true for pdf generations
			$checkInvoiceFor = 'driver_rota'; 
			
			/*******************Tab acative section***********************/
			$task_invoice_active = "";
			$invoice_management_active ="";
			$auto_invoice_active = '';
			$invoice_active = '';
			$operator_jobs_record_active = '';
			$driver_rota_active = 'a_active';
			$driver_report_active = '';
			$turn_over_active = '';
			/*******************Tab acative section***********************/
			
			/****************Tab Content************************/
			$driver_rota_tab_active = 'tab_show';
			$defaultAtive = "tab_hide";
			/***************Tab Content*************************/
			$html = getDriverRotaHtml($_POST,$_SESSION['company_id']);
		}else
		if($checkInvoiceFor == 'invoice_management'){
			
			$pdf = true; //set true for pdf generations
			$checkInvoiceFor = 'invoice_management';
			
			/*******************Tab acative section***********************/
			$task_invoice_active = "";
			$invoice_management_active ="a_active";
			$auto_invoice_active = '';
			$invoice_active = '';
			$operator_jobs_record_active = '';
			$driver_rota_active ='';
			$driver_report_active = '';
			$turn_over_active = '';
			/*******************Tab acative section***********************/
			
			/****************Tab Content************************/
			$invoice_management_tab_active = 'tab_show';
			$defaultAtive = "tab_hide";
			/***************Tab Content*************************/
			$html = getInvoiceManagement($_POST);
		 }else 
		if($checkInvoiceFor == 'operator_jobs_record'){
			
			$pdf = true; //set true for pdf generations
			$checkInvoiceFor = 'operator_jobs_record'; 
			
			/*******************Tab acative section***********************/
			$task_invoice_active = "";
			$invoice_management_active ="";
			$auto_invoice_active = '';
			$invoice_active = '';
			$operator_jobs_record_active = 'a_active';
			$driver_rota_active ='';
			$driver_report_active = '';
			$turn_over_active = '';
			/*******************Tab acative section***********************/
			
			/****************Tab Content************************/
			$operator_jobs_record_tab_active = 'tab_show';
			$defaultAtive = "tab_hide";
			/***************Tab Content*************************/
			$html = getOperatorJobsRecord($_POST,$_SESSION['company_id']); // assign HTML to PDF file
		 }
	}

   // make auto query string
   function queryString($aVal,$defaulVal=''){
		
		$queryStr = '';
		if($defaulVal <> ""){
			$queryStr  = $defaulVal;
		}
		foreach($aVal as $key=>$value){
			$queryStr  .= '&'.$key.'='.$value;  
		}
	  return $queryStr; 
 	}
 
    if($pdf == true){
	    // set $html variable for populate data. 
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render(); 
		$dompdf->output();
		$dompdf->stream("icabit_report".time().".pdf");	
	}
	
	if(isset($tab) && $tab == true){?>
	<script type="text/javascript"> 
		window.open('<?php echo $tabpage;?>?<?php echo $queryStr;?>',
		'popuppage','width=1450,toolbar=1,resizable=1,scrollbars=yes,height=1000,top=100,left=100');
	</script>
	<?php }?>


<!DOCTYPE html>
 <html lang="en">
   <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>IcabIt</title>

    <!-- Bootstrap -->
    <link href="reports_scripts/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="reports_scripts/css/all.css">
	<link href='https://fonts.googleapis.com/css?family=Lato:100,300,100italic,300italic,400italic,400,700,700italic,900' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="reports_scripts/css/jquery-ui.css" rel="stylesheet">
    </head>
	<body>
		<div id="wrapper">
			<div class="header">
				<span>PRINT REPORTS</span>
			</div> 
			<div class="report-holder"> 
				
                <ul class="report-list">
					<li><a href="#" <?php if(isset($invoice_active))echo 'class="'.$invoice_active.'"';?> id="invoice" >Invoice</a></li>
					<li><a href="custom_invioce.php" target="_blank" >Custom Invoice</a></li>
					<li><a href="#" id="auto_invoice" <?php if(isset($auto_invoice_active))echo 'class="'.$auto_invoice_active.'"';?>>Auto Invoice</a></li>
					<li><a href="#" id="task_invoice"  <?php if(isset($task_invoice_active))echo 'class="'.$task_invoice_active.'"';?>>Task Invoice</a></li>
					<!--<li><a href="#" id="job_sheet_total" class="disable_c">Job Sheet + Total</a></li>-->
					<!--<li><a href="#" id="driver_statement" class="disable_c">Driver Statement</a></li>-->
					<!--<li><a href="#" id="council_sheet" class="disable_c">Council Sheet</a></li>-->
					<!--<li><a  href="#" id="trun_over_company_earning" class="disable_c">Turnover(Company Earning)</a></li>-->
					<li><a href="#" id="driver_report" <?php if(isset($driver_report_active))echo 'class="'.$driver_report_active.'"';?>>Driver Reports</a></li>
					<!--<li><a href="#" id="letter" class="disable_c">Letters</a></li>-->
					<!--<li><a href="#" id="job_sheet" class="disable_c">Job Sheet</a></li>-->
					<!--<li><a href="#" id="account_total" class="disable_c">Account Total</a></li>-->
					<li><a href="#" id="turn_over" <?php if(isset($turn_over_active))echo 'class="'.$turn_over_active.'"';?>>Turnover</a></li>
					<li><a href="#" id="invoice_management"  
					<?php if(isset($invoice_management_active))echo 'class="'.$invoice_management_active.'"';?>>Invoice Management</a></li>
					<!--<li><a href="#" id="operator_log" class="disable_c">Operator Log</a></li>-->
					<!--<li><a href="#" id="operator_rota" class="disable_c">Operator Rota</a></li>-->
					<li><a href="#" id="driver_rota" <?php if(isset($driver_rota_active)) echo 'class="'.$driver_rota_active.'"';?>>Driver Rota</a></li>
					<li><a href="#" id="sms_report" class="disable_c">SMS Report</a></li>
					<!--<li><a href="#" id="recipt_report" class="disable_c">Receipt Statement</a></li>-->
				<!--	<li><a class="disable_c" href="#" id="turn_over_driver">Turnover (Driver Statement)</a></li>-->
                    <li><a <?php if(isset($operator_jobs_record_active)) echo 'class="'.$operator_jobs_record_active.'"';?>
                     href="#" id="operator_jobs_record">Operator Jobs Record</a></li>
				</ul>  
                 
			</div>
           
           <form name="f1" id="f1" method="post" enctype="multipart/form-data" action=""> 
            
            <div class="account-holder <?php echo $defaultAtive;?>" id="tab_invoice">
				<div class="account-heading">
					<div class="container-holder">
						<span>Invoice</span>
					</div>
				</div>
				<div class="container-holder holder"> 
					<div class="row">
						<div class="col-lg-4">
							<input type="text" name="invoice_refernce_no" placeholder="REF NO/JOB REF" id="invoice_refernce_no" value="">
							<div class="select">
								<span class="arr"></span>
								<select onChange="getDrivers(this.value,'invoice_driver_no')">
                               <option  value="" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Select Texibase</option>
                                <option  value="0" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">All</option>
									<?php
                                    $Query = mysql_query( "SELECT id,name FROM cab_base WHERE company_id =".$_SESSION['company_id']);
                                    while( $row = mysql_fetch_assoc( $Query ) ){?>
                                    <option value="<?php echo $row['id'];?>" style="padding: 5px 10px; color: #b5b5b5;  text-transform: 
                                    	capitalize; background:#fff; font-size:11px;">
                                   	  <?php echo  $row['name'];?>
                                    </option>
                                    <?php } ?>
                                </select>
							</div>
                            <div class="select tab_hide" id="invoice_driver_no"></div>
							<div class="select">
								<span class="arr"></span>
                                    <select name="invoice_payment_type">
                                    <option value="">All</option>
                                    <option value="2" 
                                    style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Cash in hand</option>
                        <option value="1" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Credit Card</option>
                        <option value="3" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Paypal</option>
                        <option value="4" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Account</option>
                                    </select>
                            </div>
							<button type="submit" class="show">Show Statement</button>
                        </div>
                        
                        
                        
						<div class="col-lg-4">
							
                            <div class="select">
								<span class="arr"></span>
								 <select  name="invoice_account_user" id="invoice_account_user" >
                                <option  value="" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">
                                	Select Account User
                                </option>
                                <option  value="0" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">All</option>
									<?php
                                    $Query = mysql_query( "SELECT id,name FROM cab_account_user  WHERE company_id =".$_SESSION['company_id']);
                                    while( $row = mysql_fetch_assoc( $Query ) ){?>
                                    <option value="<?php echo $row['id'];?>" style="padding: 5px 10px; color: #b5b5b5;  text-transform: 
                                    	capitalize; background:#fff; font-size:11px;">
                                   	  <?php echo  $row['name'];?>
                                    </option>
                                    <?php } ?>
                                </select>
							</div>
                            
                            
							<div class="radio">
								<!--<input id="percent" type="radio"  value="percent"  name="invoice_discount_radio" >-->
								<label for="percent" class="percent">
									<div class="text-field">
										<input type="text"  name="invoice_discount_price" value="" placeholder="Discount in 0.00">
										<span></span>
									</div>
								</label>
								<!--<input id="extact" type="radio" value="exact"  name="invoice_discount_radio" checked>
								<label for="extact">EXACT</label>-->
							</div>
						</div>
						<div class="col-lg-4">
							<!--<div class="checkbox">
								<input id="check5" type="checkbox" name="invoice_vat_mode" value="check5">
								<label for="check5">VAT MODE</label>
							</div>-->
							<input type="text" placeholder="FROM DATE " name="invoice_from_date" id="invoice_from_date" 
                            value="<?php echo  date('d-m-Y', strtotime('-7 days'));?>"  required >
                            <input type="text" placeholder="TO DATE " name="invoice_to_date" id="invoice_to_date" value="<?php echo date('d-m-Y');?>" required>
							
						</div>
					</div>
				</div>
			</div>
            
            <!--------------------Tab Auto Invoice------------------------>
            <div class="account-holder  <?php if(isset($auto_invoice_tab_active)) echo $auto_invoice_tab_active; else echo 'tab_hide';?>" id="tab_auto_invoice">
				<div class="account-heading">
					<div class="container-holder">
						<span>auto Invoice</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text" name="auto_invoice_refernce_no" placeholder="REF NO/JOB REF" id="auto_invoice_refernce_no" value="" onkeypress="return isNumber(event)" >
							<div class="select">
								<span class="arr"></span>
								<select onChange="getDrivers(this.value,'auto_invoice_driver_no')">
                                <option  value="0" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">All</option>
									<?php
                                    $Query = mysql_query( "SELECT id,name FROM cab_base WHERE company_id =".$_SESSION['company_id']);
                                    while( $row = mysql_fetch_assoc( $Query ) ){?>
                                    <option value="<?php echo $row['id'];?>" style="padding: 5px 10px; color: #b5b5b5;  text-transform: 
                                    	capitalize; background:#fff; font-size:11px;">
                                   	  <?php echo  $row['name'];?>
                                    </option>
                                    <?php } ?>
                                </select>
							</div>
                            <div class="select tab_hide" id="auto_invoice_driver_no"></div>
                            
                            <div class="select">
								<span class="arr"></span>
                                
                                <select  name="auto_invoice_account_user" id="auto_invoice_account_user" >
                                <option  value="" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">
                                	Select Account User
                                </option>
                                <option  value="0" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">All</option>
									<?php
                                    $Query = mysql_query( "SELECT id,name FROM cab_account_user  WHERE company_id =".$_SESSION['company_id']);
                                    while( $row = mysql_fetch_assoc( $Query ) ){?>
                                    <option value="<?php echo $row['id'];?>" style="padding: 5px 10px; color: #b5b5b5;  text-transform: 
                                    	capitalize; background:#fff; font-size:11px;">
                                   	  <?php echo  $row['name'];?>
                                    </option>
                                    <?php } ?>
                                </select>
                              	
							</div>
							<button type="submit" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
                                
                                <select name="auto_invoice_payment_type">
                                <option value="">All</option>
                                <option value="2" 
                                style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Cash in hand</option>
                                <option value="1" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Credit Card</option>
                                <option value="3" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Paypal</option>
                                <option value="4" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Account</option>
                                </select>
                            </div>
							 <input type="text" placeholder="DISCOUNT IN 0.00" name="auto_invoice_discount_price" value="">
							<!--<input type="text" placeholder="SERVICE CHARGE IN %">-->
						</div>
						<div class="col-lg-4">
							<!--<div class="checkbox">
								<input id="check5" type="checkbox" name="check" value="check5">
								<label for="check5">VAT MODE</label>
							</div>-->
							<input type="text" placeholder="FROM DATE" id="auto_invoice_from_date" name="auto_invoice_from_date" 
                            value="<?php echo  date('d-m-Y', strtotime('-7 days'));?>">
							<input type="text" placeholder="TO DATE" id="auto_invoice_to_date" name="auto_invoice_to_date" value="<?php echo date('d-m-Y');?>">
						</div>
					</div>
				</div>
			</div>
            
            <div class="account-holder <?php if(isset($task_invoice_tab_active)) echo $task_invoice_tab_active; else echo 'tab_hide';?>" id="tab_task_invoice">
				<div class="account-heading">
					<div class="container-holder">
						<span>Task invoice</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text"  placeholder="REF NO/ JPB REf" name="auto_invoice_refernce_no" id="auto_invoice_refernce_no" maxlength="11" required onkeypress="return isNumber(event)" value="REF NO/ JPB REf..">
							<div class="select">
								<span class="arr"></span>
								<select>
                               <option  value="" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Select Texibase</option>
                                <option  value="0" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">All</option>
									<?php
                                    $Query = mysql_query( "SELECT id,name FROM cab_base WHERE company_id =".$_SESSION['company_id']);
                                    while( $row = mysql_fetch_assoc( $Query ) ){?>
                                    <option value="<?php echo $row['id'];?>" style="padding: 5px 10px; color: #b5b5b5;  text-transform: 
                                    	capitalize; background:#fff; font-size:11px;">
                                   	  <?php echo  $row['name'];?>
                                    </option>
                                    <?php } ?>
                                </select>
							</div>
						<input type="text" placeholder="TO DATE " name="task_invoice_to_date" id="task_invoice_to_date" value="<?php echo date('d-m-Y');?>" required>
							<textarea placeholder="description" name="task_invoice_description" required>Description..</textarea>
							
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="QUANTITY" id="task_invoice_quantity" name="task_invoice_quantity" required onkeypress="return isNumber(event)" maxlength="2" value="QUANTITY..">
							<input type="text" placeholder="COST" name="task_invoice_cost" value="COST.." required onkeypress="return isNumber(event)" maxlength="4">
							<input type="text" placeholder="TAX" name="task_invoice_tax" value="TAX.." required onkeypress="return isNumber(event)" maxlength="2">
							<input type="text" placeholder="ACCOUNT NAME" name="task_invoice_account_name" value="Account Name.."  required >
                            <button type="submit" class="show">Show Statement</button>
							<!--<div class="checkbox">
								<input  type="checkbox" name="task_invoice_vat_mode" value="check5" id="check5">
								<label for="check5">VAT MODE</label>
							</div>-->
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="FROM DATE " name="task_invoice_from_date" id="task_invoice_from_date" 
                            value="<?php echo  date('d-m-Y', strtotime('-7 days'));?>"  required > 
							 <input type="text" placeholder="RATE" name="task_invoice_rate" value="RATE.." required onkeypress="return isNumber(event)"  maxlength="5">
                            <input type="text" placeholder="SUB TOTAL" name="task_invoice_sub_total" value="SUB TOTAL.." required onkeypress="return isNumber(event)" maxlength="6">
							<input type="text" placeholder="TOTAL" name="task_invoice_total" value="TOTAL.." required onkeypress="return isNumber(event)" maxlength="7">
                           
						</div>
					</div>
				</div>
			</div>
            
            <div class="account-holder tab_hide" id="tab_job_sheet_total">
				<div class="account-heading">
					<div class="container-holder">
						<span>JOB SHEET + TOTAL</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text"  placeholder="FROM DATE">
							<input type="text" placeholder="TO DATE ">
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER</option>
								</select>
							</div>
							<input type="text" placeholder="JOB STATUS">
						</div>
					</div>
				</div>
			</div>
            
           <!--------------------------------first row--------------------------------------->  
        
        
            <div class="account-holder tab_hide" id="tab_driver_statement">
				<div class="account-heading">
					<div class="container-holder">
						<span>Driver Statement</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">REF NO/JOB REF</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">REF NO/JOB REF</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">REF NO/JOB REF</option>
								</select>
							</div>
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
								</select>
							</div>
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">PAY TYPE</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">PAY TYPE</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">PAY TYPE</option>
								</select>
							</div>
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
								</select>
							</div>
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<textarea placeholder="ACCOUNT NAME"></textarea>
							<input type="text" placeholder="ADDITION">
							<input type="text" placeholder="DEDUCTION">
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="%">
							<input type="text" placeholder="ACCOUNT NAME">
							<input type="text" placeholder="TO DATE ">
						</div>
					</div>
				</div>
			</div>
            
            <div class="account-holder tab_hide" id="tab_council_sheet">
				<div class="account-heading">
					<div class="container-holder">
						<span>COUNCIL SHEET</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
								</select>
							</div>
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER</option>
								</select>
							</div>
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<textarea placeholder="ACCOUNT NAME"></textarea>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="ACCOUNT NAME">
							<input type="text" placeholder="TO DATE ">
						</div>
					</div>
				</div>
			</div>
            
            <div class="account-holder tab_hide" id="tab_trun_over_company_earning">
				<div class="account-heading">
					<div class="container-holder">
						<span>turnover [ company earning ]</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME </option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME </option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME </option>
								</select>
							</div>
							<input type="text" placeholder="ACCOUNT NAME">
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="FROM DATE">
							<input type="text" placeholder="TO DATE">
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER </option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER </option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER </option>
								</select>
							</div>
							<div class="checkbox">
								<input id="check5" type="checkbox" name="check" value="check5">
								<label for="check5">VAT MODE</label>
							</div>
						</div>
					</div>
				</div>
			</div>
            
            <!-------------------------------------------------------------->
            <div class="account-holder <?php if(isset($driver_report_tab_active)) echo $driver_report_tab_active; else echo 'tab_hide';?>" id="tab_driver_report">
				<div class="account-heading">
					<div class="container-holder">
						<span>DRIVER REPORTS</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text" placeholder="FROM DATE " name="driver_report_from_date" id="driver_report_from_date" 
                            value="<?php echo  date('d-m-Y', strtotime('-7 days'));?>"  required > 
                            <button type="submit" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="TO DATE " name="driver_report_to_date" id="driver_report_to_date" 
                            value="<?php echo  date('d-m-Y');?>"  required > 
                            
                        </div>
                        
                        <div class="col-lg-4">
							<div class="select">
								<span class="arr"></span> 
                            <select  name="driver_report_driver_no" id="driver_report_driver_no" >
								<?php
                                $Query = mysql_query( "SELECT driver_no, name FROM `cab_fleet`  WHERE company_id =".$_SESSION['company_id']);
                                	while( $row = mysql_fetch_assoc( $Query ) ){?>
                                    <option value="<?php echo $row['driver_no'];?>" style="padding: 5px 10px; color: #b5b5b5;  text-transform: 
                                    	capitalize; background:#fff; font-size:11px;">
                                    	<?php echo  $row['name'];?>
                                    </option>
                                	<?php } ?>
                            </select>
                           </div> 
                        </div>
                    
                    </div>
				</div>
			</div>
            
            
            
            
            <div class="account-holder tab_hide" id="tab_letter">
				<div class="account-heading">
					<div class="container-holder">
						<span>LETTERS</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text"  placeholder="JOB REFRENCE NO:">
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT LETTER</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT LETTER</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT LETTER</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
            
         <!--------------------------------Second Row-------------------------------------->   
         <!--------------------------------Third Row--------------------------------------> 
            <div class="account-holder tab_hide" id="tab_job_sheet">
				<div class="account-heading">
					<div class="container-holder">
						<span>JOB SHEET</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text"  placeholder="FROM DATE">
							<input type="text" placeholder="TO DATE ">
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT DRIVER</option>
								</select>
							</div>
							<input type="text" placeholder="JOB STATUS">
						</div>
					</div>
				</div>
			</div>
            <div class="account-holder tab_hide" id="tab_account_total">
				<div class="account-heading">
					<div class="container-holder">
						<span>ACCOUNT TOTAL</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
								</select>
							</div>
							<input type="text" placeholder="ACCOUNT NAME">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">pay time</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">pay time</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">pay time</option>
								</select>
							</div>
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="FROM DATE ">
							<input type="text" placeholder="TO DATE ">
						</div>
					</div>
				</div>
			</div>
            
          <div class="account-holder <?php if(isset($turn_over_tab_active)) echo $turn_over_tab_active; else echo 'tab_hide';?>" id="tab_turn_over">
				<div class="account-heading">
					<div class="container-holder">
						<span>turnover</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							
                            <input type="text" placeholder="FROM DATE " name="turn_over_from_date" id="turn_over_from_date" 
                            value="<?php echo  date('d-m-Y', strtotime('-7 days'));?>"  required > 
                            <button type="submit" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							
                            <input type="text" placeholder="TO DATE " name="turn_over_to_date" id="turn_over_to_date" 
                            value="<?php echo  date('d-m-Y');?>"  required > 
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
                           <?php $s='style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;"';?>
                            <select id="booking_status" name="booking_status"  >
                                <option   value="6" <?php echo $s;?>>All</option>
                                <option  value="1" <?php echo $s;?>>Confirmed</option>
                                <option   value="7" <?php echo $s;?>>Cancelled</option>
                                <option   value="2" <?php echo $s;?>>Handback</option>
                                <option  value="3" <?php echo $s;?>>Completed</option>        
                            </select>
                            </div>
						</div>
					</div>
				</div>
			</div>
            
            
            <div class="account-holder 
			<?php if(isset($invoice_management_tab_active)) echo $invoice_management_tab_active; else echo 'tab_hide';?>" id="tab_invoice_management">
				<div class="account-heading">
					<div class="container-holder">
						<span>MANAGEMENT INVOICE</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
						<input type="text" placeholder="FROM DATE" id="invoice_management_from_date" name="invoice_management_from_date" 
                            value="<?php echo  date('d-m-Y', strtotime('-7 days'));?>">
							<button type="submit" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
						<input type="text" placeholder="TO DATE" id="invoice_management_to_date" name="invoice_management_to_date" value="<?php echo date('d-m-Y');?>">
                        </div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
                        <select name="invoice_management_status">
                            <option value="" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">All</option>
                            <option value="1" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Active</option>
                            <option value="0" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">InActive</option>
                        </select>
							</div>
						</div>
					</div>
				</div>
			</div> 
            
            <div class="account-holder tab_hide" id="tab_operator_log">
				<div class="account-heading">
					<div class="container-holder">
						<span>OPERATOR LOG</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text"  placeholder="FROM DATE">
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<input type="text"  placeholder="TO DATE">
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">USER TYPE</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">USER TYPE</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">USER TYPE</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
        <!---------------------------------Third Row---------------------------------->   
        
         <!---------------------------Fourth Row---------------------------------->
           
            <div class="account-holder tab_hide" id="tab_operator_rota">
				<div class="account-heading">
					<div class="container-holder">
						<span>OPERATOR ROTA</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text"  placeholder="FROM DATE">
							<input type="text"  placeholder="TO DATE">
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">USER TYPE</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">USER TYPE</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">USER TYPE</option>
								</select>
							</div>
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT NAME</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
            <!------------------------>
           	 
             <div class="account-holder <?php if(isset($driver_rota_tab_active)) echo $driver_rota_tab_active; else echo 'tab_hide';?>" id="tab_driver_rota">
				<div class="account-heading">
					<div class="container-holder">
						<span>DRVIER ROTA</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text" placeholder="FROM DATE" id="driver_rota_from_date" name="driver_rota_from_date" 
                            value="<?php echo  date('d-m-Y', strtotime('-7 days'));?>" required>
                            <button type="submit" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
						<input type="text" placeholder="TO DATE" id="driver_rota_to_date" name="driver_rota_to_date" value="<?php echo date('d-m-Y');?>" required>
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select id="driver_rota_driver_no" name="driver_rota_driver_no"  >
								<?php
									$query = mysql_query("SELECT driver_no,name FROM `cab_fleet` WHERE company_id= '".$_SESSION['company_id']."'");?>
                                   
                                    <option value="" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">
                                    	Choose Driver 
                                    </option> 
                                   <option value="-1" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">
                                   		All
                                     </option> 
								   	<?php 
		 							while( $row = mysql_fetch_assoc( $query ) ){?>
                                    <option value="<?php echo $row['driver_no'];?>" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">
                                   	  <?php echo  $row['driver_no'].'--'.$row['name'];?>
                                    </option>
                                    <?php } ?>
                                    
                                    
                                    
                                </select>
							</div>
						</div>
					</div>
				</div>
			</div>
            
            <!------------------------>
            
            
            <div class="account-holder tab_hide" id="tab_sms_report">
				<div class="account-heading">
					<div class="container-holder">
						<span>SMS SHOW SHEEt</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							
							<input type="text"  placeholder="FROM DATE">
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="TO DATE ">
						</div>
					</div>
				</div>
			</div>
            
            <div class="account-holder tab_hide" id="tab_recipt_report">
				<div class="account-heading">
					<div class="container-holder">
						<span>recept statement</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text"  placeholder="REF NO/ JPB REf">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAXIBASE NAME</option>
								</select>
							</div>
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">ACCOUNT NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">ACCOUNT NAME</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">ACCOUNT NAME</option>
								</select>
							</div>
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">PAY TYPE</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">PAY TYPE</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">PAY TYPE</option>
								</select>
							</div>
							<input type="text" placeholder="SERVICES CHARGE IN %">
							<div class="checkbox">
								<input id="check5" type="checkbox" name="check" value="check5">
								<label for="check5">VAT MODE</label>
							</div>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="FROM DATE">
							<input type="text" placeholder="TO DATE">
						</div>
					</div>
				</div>
			</div>
            
            <div class="account-holder tab_hide" id="tab_turn_over_driver">
				<div class="account-heading">
					<div class="container-holder">
						<span>turnover [ driver statement ]</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT NAME </option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT NAME </option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT NAME </option>
								</select>
							</div>
							<input type="text" placeholder="ACCOUNT NAME">
							<button type="button" class="show">Show Statement</button>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="FROM DATE">
							<input type="text" placeholder="TO DATE">
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<select>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT NAME </option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT NAME </option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">SELECT NAME </option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
            
            
            <div class="account-holder <?php if(isset($operator_jobs_record_tab_active)) echo $operator_jobs_record_tab_active; else echo 'tab_hide';?>" 
            id="tab_operator_jobs_record">
				<div class="account-heading">
					<div class="container-holder">
						<span>OPERATOR JOBS DETAIL</span>
					</div>
				</div>
				<div class="container-holder holder">
					<div class="row">
						<div class="col-lg-4">
							<input type="text" placeholder="FROM DATE" id="operator_jobs_record_from_date" name="operator_jobs_record_from_date" 
                            value="<?php echo  date('d-m-Y', strtotime('-7 days'));?>" required>
                             <input type="text" placeholder="TO DATE" id="operator_jobs_record_to_date" name="operator_jobs_record_to_date" 
                            value="<?php echo date('d-m-Y');?>" required>
                            
                            <button type="submit" class="show">Show Statement</button>
						</div>
                    </div>
				</div>
			</div>
            
         <!---------------------------Fourth Row------------------------------------->
         	<input type="hidden" name="check_invoice_for" id="check_invoice_for" value="<?php echo $checkInvoiceFor;?>">
         </form>   
            
		</div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="reports_scripts/js/bootstrap.min.js"></script>
    <script src="reports_scripts/js/jquery-1.10.2.js"></script>
    <script src="reports_scripts/js/jquery-ui.js"></script>
    <script src="reports_scripts/js/custom_script.js"></script>
    <input type="hidden" name="store_url" id="store_url" value="<?php echo $glob['storeURL']; ?>">
</body>
</html>





