<?php
include("includes/includes.inc.php");
if(!isset($_SESSION['company_id'])){
	header("location:".$glob['storeURL']);
	exit;
}
include("classes/booking.php");
include("classes/user.php");

	$all_booking = new booking($db);
	$user = new user($db);	
	switch($_GET['page_name']){
		case 'dashboard':
		$page_title='Main Dashborad';
		$class1='li_active';
		break;
		
		case 'booking':
		$page_title='Booking';
		$class2='li_active';
		break;
		
	}
	// messsage_div
	include("classes/setting_classes/setting.php");
	$otherdata = new setting($db);
	$company_id = $_SESSION['company_id']; 
	$freez_quot=$general->getFreezInfo($company_id);
	$freezbooked=$general->freezbookingTil($company_id);
	$email_notification=$general->getEmailNotify_Info($company_id);
	$sms_notification=$general->getSmsNotify_Info($company_id); 
     
	if(isset($_POST['book_id'])){
		 
		$query = 'SELECT * FROM `cab_order_sum` where cart_order_id ='.$db->mySQLSafe($_POST['book_id']);
		$daata = $db->select($query);
		$company_name= ucfirst($user->getCompanyName($daata[0]['canceled_by']));
		  
	//*******************************************************************//
		
   $message  ='<div style="margin:0;padding:0;background-color:#c0c0c0">
  <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#c0c0c0">
    <tbody>
      <tr>
        <td align="center" valign="top" bgcolor="#373737" style="background-color:#c0c0c0"><table border="0" width="600" cellpadding="0" cellspacing="0" bgcolor="#c0c0c0">
            <tbody>
              <tr>
                <td align="left"><table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#373737">
                    <tbody>
                      <tr>
                        <td align="left" valign="top" height="100" width="40"></td>
                        <td align="left" valign="top" height="100" width="107"><a rel="nofollow" href="http://icabit.com/" target="_blank"> <img src="http://icabit.com/cab_admin/images/logo_btn_2x.png" alt="" border="0" hspace="0" vspace="0" style="vertical-align:top;border:0" class="CToWUd"> </a></td>
                        <td align="left" valign="top" height="100" width="452"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                              <tr>
                                <td align="left" valign="top" height="32" width="100%"></td>
                              </tr>
                              <tr>
                                <td align="left" valign="top" height="32" width="100%" style="padding-left:24px; padding-right:15px; font-family:Arial,sans-serif;font-weight:bold;font-size:36px;line-height:33px;white-space:nowrap;letter-spacing:-3px;color:#ffffff"> 
                                </td>
                              </tr>
                            </tbody>
                          </table></td>
                      </tr>
                      <tr>
                        <td align="left" valign="top" height="70" colspan="3" bgcolor="#FFC400"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                              <tr>
                                <td align="left" valign="top" height="25" width="40"></td>
                                <td align="center" valign="top" height="25" width="107"></td>
                                <td align="left" valign="top" height="25" width="452"></td>
                              </tr>
                            </tbody>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                              <tr>
                                <td align="left" valign="top" height="45" width="100%" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:20px;line-height:26px;color:#000; padding-bottom:10px;">
								
								Please find below a summary of Canceled Job   </td>
                              </tr>
                            </tbody>
                          </table></td>
                      </tr>
                    </tbody>
                  </table>
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="padding-left:40px;padding-right:40px;padding-top:40px;padding-bottom:40px">
                    <tbody>
                      <tr>
                        <td align="left" valign="top" style="font-family:Arial,sans-serif;font-weight:normal;font-size:16px;line-height:22px;color:#2b2b2b"><h1 style="margin-bottom:20px;margin-top:0;font-family:Arial,sans-serif;font-weight:bold;font-size:18px;line-height:22px;color:#2b2b2b"> DEAR Administrator, </h1>
                      <br />  <strong>'.$company_name.' has canceled this booking.</strong><br /><br />   
                          <div style="margin-bottom:0">Canceled Booking Reference: '.$_POST['book_id'].' </div></td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
                <td align="left"><table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f5f5f5">
                    <tbody>
                      <tr>
                        <td align="left" valign="top" height="30" width="40"></td>
                        <td align="center" valign="top" height="30" width="107"></td>
                        <td align="left" valign="top" height="30" width="452"></td>
                      </tr>
                    </tbody>
                  </table>
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f5f5f5">
                    <tbody>
                      <tr>
                        <th align="left" valign="top" height="40" colspan="2" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6">CANCEL BOOKING DETAILS </th>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#ffffff" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> PRICE </th>
                        <td align="left" valign="middle" height="40" width="320" bgcolor="#ffffff" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"><span style="color:#1698d6"> &pound;'.$daata[0]['ordertotal'].' </span> </td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#f5f5f5" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> BOOKING REFERENCE </th>
                        <td align="left" valign="middle" height="40" width="320" bgcolor="#f5f5f5" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"> '.$_POST['book_id'].' </td>
                      </tr>
                      
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#f5f5f5" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> PICK UP </th>
                        <td align="left" valign="middle" height="40" width="280" bgcolor="#f5f5f5" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b">  '.$daata[0]['postfrom'].' </td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#ffffff" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> DROP OFF </th>
                        <td align="left" valign="middle" height="40" width="280" bgcolor="#ffffff" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"> '.$daata[0]['postto'].' </td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#f5f5f5" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> PICK TIME </th>
                        <td align="left" valign="middle" height="40" width="280" bgcolor="#f5f5f5" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"><span class="aBn" data-term="goog_946438025" tabindex="0"><span class="aQJ">'.$daata[0]['pick_date'].'</span></span> @ <span class="aBn" data-term="goog_946438026" tabindex="0"><span class="aQJ">'.$daata[0]['pick_time'].'</span></span></td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#ffffff" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> CUSTOMER NAME </th>
                        <td align="left" valign="middle" height="40" width="320" bgcolor="#ffffff" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b">  '.$user->getPessengerName($daata[0]['passenger_id']).'</td>
                      </tr>';
                    if($user->getUserPhone($daata[0]['passenger_id']) !=''){ 
                     
                      $message .='<tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#f5f5f5" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> PASSENGER TEL </th>
                        <td align="left" valign="middle" height="40" width="320" bgcolor="#f5f5f5" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"><a rel="nofollow">'.$user->getUserPhone($daata[0]['passenger_id']).'</a></td>
                      </tr>';
                      }
                      $message .='<tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#ffffff" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> PASSENGERS </th>
                        <td align="left" valign="middle" height="40" width="280" bgcolor="#ffffff" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"> '.$daata[0]['how_many'].' </td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#f5f5f5" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> BAGS </th>
                        <td align="left" valign="middle" height="40" width="280" bgcolor="#f5f5f5" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b">'.$daata[0]['luggage'].' </td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
                <td align="left"><table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="padding-top:30px;padding-left:40px">
                    <tbody>
                      <tr>
                        <td align="center" valign="middle" style="font-family:Arial,sans-serif;font-weight:bold;font-size:18px;line-height:22px;color:#2b2b2b"> &nbsp; </td>
                      </tr>
                      <tr>
                        <td align="left" valign="middle" style="padding-top:8px;padding-bottom:25px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:18px;color:#2b2b2b">&nbsp; </td>
                      </tr>
                    </tbody>
                  </table>
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#2b2b2b">
                    <tbody>
                      <tr>
                        <td align="left" valign="top" bgcolor="#2b2b2b" style="padding-left:40px;padding-top:8px;padding-bottom:8px;font-family:Arial,sans-serif;font-weight:bold;font-size:14px;line-height:68px;color:#ffffff"> CONNECT WITH US </td>
                        <td align="right" valign="middle" bgcolor="#2b2b2b" style=" padding-right:20px; padding-top:8px;padding-bottom:8px;font-family:Arial,sans-serif;font-weight:bold;font-size:14px;line-height:68px;color:#ffffff"><a rel="nofollow" href="'.$config['Facebook'].'" style="border:0" target="_blank"> <img src="https://ci6.googleusercontent.com/proxy/9WnIdkyVisGfZrbWbJpYkgpyIMYsL5pwD1IBrNhkUM65r7zShk7nHrJJSg2ySBba5dCSbkX_CLUtYbI33Jdo3g=s0-d-e1-ft#http://api.anycabs.co.uk/assets/img/fb.jpg" width="36" height="36" alt="" border="0" hspace="0" vspace="0" style="vertical-align:middle;border:0 none" class="CToWUd"> </a> <a rel="nofollow" href="'.$config['Facebook'].'" style="border:0" target="_blank"> <img src="https://ci5.googleusercontent.com/proxy/E1okqsPaSa3v2iR4nTEKt6-gQLjdwTDv8wJvCaNvOZWjWs2zQScXKq8KVLXAUxZ2t6QVrxDsvUHSEoEu-t55Quna=s0-d-e1-ft#http://api.anycabs.co.uk/assets/img/twit.jpg" width="36" height="36" alt="" border="0" hspace="0" vspace="0" style="vertical-align:middle;border:0 none" class="CToWUd"> </a>  <a rel="nofollow" href="'.$config['pintrest'].'" style="border:0" target="_blank"> <img src="https://ci6.googleusercontent.com/proxy/nUJ7MJScSxGEfGYMvYkqQ0nOOgOsPBN6Egbbkng6GxeujeM8ipJi9KC5MlDDgojBrYIEK0ma8aOzpJjLpKzr0Rk5=s0-d-e1-ft#http://api.anycabs.co.uk/assets/img/pint.jpg" width="36" height="36" alt="" border="0" hspace="0" vspace="0" style="vertical-align:middle;border:0 none" class="CToWUd"> </a></td>
                       <!-- <td align="left" valign="top" bgcolor="#2b2b2b" style="padding-top:8px;padding-bottom:8px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:68px;color:#ffffff"><a rel="nofollow" href="http://icabit.com/" style="color:#ffffff;text-decoration:none" target="_blank">www.icabit.com</a></td>-->
                      </tr>
                    </tbody>
                  </table>
                  <!--<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="padding-top:15px;padding-bottom:15px">
                    <tbody>
                      <tr>
                        <td align="center" valign="middle" style="font-family:Arial,sans-serif;font-weight:normal;font-size:10px;line-height:18px;color:#2b2b2b"> Icabit Ltd. '.$config['storeAddress'].'</td>
                      </tr>
                    </tbody>
                  </table>--></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
    </tbody>
  </table>
</div>';

			
			//***************************************************************
			
			
			
			    $headers2  = 'MIME-Version: 1.0' . "\r\n";
				$headers2 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers2 .= 'From:'.$config['masterEmail'] . "\n"; 
				$mail = @mail($config['masterEmail'],"Job has been canceled",$message,$headers2);	
				
				
				//*******************
				 
				 if($daata[0]['company_id'] != $daata[0]['canceled_by']){
				 
				 $email=$user->getCompanyEmail($daata[0]['company_id']);
			     $company_name= ucfirst($user->getCompanyName($daata[0]['company_id']));
			
			
				$message ='<div style="margin:0;padding:0;background-color:#c0c0c0">
  <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#c0c0c0">
    <tbody>
      <tr>
        <td align="center" valign="top" bgcolor="#373737" style="background-color:#c0c0c0"><table border="0" width="600" cellpadding="0" cellspacing="0" bgcolor="#c0c0c0">
            <tbody>
              <tr>
                <td align="left"><table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#373737">
                    <tbody>
                      <tr>
                        <td align="left" valign="top" height="100" width="40"></td>
                        <td align="left" valign="top" height="100" width="107"><a rel="nofollow" href="http://icabit.com/" target="_blank"> <img src="http://icabit.com/cab_admin/images/logo_btn_2x.png" alt="" border="0" hspace="0" vspace="0" style="vertical-align:top;border:0" class="CToWUd"> </a></td>
                        <td align="left" valign="top" height="100" width="452"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                              <tr>
                                <td align="left" valign="top" height="32" width="100%"></td>
                              </tr>
                              <tr>
                                <td align="left" valign="top" height="32" width="100%" style="padding-left:24px; padding-right:15px; font-family:Arial,sans-serif;font-weight:bold;font-size:36px;line-height:33px;white-space:nowrap;letter-spacing:-3px;color:#ffffff"> 
                                </td>
                              </tr>
                            </tbody>
                          </table></td>
                      </tr>
                      <tr>
                        <td align="left" valign="top" height="70" colspan="3" bgcolor="#FFC400"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                              <tr>
                                <td align="left" valign="top" height="25" width="40"></td>
                                <td align="center" valign="top" height="25" width="107"></td>
                                <td align="left" valign="top" height="25" width="452"></td>
                              </tr>
                            </tbody>
                          </table>
                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                              <tr>
                                <td align="left" valign="top" height="45" width="100%" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:20px;line-height:26px;color:#000; padding-bottom:10px;">
								
								Please find below a summary of assigned job   </td>
                              </tr>
                            </tbody>
                          </table></td>
                      </tr>
                    </tbody>
                  </table>
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="padding-left:40px;padding-right:40px;padding-top:40px;padding-bottom:40px">
                    <tbody>
                      <tr>
                        <td align="left" valign="top" style="font-family:Arial,sans-serif;font-weight:normal;font-size:16px;line-height:22px;color:#2b2b2b"><h1 style="margin-bottom:20px;margin-top:0;font-family:Arial,sans-serif;font-weight:bold;font-size:18px;line-height:22px;color:#2b2b2b"> Dear '.$company_name.' Administrator, has asssigned this booking </h1>
                        
                          <div style="margin-bottom:0">Assigned Booking Reference: '.$data['booking_id'].' </div></td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
                <td align="left"><table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f5f5f5">
                    <tbody>
                      <tr>
                        <td align="left" valign="top" height="30" width="40"></td>
                        <td align="center" valign="top" height="30" width="107"></td>
                        <td align="left" valign="top" height="30" width="452"></td>
                      </tr>
                    </tbody>
                  </table>
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f5f5f5">
                    <tbody>
                      <tr>
                        <th align="left" valign="top" height="40" colspan="2" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6">ASSIGNED BOOKING DETAILS </th>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#ffffff" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> PRICE </th>
                        <td align="left" valign="middle" height="40" width="320" bgcolor="#ffffff" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"><span style="color:#1698d6"> &pound;'.$daata[0]['new_price'].' </span> </td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#f5f5f5" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> BOOKING REFERENCE </th>
                        <td align="left" valign="middle" height="40" width="320" bgcolor="#f5f5f5" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"> '.$data['booking_id'].' </td>
                      </tr>
                      
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#f5f5f5" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> PICK UP </th>
                        <td align="left" valign="middle" height="40" width="280" bgcolor="#f5f5f5" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b">  '.$daata[0]['postfrom'].' </td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#ffffff" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> DROP OFF </th>
                        <td align="left" valign="middle" height="40" width="280" bgcolor="#ffffff" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"> '.$daata[0]['postto'].' </td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#f5f5f5" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> PICK TIME </th>
                        <td align="left" valign="middle" height="40" width="280" bgcolor="#f5f5f5" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"><span class="aBn" data-term="goog_946438025" tabindex="0"><span class="aQJ">'.$daata[0]['pick_date'].'</span></span> @ <span class="aBn" data-term="goog_946438026" tabindex="0"><span class="aQJ">'.$daata[0]['pick_time'].'</span></span></td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#ffffff" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> CUSTOMER NAME </th>
                        <td align="left" valign="middle" height="40" width="320" bgcolor="#ffffff" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b">  '.$user->getPessengerName($daata[0]['passenger_id']).'</td>
                      </tr>';
                    if($user->getUserPhone($daata[0]['passenger_id']) !=''){ 
                     
                      $message .='<tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#f5f5f5" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> PASSENGER TEL </th>
                        <td align="left" valign="middle" height="40" width="320" bgcolor="#f5f5f5" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"><a rel="nofollow">'.$user->getUserPhone($daata[0]['passenger_id']).'</a></td>
                      </tr>';
                      }
                      $message .='<tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#ffffff" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> PASSENGERS </th>
                        <td align="left" valign="middle" height="40" width="280" bgcolor="#ffffff" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b"> '.$daata[0]['how_many'].' </td>
                      </tr>
                      <tr>
                        <th align="left" valign="middle" height="40" width="200" bgcolor="#f5f5f5" style="padding-left:40px;font-family:Arial,sans-serif;font-weight:bold;font-size:16px;line-height:22px;color:#1698d6"> BAGS </th>
                        <td align="left" valign="middle" height="40" width="280" bgcolor="#f5f5f5" style="padding-left:40px;padding-top:5px;padding-bottom:5px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:16px;color:#2b2b2b">'.$daata[0]['luggage'].' </td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
                <td align="left"><table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="padding-top:30px;padding-left:40px">
                    <tbody>
                      <tr>
                        <td align="center" valign="middle" style="font-family:Arial,sans-serif;font-weight:bold;font-size:18px;line-height:22px;color:#2b2b2b"> &nbsp; </td>
                      </tr>
                      <tr>
                        <td align="left" valign="middle" style="padding-top:8px;padding-bottom:25px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:18px;color:#2b2b2b">&nbsp; </td>
                      </tr>
                    </tbody>
                  </table>
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#2b2b2b">
                    <tbody>
                      <tr>
                        <td align="left" valign="top" bgcolor="#2b2b2b" style="padding-left:40px;padding-top:8px;padding-bottom:8px;font-family:Arial,sans-serif;font-weight:bold;font-size:14px;line-height:68px;color:#ffffff"> CONNECT WITH US </td>
                        <td align="right" valign="middle" bgcolor="#2b2b2b" style=" padding-right:20px; padding-top:8px;padding-bottom:8px;font-family:Arial,sans-serif;font-weight:bold;font-size:14px;line-height:68px;color:#ffffff"><a rel="nofollow" href="'.$config['Facebook'].'" style="border:0" target="_blank"> <img src="https://ci6.googleusercontent.com/proxy/9WnIdkyVisGfZrbWbJpYkgpyIMYsL5pwD1IBrNhkUM65r7zShk7nHrJJSg2ySBba5dCSbkX_CLUtYbI33Jdo3g=s0-d-e1-ft#http://api.anycabs.co.uk/assets/img/fb.jpg" width="36" height="36" alt="" border="0" hspace="0" vspace="0" style="vertical-align:middle;border:0 none" class="CToWUd"> </a> <a rel="nofollow" href="'.$config['Facebook'].'" style="border:0" target="_blank"> <img src="https://ci5.googleusercontent.com/proxy/E1okqsPaSa3v2iR4nTEKt6-gQLjdwTDv8wJvCaNvOZWjWs2zQScXKq8KVLXAUxZ2t6QVrxDsvUHSEoEu-t55Quna=s0-d-e1-ft#http://api.anycabs.co.uk/assets/img/twit.jpg" width="36" height="36" alt="" border="0" hspace="0" vspace="0" style="vertical-align:middle;border:0 none" class="CToWUd"> </a>  <a rel="nofollow" href="'.$config['pintrest'].'" style="border:0" target="_blank"> <img src="https://ci6.googleusercontent.com/proxy/nUJ7MJScSxGEfGYMvYkqQ0nOOgOsPBN6Egbbkng6GxeujeM8ipJi9KC5MlDDgojBrYIEK0ma8aOzpJjLpKzr0Rk5=s0-d-e1-ft#http://api.anycabs.co.uk/assets/img/pint.jpg" width="36" height="36" alt="" border="0" hspace="0" vspace="0" style="vertical-align:middle;border:0 none" class="CToWUd"> </a></td>
                       <!-- <td align="left" valign="top" bgcolor="#2b2b2b" style="padding-top:8px;padding-bottom:8px;font-family:Arial,sans-serif;font-weight:normal;font-size:14px;line-height:68px;color:#ffffff"><a rel="nofollow" href="http://icabit.com/" style="color:#ffffff;text-decoration:none" target="_blank">www.icabit.com</a></td>-->
                      </tr>
                    </tbody>
                  </table>
                  <!--<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="padding-top:15px;padding-bottom:15px">
                    <tbody>
                      <tr>
                        <td align="center" valign="middle" style="font-family:Arial,sans-serif;font-weight:normal;font-size:10px;line-height:18px;color:#2b2b2b"> Icabit Ltd. '.$config['storeAddress'].'</td>
                      </tr>
                    </tbody>
                  </table>--></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
    </tbody>
  </table>
</div>';
			
			
			
			    $headers2  = 'MIME-Version: 1.0' . "\r\n";
				$headers2 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers2 .= 'From:'.$config['masterEmail'] . "\n"; 
				$mail = @mail($email,"New Job For You",$message,$headers2);	
				
				 }
				
		            //*******************
		         $page=filter_input(INPUT_POST, 'page');	
				 $page2='';
				 if($page){
					 
					 $page2='/'.$page;
					 }
				 
				 header('Location:'.$glob['rootRel'].$_GET['parm'].'/'.$_GET['page_name'].$page2 );
		 
		 }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Modern Business - Start Bootstrap Template</title>
	<link href="<?php echo $glob['storeURL']; ?>css_new/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $glob['storeURL']; ?>css_new/modern-business.css" rel="stylesheet">
	<link href="<?php echo $glob['storeURL']; ?>css_new/style.css" rel="stylesheet">
    <link href="<?php echo $glob['storeURL']; ?>css_new/bootstrap-datepicker.css" rel="stylesheet">
	<link href="<?php echo $glob['storeURL']; ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
 <link rel="stylesheet" href="<?php echo $glob['storeURL']; ?>css_new/wickedpicker.css">
     <link rel="stylesheet" type="text/css" href="<?php echo $glob['storeURL']; ?>css/datepickr.min.css">
     
     
     
     
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>css_new
@import url('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');

