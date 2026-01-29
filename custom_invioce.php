<?php 
include("includes/includes.inc.php");
include("dompdf_config.inc.php");
ob_start();
ob_get_contents();
ini_set("memory_limit", "200M");
if(isset($_REQUEST['custom_invoice_hidden']) && $_REQUEST['custom_invoice_hidden']== true){
	
      
	  
	  if($_FILES["custom_invoice_logo"]["name"]<>""){
			
		    $listImagePath='uploads/invoice_logo/';
		    $file_name = $_FILES["custom_invoice_logo"]["name"];
			if(!file_exists($listImagePath.$file_name)){
				move_uploaded_file($file_tmp = $_FILES["custom_invoice_logo"]["tmp_name"],$listImagePath.$file_name);
			}else{
				$ext = pathinfo($file_name,PATHINFO_EXTENSION);
				$filename = basename($file_name,$ext);
				$renameFileName  =   time().'.'.$ext; 
				move_uploaded_file($file_tmp = $_FILES["custom_invoice_logo"]["tmp_name"],$listImagePath.$renameFileName);
				$file_name =  $renameFileName;
			}
		}
	    $html  = getPDFhtml($_POST,$file_name);
		$dompdf = new DOMPDF();
		$dompdf->load_html($html); 
		$dompdf->render(); 
		$dompdf->output();
		$dompdf->stream("icabit_".time().".pdf");
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Custom Report</title>

    <!-- Bootstrap -->
    <link href="reports_scripts/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="reports_scripts/css/all.css">
    <link rel="stylesheet" href="reports_scripts/css/jquery-ui.css">
    <link href='https://fonts.googleapis.com/css?family=Lato:100,300,100italic,300italic,400italic,400,700,700italic,900' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	</head>
	<body>
      <form name="f1" action="" method="post" enctype="multipart/form-data"> 
		<div id="wrapper" class="wrapper_class">
			<div class="header">
				<span>PRINT REPORTS</span>
			</div>
			
            
             <div class="account-holder">
				<div class="account-heading">
					<div class="container-holder">
						<span>CUSTOM INVOICE</span>
					</div>
				</div>
             
				<div class="container-holder holder custom">
					<div class="row">
						<em>Please set your criteria for custom invoice</em>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
								<!--<select style="color:#9b9b9b;">
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAX</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAX</option>
									<option style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">TAX</option>
								</select>-->
                        <select style="color:#9b9b9b;" name="c_i_tax">
                            <option value="%" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">% Tax</option>
                            <option value="£" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Flat Tax</option>
                            <option value="off" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Off</option>
                        </select>
                           </div>
						</div>
                     
                     <div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
		<select style="color:#9b9b9b;" name="c_i_currency"> 
            <option value="¥" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Yuan (¥)</option>
            <option selected="" value="£" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Pound (£)</option>
            <option value="€" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Euro (€)</option>
            <option value="Rs" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Pakistani Rupee (Rs)</option>
            <option value="AED" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Durham (AED)</option>
            <option value="RM" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Malaysian Ranggit (RM)</option>
            <option value="$" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Dollar ($)</option>
        </select>   
                
                </div>
						</div>
						<div class="col-lg-4">
							<div class="select">
								<span class="arr"></span>
				<select style="color:#9b9b9b;" name="c_i_discount">
                    <option value="%" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">% Discount</option>
                    <option value="£" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Flat Discount</option>
                    <option value="off" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Off</option>
                </select>
                       </div>
						</div>
					</div>
				</div>
			</div>
			<div class="account-holder customer">
				<div class="container-holder">
					<div class="row">
						<div class="col-lg-4">
							<div class="upload">
								<em class="logo">upload your logo</em>
								<input type="file" name="custom_invoice_logo" id="file" class="inputfile" />
								<div class="label-holder"><label for="file" >Browse...</label></div>
								<span class="selected">No file Selected</span>
							</div>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="INVOICE" name="c_i_title" required value="INVOICE">
							<input type="text" placeholder="BILLING DATE" id="custom_invoice_biiling_date" name="c_i_biiling_date" value="<?php echo date("d-m-Y");?>" required maxlength="10">
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="INVOICE #" name="c_i_number" value="" required>
							<input type="text" placeholder="DUE DATE" id="custom_invoice_due_date" name="c_i_due_date" value="<?php echo date("d-m-Y");?>" required>
						</div>
					</div>
					<div class="row">
						<em>Taxi Office Details</em>
						<div class="col-lg-4">
							<input type="text" placeholder="BUSINESS NAME *" name="c_i_bussinus_name" value="" required>
							<input type="text" placeholder="PHONE#" name="c_i_bussinus_phone" required>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="ADDRESS LINE 1" name="c_i_bussinus_address_1" value="" required>
							<input type="email" placeholder="EMAIL" name="c_i_bussinus_email" value="" required>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="ADDRESS LINE 2" name="c_i_bussinus_address_2" value="" required>
							<textarea placeholder="ADDITIONAL INFO" name="c_i_bussinus_additional_info" required></textarea>
						</div>
					</div>
					<div class="row">
						<em>Customer details</em>
						<div class="col-lg-4">
							<input type="text" placeholder="BUSINESS NAME *" name="c_i_customer_bussinus_name" value="" required>
							<input type="text" placeholder="PHONE#"  name="c_i_customer_bussinus_phone" value="" required>
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="ADDRESS LINE 1" name="c_i_customer_bussinus_address_1" value="" required>
							<input type="email" placeholder="EMAIL" name="c_i_customer_bussinus_email">
						</div>
						<div class="col-lg-4">
							<input type="text" placeholder="ADDRESS LINE 2" name="custom_invoice_customer_bussinus_address_2">
							<textarea placeholder="ADDITIONAL INFO" name="c_i_customer_bussinus_additional_info" required></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="account-holder">
				<table class="account-table" id="row_wrap">
					<tbody id="rows_body">
                    <tr>
						<th class="col20">action</th>
						<th class="col21">bookings</th>
						<th class="col22">Quantity</th>
						<th class="col23">price</th>
						<th class="col24">tax</th>
						<th class="col25">discount</th>
						<th class="col26">total</th>
					</tr>
                    <tr>
						<td class="col20"><span id="add_more_btn">+</span></td>
						<td class="col21">
							<input type="text" placeholder="PHONE#" name="row_phone_no[]" id="row_phone_no" onkeypress="return isNumber(event)"  value="" maxlength="11" required>
						</td>
						<td class="col22">
							<input type="text"  value="" id="q_1" onKeyUp="cal_row_price(1)" name="row_quantity[]" onkeypress="return isNumber(event)" maxlength="3"  required>
						</td>
						<td class="col23">
							<div class="field-holder">
								<strong>&pound;</strong>
								<input type="text" class="text"  value="" id="p_1" onKeyUp="cal_row_price(1)" name="row_price[]" onkeypress="return isNumber(event)" maxlength="5" required>
							</div>
						</td>
						<td class="col24">
							<div class="field-holder">
								<strong>%</strong>
								<input type="text" placeholder="" class="text" id="t_1"  onKeyUp="cal_row_tax(1)" name="row_tax[]" onkeypress="return isNumber(event)" maxlength="3" required>
							</div>
						</td>
						<td class="col25">
							<div class="field-holder">
								<strong>%</strong>
								<input type="text" placeholder="" class="text" id="d_1" onKeyUp="cal_row_discount(1)" 
                                name="row_discount[]" onkeypress="return isNumber(event)" maxlength="3">
							</div>
						</td>
						<td class="col26">
							&pound;  <span id="row_total_1" class="row_total">0</span>
                            <input type="hidden"  id="row_total_hidden_1" name="row_total_hidden[]" value="">
						</td>
					</tr>
                    <tr>
						<td class="col20"></td>
						<td class="col21">
							<textarea placeholder="ADDITIONAL INFO" style="font-size:11px; margin-top:-34px;" name="row_additional_info[]"></textarea>
						</td>
						<td class="col22"></td>
						<td class="col23"></td>
						<td class="col24"></td>
						<td class="col25"></td>
						<td class="col26"></td>
					</tr>
                  </tbody>
            
            	</table>
			</div>
			<div class="account-holder">
				<div class="note-holder">
					<div class="col-lg-9">
						<label for="notes">extra notes</label>
						<textarea placeholder="Use this space to add some more text e.g. Terms & Conditions or Bank Details etc etc" name="c_i_extra_notes"></textarea>
                    </div>
					<div class="col-lg-3">
						<div class="sub-total">
							<div class="col-lg-3"><strong id="add_more_sub_total_list">+</strong></div>
							<div class="col-lg-6"><span>sub total</span></div>
							  <div class="col-lg-3 prise" style="padding:0;"><em>&pound; 
                            	 <span id="subtotalAmount" style="margin:2px 2px 0 0;">0</span>
                            		<input type="hidden" name="subtotalAmount_hidden" id="subtotalAmount_hidden" value="" />
                            	 </em>
                             </div>
                            <div id="sub_title_wrap"></div>
                            
                            <div class="col-lg-12">
								<div class="total">
									<span class="left">total</span>
									<span class="right">&pound; <span id="totalAmount" style="margin:0 2px 0 0; color:#fff;">0</span>
                                    <input type="hidden" name="totalAmount_hidden" id="totalAmount_hidden" value="" />
                                    
                                    </span>
								</div>
							</div>
                            
                        </div>
					</div>
				</div>
			</div>
			<div class="col-lg-12 account-holder original">
				<div class="col-lg-4 color">
					<label for="color">select a color</label>
					<div class="select">
						<span  class="arr">color picker</span>
						<input class="jscolor" value="ab2567" id="custom_invoice_color" name="c_i_color">
                       	  <!-- <select style="background:#ff9900" >
							<option style="padding: 20px ; background:#ff9900;"></option>
							<option style="padding: 20px ; background:#0099cc;"></option>
							<option style="padding: 20px ; background:#ff0;"></option>
						  </select>-->
                    </div>
					<em>Click on the color box and select a color of the invoice.</em>
				</div>
				<div class="col-lg-4 color">
					<label for="color">Label / Stamp</label>
					<input type="text" placeholder="ORIGINAL" name="c_i_stamp">
				</div>
				<div class="col-lg-4 color">
					<label for="color">Signature</label>
					<input type="text" placeholder="Name" name="c_i_person_name" value=""  >
					<input type="text" placeholder="Designation" name="c_i_person_designation">
                    <input type="hidden" name="custom_invoice_hidden" value="<?php echo true;?>" />
					<em>Leave blank to hide or disable the Signatures on invoice.</em>
				</div>
			</div>
			<div class="col-lg-12" style="text-align:center;">
				<button type="submit" class="download">GENERATE &amp; DOWNLOAD INVOICE</button>
                
			</div>
		</div>
     </form>
     
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="reports_scripts/js/bootstrap.min.js"></script>
    <script src="reports_scripts/js/jquery-1.10.2.js"></script>
    <script src="reports_scripts/js/jscolor.js"></script>
    <script src="reports_scripts/js/jquery-ui.js"></script>
    <script src="reports_scripts/js/custom_script.js"></script>
    <input type="hidden" name="store_url" id="store_url" value="<?php echo $glob['storeURL']; ?>">
  </body>
</html>