.date-form { margin: 10px; }
label.control-label span { cursor: pointer; }


	.searchbox_div{
		background: #eaeaea none repeat scroll 0 0;
		border: 1px solid #ccc;
		height: 200px;
		overflow: auto;
		position: absolute;
		width: 447px;
		z-index: 10000;
	}
	.searchbox_div ul {
		padding:0px;
	}
	.searchbox_div ul li{
		list-style: outside none none;
    	padding: 0 0 2px 10px;
		
	}
	
	.searchbox_div ul li:hover{
		 background:#9CF; 
		
	}
	.loading{
		position: absolute;
		right: 10px;
		top: 10px;
		z-index: 10000;	
	}
  
  
  /*****************/
   .time-input-list-quick{
    background: white none repeat scroll 0 0;
    border: 1px solid #aaa;
    border-radius: 2px;
    box-shadow: 3px 3px 15px #bbb;
    color: #444;
    font-weight: bold;
    height: 160px;
    left: 934px;
    opacity: 1;
    padding: 9px 10px;
    position: absolute;
    top: 322px;
    width: 316px;
    z-index: 10000;
  
  }
  
    .time-input-list{
	background: white none repeat scroll 0 0;
	border: 1px solid #aaa;
	border-radius: 2px;
	box-shadow: 3px 3px 15px #bbb;
	color: #444;
	font-weight: bold;
	height: 160px;
	left: 189px;
	opacity: 1;
	padding: 9px 10px;
	position: absolute;
	top: 513px;
	width: 316px;
	z-index: 10000;
	}
	 .time-input-list img,.time-input-list-quick img{
		cursor: pointer;
		float: right;
		height: 7px;
		margin: 6px 0 0;
	 }
	.time-input-list  li,.time-input-list-quick li{
		background: #f3f3f3 none repeat scroll 0 0;
		border: 1px solid #cccccc;
		color: #1c94c4;
		cursor: pointer;
		float: left;
		font-size: 11px;
		font-weight: normal;
		list-style: outside none none;
		padding: 0;
		text-align: center;
		width: 42px;
	}
	
	.time-input-list  li:hover ,.time-input-list-quick li:hover{
	   background:#FFF0A5;
	   border:1px solid #FED22F;
	   color:#F7B64B;	
	
	}
  .wickedpicker{z-index: 1000 !important;}
  /*****************/
  
    
  
  


</style>
    
 <link rel="stylesheet" href="/resources/demos/style.css">
</head>
<body id="elm_body">
 <div id="content_overley" style="background: #000 none repeat scroll 0 0;height: 100%;position:fixed;opacity: 0.4;width: 100%; display: none; z-index:99;">
    	
   </div>
   <div  style="
   background: #fff;
    border: 1px solid #fff;
    left: 40%;
    padding: 20px;
    position: fixed; display: none;
    top: 35%;
    z-index: 99999;" id="content_display">
    	<a href="javascript:void(0);" class="" style="color:red;" title="close popup" id="close_popup">×</a>
    	<div id="html_data"></div>
   </div>

   
   <div  style="
   background: #fff none repeat scroll 0 0;
    border: 1px solid #fff;
    display: none;
    left: 7%;
    padding: 20px;
    position: fixed;
    top: 25%;
    width: 87%;
    z-index: 99999;" id="content_display_2">
    	<a href="javascript:void(0);" class="" style=" background: #333333 none repeat scroll 0 0;
    color: red;
    float: right;
    margin: 0 0 9px;
    padding: 0 7px;" 
    title="close popup" id="close_popup_2">×</a>
    	<div id="html_data_2"></div>
   </div>
   <?php include('includes/header_file.php');?>
   <?php
        $page_name = $_GET['page_name'].'.php';
            if($_GET['parm']=='dashboard2'){
            include("dashboard2/$page_name");
        }else if($_GET['parm']=='settings'){
            include("settings/$page_name");
        }
        ?>
    <?php include "includes/footer_file.php";?>
	
	<script src="<?php echo $glob['storeURL']; ?>js/jquery.js"></script>
	<script src="<?php echo $glob['storeURL']; ?>js/bootstrap.min.js"></script>
    <link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet"> 
	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php //echo $glob['storeURL']; ?>js/custom_script.js"></script>
     <script type="text/javascript" src="<?php //echo $glob['storeURL']; ?>js/global_script.js"></script>
      <script type="text/javascript" src="<?php echo $glob['storeURL']; ?>js/wickedpicker.js"></script>
      

	<script>
		$(function() {
			
			$('#timepicker_start').wickedpicker({twentyFour: true});
			$( "#filter_from_id" ).datepicker({ dateFormat: 'dd-mm-yy' }).val();
			$( "#filter_to_id" ).datepicker({ dateFormat: 'dd-mm-yy' }).val();
			$( "#pickup_date" ).datepicker({ dateFormat: 'dd-mm-yy' }).val(); // pickup date
			
			var month = ["1","2","3","4","5","6","7","8","9","10","11","12"];
			var date = new Date();
			var day = date.getDate();
			var year = date.getFullYear(); // get year
			var month = month[date.getMonth()]; // get month
			
			if(day<10) {
				day='0'+day
			} 
			if(month<10) {
				month='0'+month
			} 
			var today = day+'-'+month+'-'+year 
			// hello
			//$("#pickup_date").val(today);
			//$("#date_picker").val(today);
			
			var d = new Date(); 
			var hour = d.getHours(); 
			var minu = d.getMinutes();
			var sec = d.getSeconds(); 
			
			currentTime = new Date();
		    currentTime.setHours(currentTime.getHours());
		    currentTime.setMinutes(currentTime.getMinutes() + 15);
		    var hours = currentTime.getHours();
			var minutes = currentTime.getMinutes();
		    if(hours<10) {
				hours='0'+hours
			} 
			if(minutes<10) {
				minutes='0'+minutes
			} 
			// check_for_time_not_display_onload
			var timeWith15min = hours+ ':' +minutes; 
			// hello
			
			 // when booking edit
			 <?php if( !isset($booking_row['pick_time']) or $booking_row['pick_time']==""){?>
			 	$("#timepicker_start").val(timeWith15min);
				$("#time_picker").val(timeWith15min);
			<?php }?>
			
  		 });
         function deleteDatadash(id){
			
			var txt;
			var r = confirm("You want to delete this record!");
			if (r == true) {
				jQuery.post("<?php echo $glob['storeURL']; ?>controller/user_controller.php", {
				action: 'hideVisibility',
				id:id },
				function (data) {
					$("#data"+id).slideUp();
				});
			} else {
			
			}
		}
		
		 function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			
			alert("Enter only Number");
			$('#inputContactNo').focus();
			return false;
		}
		return true;
	}
	
	
	function onlyAlphabets(evt) {
        var charCode;
        if (window.event)
            charCode = window.event.keyCode;  //for IE
        else
            charCode = evt.which;  //for firefox
        if (charCode == 32) //for &lt;space&gt; symbol
            return true;
        if (charCode > 31 && charCode < 65) //for characters before 'A' in ASCII Table
            return false;
        if (charCode > 90 && charCode < 97) //for characters between 'Z' and 'a' in ASCII Table
            return false;
        if (charCode > 122) //for characters beyond 'z' in ASCII Table
            return false;
        return true;
    }
		
		
		
		
		
		
		// filterations scripts 
	   function submitForm(formId){
			if(document.getElementById('book_search_field').value==''){
				alert('Enter Booking No');
				document.getElementById('book_search_field').focus();
				return false;
			}
			// reset all above
			refreshAll(0);
			document.getElementById(formId).submit();
			return true;
		}
		
		function filterForm(status){
			document.getElementById('filterForm_hidden').value = status;
			document.getElementById('filterid').submit();
			return true;
		}
		
		function postFilterData(val){
			document.getElementById('main_filter_form').submit();
			return true;
		}
		
		// delete all 
		function deleteAll(){
			
		  var confrim  = confirm('do you realy want to delete.?');
			if(confrim){
				var get_count_allcheckbox = document.getElementById('get_count_allcheckbox').value;
				var result;
					for(i=1; i<=get_count_allcheckbox;i++){
						
						if(document.getElementById('check_delete_'+i).checked==true){
							document.getElementById('form_delete_check').submit();
							return  true;
						}else{
							result =  false; 
						}
					}
					if(result==false){
					  alert('check box should be selected atleast one.');	
					  return false;
					}
					
			 }else{
			  return false;	
			}
		}
		
		
		// for refresh All
		function refreshAll(val){
		  	// get all data to be refresh list
			if(val==1){
				document.getElementById("book_search_field").value='';	
			}
			var ele_filter_paytype = document.getElementById("filter_paytype").options;
			var ele_filter_passenger = document.getElementById("filter_passenger").options;
			var ele_filter_driver = document.getElementById("filter_driver").options;
			var ele_filter_bookingid = document.getElementById("filter_bookingid").options;
			document.getElementById("filter_from_id").value='';
			document.getElementById("filter_to_id").value='';
			
		    for(var i = 0; i < ele_filter_paytype.length; i++){
				ele_filter_paytype[i].selected = false;
			}
			
			for(var i = 0; i < ele_filter_passenger.length; i++){
				ele_filter_passenger[i].selected = false;
			}
			
			for(var i = 0; i < ele_filter_driver.length; i++){
				ele_filter_driver[i].selected = false;
			}
			for(var i = 0; i < ele_filter_bookingid.length; i++){
				ele_filter_bookingid[i].selected = false;
			}
		  //alert('Reset all fields');
		  return true;
		}
		
		function hideMainLoader(){
			 
			if(document.getElementById('is_quick').value!=""){
				document.getElementById('popup_loader').style.display='none'; // for quick case
			}else{
				document.getElementById('overley').style.display="none";	   // for reguler case
			}
		}
		
		function showMainLoader(){
			 
			if(document.getElementById('is_quick').value!=""){
				document.getElementById('popup_loader').style.display='block';
			}else{
				document.getElementById('overley').style.display='block';	
			}
		}
		
	 //-----------------------------------------------------------------------------------------------------------//
		
	  function toAutoComplete(){
		 
		var to_id =  document.getElementById('to_id').value;
		if(document.getElementById('to_id').value.length > 3){
			 document.getElementById('loading_image_to').style.display="block";
			 disableField('to_id',true);
		  	 var ajaxRequest;  // The variable that makes Ajax possible!
						try{
							ajaxRequest = new XMLHttpRequest();
						}catch (e){
						 try{
							ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
						   }catch (e) {
							 try{
								 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
								}catch (e){
									alert("Your browser broke!");
							 return false;
							}
						}
					 }
					ajaxRequest.onreadystatechange = function(){
					if(ajaxRequest.readyState == 4){

							responseText = ajaxRequest.responseText;
							document.getElementById('loading_image_to').style.display="none";
							if(responseText !=''){
								disableField('to_id',false);
								document.getElementById("address_to").style.height = "200px";
								document.getElementById('address_to').style.display='block';
								document.getElementById('address_to').innerHTML = responseText;
							}else{
								disableField('to_id',false);
								document.getElementById("address_to").style.height = "30px";
								document.getElementById('address_to').style.display='block';
								document.getElementById('address_to').innerHTML = 'No location found.!';	
							}
					}
				}  

						var queryString = "?val=" + to_id;
						queryString += "&action=" + 'to';
						ajaxRequest.open("GET", "<?php echo $glob['storeURL']; ?>get_locations.php" + queryString, true);
						ajaxRequest.send(null); 
				}
       }
	   
	   
	  
	    function disableField(fieldId,requiredStatus){
		   var fieldId;
		   var requiredStatus;
		   if(fieldId!=''){
		     return document.getElementById(fieldId).disabled = requiredStatus;
	       }
		}
	   //------------------------------------------------------------------------------------------------------------//
		
		
		
		function getMeetGreet(id){
		 var id;
		 //document.getElementById('overley').style.display="block";
		  var ajaxRequest;  // The variable that makes Ajax possible!
						try{
							ajaxRequest = new XMLHttpRequest();
						}catch (e){
						 try{
							ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
						   }catch (e) {
							 try{
								 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
								}catch (e){
									alert("Your browser broke!");
							 return false;
							}
						}
					 }
					ajaxRequest.onreadystatechange = function(){
					if(ajaxRequest.readyState == 4){
						responseText = ajaxRequest.responseText;
						document.getElementById('content_overley').style.display="block";
						document.getElementById('content_display').style.display="block";
						document.getElementById('html_data').innerHTML=responseText;
					}
				}  

						var queryString = "?cart_order_id=" + id;
						queryString += "&action=" + 'getMeetGreet';
						ajaxRequest.open("GET", "<?php echo $glob['storeURL']; ?>get_locations.php" + queryString, true);
						ajaxRequest.send(null); 
				
       }
	   
	   
	   function checkDriverAvailability(driver_no,pickupDate,pickupTime){
			jQuery.post("<?php echo $glob['storeURL']; ?>controller/user_controller.php", {
            action: 'check_driver_availablity',driver_no:driver_no,pickupDate:pickupDate ,pickupTime:pickupTime},
            function (data) {
				document.getElementById('overley').style.display="none";
				document.getElementById(driver_no).checked=true;
				if(data == 1){
					$("#assign_driver_error").slideDown();
					$("#assign_driver_error").html('This Driver already on job.');
					document.getElementById(driver_no).checked=false;
				}else{
					 /*$("#assign_driver_error").css('backgroundColor','#336600');
					 $("#assign_driver_error").slideDown();
					 $("#assign_driver_error").html('Driver is avilable for job.');*/
					
				    //alert('Driver already on job');
				}
            });
		 }
	   
	   function calculatePrice(){
		   
		   var fromVal,toVal,fieldIdFrom,fieldIdTo,error;
		   
			if(document.getElementById('from_id_onload').style.display=='none'){
				fromVal =  document.getElementById('from_id').value; 
				fieldIdFrom = 'from_id'; 
			}else{
				fromVal =  document.getElementById('from_id_onload').value; 
				fieldIdFrom = 'from_id_onload'; 
			}
			if(document.getElementById('to_id_onload').style.display=='none'){
				toVal =  document.getElementById('to_id').value;
				fieldIdTo = 'to_id';  
			}else{
				toVal =  document.getElementById('to_id_onload').value;
				fieldIdTo = 'to_id_onload'; 
			}
			
			if(fromVal==''){
				alert('Origen should have proper address.');
				document.getElementById(fieldIdFrom).focus();
				hideMainLoader();
				return false;  
			}
			
			if(toVal==''){
				alert('Destination should have proper address.');
				document.getElementById(fieldIdTo).focus();
				hideMainLoader();
				return false;    
			}
			
				var  pick_time,pick_date,total_duration,total_distance,how_many,luggage,from_id,to_id,from_postcode,to_postcode; 
				total_duration =  document.getElementById('duration_field').value;
				total_distance =  document.getElementById('total_miles').value;
				pick_date =  document.getElementById('pickup_date').value;
				pick_time =  document.getElementById('timepicker_start').value;
				how_many =  document.getElementById('how_many').value;
				luggage =  document.getElementById('luggage').value;
				from_postcode =  document.getElementById('from_postcode').value;
				to_postcode =  document.getElementById('to_postcode').value;
				vehicle_type =  document.getElementById('vehicle_select').value;
				vehicle_type =  document.getElementById('vehicle_select').value;
				
				
				if(pickup_date==''){
					alert('Pick up date cannot empty.');
					document.getElementById('pickup_date').focus();
					hideMainLoader();
					return false;    
				}
				
				if(how_many==''){
					alert('Choose No of Persons.');
					document.getElementById('how_many').focus();
					hideMainLoader();
					return false;    
				}
				
				if(vehicle_type==''){
					alert('Choose Desired Vehicle Type');
					document.getElementById('vehicle_select').focus();
					hideMainLoader();
					return false;    
				}
				
				from_id = fromVal;
				to_id = toVal;
				
				jQuery.post("<?php echo $glob['storeURL']; ?>ajax_functions.php", {
          		action: 'calculate_price',
				total_duration:total_duration,
				total_distance:total_distance,
				pick_date:pick_date,
				pick_time:pick_time,
				how_many:how_many,
				luggage:luggage,
				from_id:from_id,
				to_id:to_id,
				from_postcode:from_postcode,
				to_postcode:to_postcode,
				vehicle_type:vehicle_type,
			 },
             
			 function (data) {
				console.log(data);
				hideMainLoader();
				if(data!=''){
				  $("#booking_price").focus();
				  $("#booking_price").val(data);
				}
			});
		 }
	   
	   // 
	   
	function getPreviousjourneyByPhoneNo(phoneNo){
	   var  phoneNo; 
	      		showMainLoader();  // show loader
				jQuery.post("<?php echo $glob['storeURL']; ?>ajax_functions.php", {
          		action: 'getPreviousjourneyByPhoneNo',
				phoneNo:phoneNo,
			 }, 
                 function (data) {
					hideMainLoader();
						if(data!=''){
							document.getElementById('content_overley').style.display="block";
							document.getElementById('content_display_2').style.display="block";
							document.getElementById('html_data_2').innerHTML = data;
						}
					
				});
	  }   
	   
	   
	   
	   
	   
	   function getPreviousBooking(refernceId){
		  		var  refernceId,postfrom; 
				if(refernceId==''){
					alert('Enter Reference No');
					document.getElementById('vehicle_select').focus();
					hideMainLoader();
					return false;    
				}
				jQuery.post("<?php echo $glob['storeURL']; ?>ajax_functions.php", {
          		action: 'get_previous_booking',
				refernceId:refernceId,
				
			 }, 
                 function (data) {
					hideMainLoader();
					$("#search_span_not").slideUp();
					var aKeys,key,val;
						if(data!='0'){
							var aData = data.split("|");	
							for(i=0;i<aData.length;i++){
							aKeys = aData[i].split('=');
							key  = aKeys[0];
							val  = aKeys[1];
							getKey(key,val); 
						}
					}else{
						$("#search_span_not").slideDown();
					}
					return false;
				});
			}
	   
			function getKey(key,value){
				if(key=='from_id_onload'){
					$('#'+key).show();
					$('#from_id').hide();
					$('#'+key).val(value); 
					$('#'+key).attr('disabled',true);
					$('#origen').val(value);
				}else
				if(key=='to_id_onload'){
					$('#'+key).show();
					$('#to_id').hide();
					$('#'+key).val(value);
					$('#'+key).attr('disabled',true);
					$('#destination').val(value);		
				}else
				if(key=='payment_type'){
					
					if(document.getElementById('is_quick').value!=""){
						$('#'+key).val(value);
						if(value==2){
							$("#payment_type").val(2);
							$(".bg_cash").addClass('selected_payment');
							$(".bg_account").removeClass('selected_payment');
						}else
						if(value==4){
							$("#payment_type").val(4);
							$(".bg_cash").removeClass('selected_payment');
							$(".bg_account").addClass('selected_payment');
						}
						$('#'+key).attr('readonly', true);	
					}else{
					  
					  if(value==1){
							
							$("#list_cash").removeClass('active');
							$("#list_card").addClass('active');
							$("#list_account").removeClass('active');
							
							$("#tab2default").removeClass('active in');
							$("#tab1default").addClass('active in');
							$("#tab3default").removeClass('active in');
							
							$('#radio_account').prop('checked', false);
							$('#radio_credit_card').prop('checked', true);
							$('#radio_cash').prop('checked', false);
							
							$('#radio_cash').attr('disabled',true);	
							
					  }else	
					  if(value==2){
							$("#list_cash").addClass('active');
							$("#list_card").removeClass('active');
							$("#list_account").removeClass('active');
							
							$("#tab2default").removeClass('active in');
							$("#tab1default").removeClass('active in');
							$("#tab3default").addClass('active in');
							
							$('#radio_account').prop('checked', false);
							$('#radio_credit_card').prop('checked', false);
							$('#radio_cash').prop('checked', true);
					  }else	
					  if(value==4){
						   
							$("#list_account").addClass('active');
							$("#list_card").removeClass('active');
							$("#list_cash").removeClass('active');
							
							$("#tab2default").addClass('active in');
							$("#tab1default").removeClass('active in');
							$("#tab3default").removeClass('active in');
							
							
							$('#radio_account').prop('checked', true);
							$('#radio_credit_card').prop('checked', false);
							$('#radio_cash').prop('checked', false);
							
							$('#radio_cash').attr('disabled',true);	
					  }	
					}
				}else
					if(key=='booking_price'){
					$('#'+key).val(value);
					$('#'+key).attr('disabled',true);		
					}else
					if(key=='how_many'){
					$('#'+key).val(value);
					$('#'+key).attr('disabled',true);		
				}else
				if(key=='luggage'){
					$('#'+key).val(value);
					$('#'+key).attr('disabled',true);  
				}else
				if(key=='flight_no'){
					$('#'+key).val(value);
					$('#'+key).attr('disabled',true);
				}else
				if(key=='is_child_trolley'){
					$('#'+key).val(value);
					if(value==1){
						$('#'+key).prop('checked', true);
					}	
				}else
				if(key=='is_disable_trolley'){
					$('#'+key).val(value);
					if(value==1){
						$('#'+key).prop('checked', true);
					}
				}else
				if(key=='pickup_date'){
					$('#'+key).val(value);
					$('#'+key).attr('disabled',true);	
				}else
				if(key=='timepicker_start'){
					$('#'+key).val(value);
					$('#'+key).attr('disabled',true);
				}else
				if(key=='extar_notes'){
					$('#'+key).val(value);	
					$('#'+key).attr('disabled',true);
				}else
				if(key=='booking_username'){
					$('#'+key).val(value);
					$('#'+key).attr('disabled',true);
				}else
				if(key=='booking_email'){
					$('#'+key).val(value);
					$('#'+key).attr('disabled',true);
				}else
				if(key=='booking_phoneno'){
					$('#'+key).val(value);
					$('#'+key).attr('disabled',true);
				}else
				if(key=='vehicle_select'){
					$('#'+key).val(value);	
					$('#'+key).attr('disabled',true);
				}else // nbew
				if(key=='booking_commission'){
					$('#'+key).val(value);	
					$('#'+key).attr('disabled',true);
				}else
				if(key=='waiting_time'){
					$('#'+key).val(value);	
					$('#'+key).attr('disabled',true);
				}else
				if(key=='booking_parking'){
					$('#'+key).val(value);	
					$('#'+key).attr('disabled',true);
				}else
				if(key=='booking_paymnet_received'){
					$('#'+key).val(value);	
					if(value==1){
						$('#'+key).prop('checked', true);
					}
					$('#'+key).attr('disabled',true);	
			    }else
				if(key=='booking_phoneno_alt'){
					$('#'+key).val(value);	
					$('#'+key).attr('disabled',true);
				}
				
				refreshIframe('map_iframe');
				$("#save_btn").attr('disabled',true);
				$("#cal_price").attr('disabled',true);
				$("#btn_route").attr('disabled',true);
				$("#save_dispatch_btn").attr('disabled',true);
			}
	   		
		  function getLocationsData(){
		   
			if(document.getElementById('is_quick').value!=""){
				document.getElementById('popup_loader').style.display='block';
			}
			var from_id,to_id;
			if(document.getElementById('from_id_onload').style.display=='none'){
				 from_id =  document.getElementById('from_id').value;	
			}else{
				 from_id =  document.getElementById('from_id_onload').value;
			}
			
			
			if(document.getElementById('to_id_onload').style.display=='none'){
				 to_id =  document.getElementById('to_id').value;	
			}else{
				 to_id =  document.getElementById('to_id_onload').value;
			}
			
			document.getElementById('overley').style.display="block";
		 	 var ajaxRequest;  // The variable that makes Ajax possible!
						try{
							ajaxRequest = new XMLHttpRequest();
						}catch (e){
						 try{
							ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
						   }catch (e) {
							 try{
								 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
								}catch (e){
									alert("Your browser broke!");
							 return false;
							}
						}
					 }
					ajaxRequest.onreadystatechange = function(){
					if(ajaxRequest.readyState == 4){

							responseText = ajaxRequest.responseText;
							var distance, duration,totalMiles;
							
							if(document.getElementById('is_quick').value!=""){
								document.getElementById('popup_loader').style.display='none';
							}
							
							if(responseText!=0){
								if(document.getElementById('is_quick').value!=""){
									document.getElementById('booking_price').value = '';
								}
								
								var aResponse =responseText.split(",");
								distance = aResponse[0]; // distance
								duration = aResponse[1]; // duration 
								totalMiles = aResponse[2]; // duration 
								document.getElementById('overley').style.display="none";
								document.getElementById('km_div').innerHTML = distance;
								document.getElementById('min_div').innerHTML = duration;
								document.getElementById('km_field').value = distance;
								document.getElementById('duration_field').value = duration;
								document.getElementById('total_miles').value = totalMiles;
							}else{
								document.getElementById('overley').style.display="none";
								document.getElementById('km_div').innerHTML = '<span style="color:red;">00 km</span>';
							}
							
							getMap(from_id,to_id);
							return true;
					}
				}  

						var queryString = "?from=" + from_id+'&to='+to_id;
						queryString += "&action=" + 'getDistance';
						ajaxRequest.open("GET", "<?php echo $glob['storeURL']; ?>get_locations.php" + queryString, true);
						ajaxRequest.send(null); 
				
       }
	   
	    
		function getMap(from_id,to_id){
			document.getElementById('origen').value = from_id;
			document.getElementById('destination').value = to_id;
			var ifr = document.getElementById('map_iframe');
			ifr.src = ifr.src;
		}
	   
		
	   
		function saveUserAccount(){
		 
			var account_username =  document.getElementById('account_username').value;
			var account_email =  document.getElementById('account_email').value;
			var account_phoneno =  document.getElementById('account_phoneno').value;
			var account_address =  document.getElementById('account_address').value;
			var error = true
			
			var aFields = ['account_username','account_email','account_phoneno','account_address'];
			
			for(j = 0; j<aFields.length; j++ ){
				document.getElementById(aFields[j]).style.border="";
			}
			if(account_username ==''){
				document.getElementById('account_username').focus();
				document.getElementById('account_username').style.border="1px solid red";
				error = false;	
			}
			if(account_email ==''){
				 document.getElementById('account_email').focus();
				 document.getElementById('account_email').style.border="1px solid red";
			  	 error = false;
			}
			if(account_phoneno ==''){
				 document.getElementById('account_phoneno').focus();
				 document.getElementById('account_phoneno').style.border="1px solid red";
			  	error = false;
			}
			if(account_address ==''){
				 document.getElementById('account_address').focus();
				  document.getElementById('account_address').style.border="1px solid red";
			  	 error = false;
			}
			
			if(error!=true){
			 	return false;	
			}
			
			document.getElementById('overley').style.display="block";
			//return false;
		  var ajaxRequest;  // The variable that makes Ajax possible!
						try{
							ajaxRequest = new XMLHttpRequest();
						}catch (e){
						 try{
							ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
						   }catch (e) {
							 try{
								 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
								}catch (e){
									alert("Your browser broke!");
							 return false;
							}
						}
					 }
					ajaxRequest.onreadystatechange = function(){
					if(ajaxRequest.readyState == 4){
							responseText = ajaxRequest.responseText;
							document.getElementById('overley').style.display="none";
							if(responseText !=''){
							   	alert('User added successfully');
								$("#assign_user").append(responseText);
							    return true;
							} else{
							  	alert('operation faild.');
							    return true;	
							}
							
					}
				}  

						var queryString = "?name=" + account_username+'&email='+account_email+'&phone='+account_phoneno+'&address='+account_address;
						queryString += "&action=" + 'saveuseraccount';
						ajaxRequest.open("GET", "<?php echo $glob['storeURL']; ?>get_locations.php" + queryString, true);
						ajaxRequest.send(null); 
				
       		}
		
		
		
		function chooseVehicle(){
			var how_many =  $('#how_many').val();
			var luggage =  $('#luggage').val(); 
			var total  = parseInt(how_many) +  parseInt(luggage);
			if(total <= 6){ // saloon
				$("#car_type_1").removeAttr('disabled');
				document.getElementById('car_type_1').checked=true;	
				var disableElements  = ['car_type_3','car_type_2','car_type_4'];
			}
			else
			if(total == 7){ // estate
				$("#car_type_2").removeAttr('disabled');
			  	document.getElementById('car_type_2').checked=true;	
				var  disableElements  = ['car_type_1','car_type_3','car_type_4'];
			}
			else
			if(total > 7 && total <=10){ // mpv
				$("#car_type_3").removeAttr('disabled');
			 	document.getElementById('car_type_3').checked=true;
				var  disableElements  = ['car_type_1','car_type_2','car_type_4'];			
			}
			else
			if(total > 7 & total <=13){ // large mpv
				$("#car_type_4").removeAttr('disabled');
				document.getElementById('car_type_4').checked=true;	
				var  disableElements  = ['car_type_1','car_type_2','car_type_3'];
			}
			else {// large mpv
			 	$("#car_type_4").removeAttr('disabled');
				var disableElements  = ['car_type_1','car_type_2','car_type_3'];
				document.getElementById('car_type_4').checked=true;
			}
			
			for(i = 0; i < disableElements.length; i++){
				document.getElementById(disableElements[i]).disabled=true;	
				
			}
			return true;	
			
		}
		
	
		function getSelectedPayment(arg){
			 var arg;
			  var disableElements ="";
				if(arg=='cash'){
					$("#radio_cash").removeAttr('disabled');
					disableElements  = ['radio_credit_card','radio_account'];
					document.getElementById('radio_cash').checked=true;	 
				}else
				if(arg=='account'){
					$("#radio_account").removeAttr('disabled');
					disableElements  = ['radio_cash','radio_credit_card'];
					document.getElementById('radio_account').checked=true; 
				}else
				if(arg=='card'){
						$("#radio_credit_card").removeAttr('disabled');
					disableElements  = ['radio_account','radio_cash'];
					document.getElementById('radio_credit_card').checked=true; 
				}
			 	for(i = 0; i < disableElements.length; i++){
					document.getElementById(disableElements[i]).checked=false;
					document.getElementById(disableElements[i]).disabled=true;		
				}
			return true;	
		  }
		
	 		 function reset_all_quick(refernece){
	    		//var refernece;
				
				$("#from_id_onload").show();
				$("#from_id_onload").val('');
				$("#from_id").hide();
				$("#to_id_onload").show();
				$("#to_id_onload").val('');
				$("#to_id").hide();
				
				
				 var disableFields;
					if(refernece==0){  //quick case
						getDrivers(0); // for all driver
						$("#from_postcode").val('');
						$("#to_postcode").val('');
						var disableFields = ["flight_no","how_many","booking_price","from_id_onload","to_id_onload",
						"extar_notes","save_btn","booking_phoneno","booking_email","booking_username",
						"pickup_date","timepicker_start","vehicle_select","btn_route","cal_price","luggage"]; 
						var ele_vehicle_select = document.getElementById("vehicle_select").options;
						var ele_input_texibasename = document.getElementById("input_texibasename").options;
						
						for(var i = 0; i < ele_vehicle_select.length; i++){
							ele_vehicle_select[i].selected = false;
						}
						
						for(var i = 0; i < ele_input_texibasename.length; i++){
							ele_input_texibasename[i].selected = false;
						}
						$("#how_many").val('');
						$("#booking_price").val('');
				}else{ // normal booking
						var disableFields = ["flight_no","how_many","booking_price","from_id_onload","to_id_onload",
						"extar_notes","save_btn","booking_phoneno","booking_email","booking_username","pickup_date","timepicker_start","booking_phoneno_alt","booking_commission","booking_parking","waiting_time","save_dispatch_btn","luggage","booking_paymnet_received","radio_cash"];
						
						document.getElementById("booking_paymnet_received").checked=false;
						var ele_how_many = document.getElementById("how_many").options;
						$("#booking_commission").val('');
						$("#booking_parking").val('');
						$("#waiting_time").val('');
						$("#booking_price").val('');
						$("#booking_phoneno_alt").val('');
						
						for(var i = 0; i < ele_how_many.length; i++){
							ele_how_many[i].selected = false;
						}
						
						$("#list_cash").addClass('active');
						$("#list_card").removeClass('active');
						$("#list_account").removeClass('active');
						
						$("#tab2default").removeClass('active in');
						$("#tab1default").removeClass('active in');
						$("#tab3default").addClass('active in');
						$('#radio_account').prop('checked', false);
						$('#radio_credit_card').prop('checked', false);
						$('#radio_cash').prop('checked', true);
					}
				
				
				
				
				$("#booking_username").val('');
				$("#booking_phoneno").val('');
				
				var ele_luggage = document.getElementById("luggage").options;
				for(var i = 0; i < ele_luggage.length; i++){
					ele_luggage[i].selected = false;
				}
				
				$("#flight_no").val('');
				$("#booking_email").val('');
				$("#extar_notes").val('');
				$("#destination").val('');
				$("#origen").val('');
				
				$("#refernce_no").val('');
		 		$('#previous_booking_id').slideUp();
				
				
				refreshIframe('map_iframe');
				document.getElementById("is_child_trolley").checked=false;
				document.getElementById("is_disable_trolley").checked=false;
				
				
				/******remove disablity******/
				  
				$("#timepicker_start").val($("#time_picker").val());
				$("#pickup_date").val($("#date_picker").val());
				
				for(var i = 0; i < disableFields.length; i++){
					$('#'+disableFields[i]).attr('disabled',false);
				}
				
				$('#is_child_trolley').prop('checked', false);
				$('#is_disable_trolley').prop('checked', false); 
		  }
	
	
	
	
	
	
	    // get drivers agaist base-name
		function getDrivers(id){
			var img = '<img src="<?php echo $glob['storeURL']; ?>images/driver_loader.gif" id="driver_loader">';
			$("#drivers_data").html(img);
			jQuery.post("<?php echo $glob['storeURL']; ?>get_locations.php", {
                action: 'get_driver',
				id:id},
            function (data) {
				$("#drivers_data").html(data);
				return false;
			});
		}
	  
		function getURL(){
			return  document.getElementById('store_url').value;	  
		}
		function refreshIframe(id){
			var id;
			var ifr = document.getElementById(id);
			ifr.src = ifr.src;  
		}
	  
	  /*********************khan**************************/
	  function CallLoader(id,display_div){
			//var base_url = $("#base_url").val();
			if(id==true){
			var img ='<img src="<?php echo $glob['storeURL']; ?>images/loading-black.gif" id="small_loader">';
			}else{
			var img ='';	
			}
			$("#"+display_div).html(img);
		}
		
	  
	  
	   function getCoordinates(location){
			
			jQuery.post("<?php echo $glob['storeURL']; ?>ajax_functions.php", {
                action: 'get_coordinates',
				location:location},
            function (data) {
				var strData = data.split("|");
				var lattitude = strData[1];
				var longitude = strData[2];
				var formated_address = strData[0];
				$("#longitude").val(longitude);
				$("#lattitude").val(lattitude);
				$("#formated_address").val(formated_address);
				CallLoader(false,'map');
				var embedIframe = '<iframe src="map_drawer.php" id="map_circle" frameborder="0" scrolling="no" width="1400" height="650"></iframe>';
				$("#map").html(embedIframe);
				$("#back_to_search").show();
				$("#draw-circle").hide();
				refreshIframe('map_circle');
				return false;
			});
		}
		
		
		

		function checkNullBeforeSubmit(){
		
			var  booking_username  = $("#booking_username").val();
			var  booking_phoneno  = $("#booking_phoneno").val();
			var  booking_price  = $("#booking_price").val();
			
			if(booking_username==''){
				alert('UserName Is empty');
				document.getElementById('booking_username').focus();
				return false;    
			}
			
			if(booking_phoneno==''){
				alert('Phone No should must be enter');
				document.getElementById('booking_phoneno').focus();
				return false;    
			}
			
			if(document.getElementById('from_id_onload').style.display=='block' && document.getElementById('from_id_onload').value==''){
				alert('Pick up location is still empty');
				document.getElementById('from_id_onload').focus();
				return false; 	
			}else
			if(document.getElementById('from_id').style.display=='block' && document.getElementById('from_id').value==''){
				alert('Pick up location is still empty');
				document.getElementById('from_id').focus();
				return false; 
			}
			
			if(document.getElementById('to_id_onload').style.display=='block' && document.getElementById('to_id_onload').value==''){
				alert('Destination location is still empty');
				document.getElementById('to_id_onload').focus();
				return false; 	
			}else
			if(document.getElementById('to_id').style.display=='block' && document.getElementById('to_id').value==''){
				alert('Destination location is still empty');
				document.getElementById('to_id').focus();
				return false; 
			}
			
			
					
			
			if(booking_price==''){
			alert('Price Cannoy be empty or less then zero');
			document.getElementById('booking_price').focus();
			return false;    
			}
			
			return true;
		}
		
	  /*******************khan*****************************/
	  
	 
	   // khan time plugin
		$( document ).ready(function() {
			var list = "";
			var month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
			var date = new Date();
			var year = date.getFullYear(); // get year
			var month = month[date.getMonth()]; // get month
			var hours  = date.getHours(); // get hours
			var miniutes  = date.getMinutes(); // get hours
			var aTimeP1  = ['06:00','06:15','06:30','06:45','07:00','07:15'];
			var aTimeP2  = ['07:30','07:45','08:00','08:15','08:30','08:45'];
			var aTimeP3  = ['09:00','09:15','09:30','09:45','10:00','10:15'];
			var aTimeP4  = ['10:30','10:45','11:00','11:15','11:30','11:45'];
			var aTimeP5  = ['12:00','12:15','12:30','12:45','13:00','13:15'];
			var aTimeP6  = ['13:30','13:45','14:00','14:15','14:30','14:45','15:00'];
			
			var aTime = aTimeP1.concat(aTimeP2,aTimeP3,aTimeP4,aTimeP5,aTimeP6);
			
			for(j = 0; j<aTime.length; j++ ){
				list += '<li>'+aTime[j]+'</li>';
			}
		
			var content ='<div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all" style="padding: 5px; margin-bottom: 1px;text-align: center;"><div class="ui-datepicker-title"><span class="ui-datepicker-month">'+month+'</span>&nbsp;<span class="ui-datepicker-year">'+year+'</span></div></div>'+list+'<img src="<?php echo $glob['storeURL']; ?>/images/close_img.png" id="close_btn">';
				$("#timepicker").append(content);
				
			/*$("#timepicker_start").focus(function(){
				$("#timepicker").slideDown();
				return true;
			})*/
			
			/*$('#timepicker').on('click', 'li', function () {
				var time = $(this).html();
				$("#timepicker_start").val(time);
				$("#timepicker").slideUp();
				return true;
			});
			
			$("#close_btn").click(function(){
				$("#timepicker").slideUp();	
				return true; 
			})*/
			
			/*$('#selction').click(function(){
			  	
			  $("#id").addClass('clllll');
			 if($("#id").hasClass('clllll')){
				  alert('hjhj);
				 $("#id").removeClass('fdsf');
				  
			}
			  	
			})*/
			
			// me
			
			
			
			
			// add user 
			$("#add_user").click(function(){
				 $("#openaccountuserdiv").slideDown();
			})
			
			$("#account_user_save").click(function(){
				saveUserAccount();
			})
			$("#account_user_close").click(function(){
				$("#openaccountuserdiv").slideUp();
				var aFields = ['account_username','account_email','account_phoneno','account_address'];
				for(j = 0; j<aFields.length; j++ ){
					document.getElementById(aFields[j]).style.border="";
					$("#"+aFields[j]).val('');
				}
			})
			
			// main booking form
			$( "#save_btn" ).mouseover(function() {
				$("#save_btn").fadeTo(500, 0.7);
			});
			
			$( "#save_btn" ).mouseout(function() {
				$("#save_btn").removeAttr("style");
			})
			
			$( "#exit_btn" ).mouseover(function() {
				$("#exit_btn").fadeTo(500, 0.7);
			});
			
			$( "#exit_btn" ).mouseout(function() {
				$("#exit_btn").removeAttr("style");
			})
			
			
			$("#save_btn").click(function(){
			if($("#is_quick").val()==1){
				$("#booking_status").val(1); // booking_status
				$("#main_booking_form").submit();
				return true;
			}else{	
			//alert('hererer for save click');
				$("#booking_status").val(0); // booking_status
				if($("#booking_status").val() !=''){
					if(checkNullBeforeSubmit()==true){
					$("#main_booking_form").submit();
						return true;
					}
				}
			}
			})
			
			$("#save_dispatch_btn").click(function(event){
				$("#booking_status").val(1);
				if($("#booking_status").val() !=''){
					//alert($("#booking_status").val());
					
					if($("#check_driver_radio").val()==1){
						$("#assign_driver_error").slideUp();
						if(checkNullBeforeSubmit()==true){
						  $("#main_booking_form").submit();
						}
						//event.preventDefault();
						return false;
					}else{
						
						$("#assign_driver_error").slideDown();
						$("#assign_driver_error").html('Choose driver for this job.');
						$("#driver_no_1").focus();
						event.preventDefault();
					  	return false;
					}
				}
			})
			
			// exit button 
			$("#exit_btn").click(function(){
				window.location ='<?php echo $glob['storeURL'];?>dashboard2/dashboard';
			})
			// get quick click.
			$(".js-quick-modal").click(function(){
				getDrivers(0); // get all drivers
				$(".bg_cash").addClass('selected_payment');
				$(".bg_account").removeClass('selected_payment');
				$("#is_quick").val(1);
				$("#payment_type").val(2);
				$("#km_div").html('0.00 Km');
				$("#min_div").html('0.00');
				$("#origen").val('');
				$("#destination").val('');
				$("#from_postcode").val('');
				$("#to_postcode").val('');
				var ifr = document.getElementById('map_iframe');
				ifr.src = ifr.src;
				$("#timepicker").removeClass('time-input-list');
				$("#timepicker").addClass('time-input-list-quick');
				var companyAddress = $("#hiddenFromAddres").val();
				$("#from_id").val(companyAddress);
				$("#redirection_div").val('<?php echo $glob['storeURL'].'dashboard2/dashboard';?>');
			})
			
			// rota model
			$(".js-rota-modal").click(function(){
				var url  = getURL();
				$(".modal-box").css('height','14px');
				$(".modal-box").css('width','79%');
				var embedIframe = '<iframe src="'+url+'/rota_drivers.php" id="rota_iframe" frameborder="0" scrolling="no"></iframe>';
				$(".main_popup_body").html(embedIframe);
				refreshIframe('rota_iframe');
			})
			
			// button selection by khan
			
			$('#decsion_checkbox').on('click', 'a', function () {
				var currentClass  = $(this).attr('class'); // get cuurent class
				var currentId  = $(this).attr('id');
				$("."+currentId).val(1); // assign val to hidden fileds
				if($(this).hasClass('selected_btn')){ // check if current class exist
					$(this).removeClass('selected_btn'); // this remove class
					var currentId  = $(this).attr('id');
					$("."+currentId).val('');
					$(this).removeAttr("style");
				}else{
					$(this).addClass('selected_btn');
					$(".selected_btn").fadeTo(500, 0.4);
				}
			});
			
			$('.popup_left_top').on('change', 'select', function () {
			  	if($(this).val()!=''){
					getDrivers( $(this).val());
				}
			});
			
				
			// quick previous booking
				$("#previous_booking_textf").click(function(){
					$("#previous_booking_id").slideDown();	
				})
				
				$("#previous_booking_get_close").click(function(){
					$("#refernce_no").val('');
					$("#previous_booking_id").slideUp();	
				})
				
				$("#previous_booking_get_serach").click(function(){
					showMainLoader();
					getPreviousBooking($("#refernce_no").val());
				})
			// quick previous booking
			$('.yellow_footer').on('click', 'input[type="radio"]', function () {
				document.getElementById('overley').style.display="block";
				   $("#check_driver_radio").val(1);
					var pickupDate =   $("#pickup_date").val();
					var pickupTime =  $("#timepicker_start").val();
				   if($(this).attr('value') !=''){
						if(pickupDate =='' || pickupTime ==''){
							$("#assign_driver_error").slideDown();
							$("#assign_driver_error").html('Make sure Pickup Date and Time have proper value.');
							document.getElementById('overley').style.display="none";
							return false;
						}else{
							$("#assign_driver_error").slideUp();
							checkDriverAvailability($(this).attr('value'),pickupDate,pickupTime);
					 		return true;	
						}
					}// send driver id for availablity
			});
		
			// for payment type check
			$('.popup_left_top').on('click', 'input', function () {
			    var currentClick = $(this).attr('value');
				var hiddenVal ;
				if(currentClick =='cash'){
					hiddenVal=2;
				 	$(".bg_account").removeClass('selected_payment');
					$(".bg_cash").addClass('selected_payment');
				}else{
					hiddenVal=4;
					$(".bg_cash").removeClass('selected_payment');
					$(".bg_account").addClass('selected_payment')
				}
				
				$("#payment_type").val(hiddenVal);
			});
			
			
			// display none/block address divs
			/*$("#elm_body").click(function(){
				if(document.getElementById("address_from").style.display=='block'){
				 $("#address_from").hide();
				}
				
				if(document.getElementById("address_to").style.display=='block'){
				 	$("#address_to").hide();
				}
			})*/
			 
			// meet and greet form block 
			$("#meetgreet").click(function(){
				$("#openmeetgreet").slideDown();
			})
			// meet and greet form close 
			$("#meetgreet_close").click(function(){
				$("#openmeetgreet").slideUp();
			})
			
			/*********************Report Click**************************/
			$(".js-report-modal").click(function(){
					var url  = getURL();
					$(".modal-box").css('height','14px');
					$(".modal-box").css('left','14px');
					$(".modal-box").css('width','98%'); 
					var embedIframe = '<iframe src="'+url+'/report_options.php" id="report_iframe" frameborder="0" scrolling="no"></iframe>';
					$(".main_popup_body").html(embedIframe);
					refreshIframe('report_iframe');
			 })
			/*********************Report Click**************************/
			
		// change tomorrow to today.
			 $("#btn_schedule").click(function(){
				var monthIndex = "";
				for(j=1;j<=12;j++){
					if(j==12){
						monthIndex +=j;	
					}else{
						monthIndex +=j+',';		
					}
				}
				var aMonth = [1,2,3,4,5,6,7,8,9,10,11,12];	
				var date = new Date();
				var tDay;
				var year = date.getFullYear(); // get year
				var month = aMonth[date.getMonth()];
				var day = date.getDate();
				var tDay = day + 1;
				
				if(tDay<10) {
					tDay='0'+tDay
				} 
				if(day<10) {
					day='0'+day
				} 
				if(month<10) {
					month='0'+month
				} 
				var  makeDate  = day+'-'+month+'-'+year;
				var tomorrowDate =  tDay+'-'+month+'-'+year;
				if($("#btn_schedule").html()=='Tomorrow'){
					$("#btn_schedule").html('Today');
					$("#pickup_date").val(tomorrowDate); 
				}else{
					$("#btn_schedule").html('Tomorrow'); 
					$("#pickup_date").val(makeDate);
				
				}
			  })
			 
			 
			// close meet greet popup 
			$("#close_popup").click(function(){
				$("#content_overley").fadeTo(500, 0.7);
				$("#content_overley").hide();
				$("#content_display").hide();
			})
			// khan
			$("#close_popup_2").click(function(){
				$("#content_overley").fadeTo(500, 0.7);
				$("#content_overley").hide();
				$("#content_display_2").hide();
			})
			
			
			// calculate price
			$("#cal_price").click(function(){
				showMainLoader();
				calculatePrice();
			})
				
				
			// check its detail null or have data
			$("#meetgreet_username").keyup(function(){
				if($("#meetgreet_username").val()!=''){
					$("#meetgreet").addClass('have_data');
				}else{
					if($("#meetgreet_detail").val()!=''){
					// do nothing
					}else{
					$("#meetgreet").removeClass('have_data');
					}
				}
			})
			
			// check its detail null or have data
			$("#meetgreet_detail").keyup(function(){
				if($("#meetgreet_detail").val()!=''){
					$("#meetgreet").addClass('have_data');
				}else{
					if($("#meetgreet_username").val()!=''){
					// do nothing
					}else{
					$("#meetgreet").removeClass('have_data');
					}
				}
			})
			
			
			/*********************Driver Ploting Start**************************/
			  $(".js-plot-modal").click(function(){
				  	var url  = getURL();
					$(".modal-box").css('height','14px');
					$(".modal-box").css('width','79%'); 
					var embedIframe = '<iframe src="'+url+'/driver_plot_list.php" id="plot_iframe" frameborder="0" scrolling="no"></iframe>';
					$(".main_popup_body").html(embedIframe);
					refreshIframe('plot_iframe');
				})
			
			/*********************Driver Ploting End**************************/
			
			/************************khan******************************/
			 
			$("#draw-circle").click(function(){
				var location = $("#pac-input").val();
				CallLoader(true,'map');
				if(location!=''){
					getCoordinates(location);
				}
			})
			
			$("#btn_back").click(function(){
				window.location="map_plot.php";	
			})
			
			// PREVIOUS JOURENY on phone no
			
			$("#booking_phoneno").blur(function(){
				if ($("#booking_phoneno").val() !=''){
				   getPreviousjourneyByPhoneNo($("#booking_phoneno").val());
				}	
			})
			
			/***********************khan********************************/
			
			
			
			
			
		}); // document ready end
		
		</script>
		
	
	<script>
        $(function(){
        
        var appendthis =  ("<div class='modal-overlay js-modal-close' id='model_overley'></div>");
        	$('a[data-modal-id]').click(function(e) {
				e.preventDefault();
				$("body").append(appendthis);
				$(".modal-overlay").fadeTo(500, 0.7);
				//$(".js-modalbox").fadeIn(500);
				var modalBox = $(this).attr('data-modal-id');
				$('#'+modalBox).fadeIn($(this).data());
            });
			
			$('span[data-modal-id]').click(function(e) {
				e.preventDefault();
				$("body").append(appendthis);
				$(".modal-overlay").fadeTo(500, 0.7);
				//$(".js-modalbox").fadeIn(500);
				var modalBox = $(this).attr('data-modal-id');
				$('#'+modalBox).fadeIn($(this).data());
            });  
          
		 
          
        $(".js-modal-close, .modal-overlay,#exit_btn_qucik").click(function() {
			//alert('herre');
            $(".modal-box, .modal-overlay").fadeOut(500, function() {
                $("#timepicker").slideUp();
				$(".modal-overlay").remove();
				$("#from_id").val('');
				$("#to_id").val('');
				reset_all_quick(0);
			 });
           
        });
         
        $(window).resize(function() {
            $(".modal-box").css({
                top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
                left: ($(window).width() - $(".modal-box").outerWidth()) / 2
            });
        });
         
        $(window).resize();
		});
		
		//  hide menu when click other than field
		$('html').click(function() {
			if($("#from_id_onload").is(':focus') || $("#from_id").is(':focus')){
			  document.getElementById("address_from").style.display="block";	
			}else{
				document.getElementById("address_from").style.display="none";		
			}
			
			if($("#to_id_onload").is(':focus') || $("#to_id").is(':focus')){
			  document.getElementById("address_to").style.display="block";	
			}else{
				document.getElementById("address_to").style.display="none";		
			}
		});
		
	</script>
    
	</body>
</html>




