<?php include('includes/include.php');
ini_set('max_execution_time', 0);
$mail->Subject = "Daily Activity Report - ".date('jS F Y',strtotime("-1 days")).".";
$dat=date('Y-m-d',strtotime("-1 days"));
$dat1=date('Y-m-01');
$dat2=date('Y-m-t');
         
		  
			
$resellers=db_query("select id,name from partners where id!=45");
while($data=db_fetch_array($resellers))
{
	echo $manager_email=getSingleresult("select email from users where user_type='MNGR' and team_id='".$data['id']."'");
	$mail->AddAddress($manager_email);
	$mail->Body='<style type="text/css"> 
.TFtable{ width:100%; border-collapse:collapse; font-family:verdana; } 
.TFtable td{ padding:7px; border:#4e95f4 1px solid; } 
.TFtable th{ padding:7px; border:#4e95f4 1px solid;background: #acdc9c; } 
.TFtable tr{ background: #b8d1f3; } 
.odd{ background: #b8d1f3; } 
.even{ background: #dae5f4; }
.first_table td{background: #061621;color:#FFFDFC;background: -moz-linear-gradient(top, #445058 0%, #1f2d37 66%, #061621 100%);
  background: -webkit-linear-gradient(top, #445058 0%, #1f2d37 66%, #061621 100%);
  background: linear-gradient(to bottom, #445058 0%, #1f2d37 66%, #061621 100%);
  border: 2px solid #444444;} 
 .second_table td{ 
   background: #371044;
  background: -moz-linear-gradient(top, #a6554b 0%, #943327 66%, #891D0F 100%);
  background: -webkit-linear-gradient(top, #a6554b 0%, #943327 66%, #891D0F 100%);
  background: linear-gradient(to bottom, #a6554b 0%, #943327 66%, #891D0F 100%);
  border: 2px solid #444444;color:#fff;
}
  </style>';		

		$mail->Body.='<div style="margin:0px;padding:5px;background-color:#fff;">';
		
			$mail->Body .='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
<thead class="first_table">
<tr>
<td colspan="12"><strong>Daily Report</strong></td>
</tr>
<tr>
<td colspan="11"><strong>Stage</strong></td>
<td rowspan="3"><strong>Visibility for day</strong></td>
</tr>
<tr>
<td rowspan="2"><strong>Sr. Number</strong></td>
<td rowspan="2"><strong>'.$data['name'].'</strong></td>
<td><strong>Data Received</strong></td>
<td><strong>DVR</strong></td>
<td><strong>Calls</strong></td>
<td rowspan="2">&nbsp;</td>
<td rowspan="2"><strong>Quote</strong></td>
<td rowspan="2"><strong>Follow-Up</strong></td>
<td rowspan="2"><strong>Commit</strong></td>
<td rowspan="2"><strong>Booking</strong></td>
<td rowspan="2"><strong>OEM Billing</strong></td>
</tr>
<tr>
<td><strong>Qualified</strong></td>
<td><strong>Daily Visit Updated</strong></td>
<td><strong>(Log A Calls)</strong></td>
</tr></thead><tbody>';
$users1=db_query("select * from users where team_id='".$data['id']."'");
$i=1;
while($users=db_fetch_array($users1))
{
//approved
$qualified_data=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and date(created_date)='".$dat."' and created_by='".$users['id']."' and status='Approved'");	
$total_qualified+=$qualified_data;
//end//
$dvr_data=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and date(created_date)='".$dat."' and created_by='".$users['id']."' and is_dr=1");
$total_dvr+=$dvr_data;
//
$log_call=getSingleresult("SELECT count(*) FROM `activity_log` JOIN orders on orders.id=activity_log.pid where orders.team_id='".$data['id']."' and orders.created_by='".$users['id']."' and date(activity_log.created_date)='".$dat."'");
$total_lac+=$log_call;
//
$quote=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Quote' and date(created_date)='".$dat."'");
$total_quote+=$quote;
//
$verification=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and (stage='Verification' or stage='Follow-Up') and created_by='".$users['id']."' and date(created_date)='".$dat."'");
$total_verification+=$verification;
//
$commit=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Commit' and date(created_date)='".$dat."'");
$total_commit+=$commit;
//
$booking=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Booking' and date(created_date)='".$dat."'");
$total_booking+=$booking;
//
$oem=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='OEM Billing' and date(created_date)='".$dat."'");
$total_oem+=$oem;
//

$total_acc=$commit+$booking+$oem;

$grand_total_acc+=$total_acc;

$quote_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Quote' and date(created_date)='".$dat."'");
$total_quote_quantity+=$quote_quantity;
//
$verification_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and  (stage='Verification' or stage='Follow-Up') and date(created_date)='".$dat."'");
$total_verification_quantity+=$verification_quantity;
//
$commit_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Commit' and date(created_date)='".$dat."'");
$total_commit_quantity+=$commit_quantity;
//
$booking_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."'  and stage='Booking' and date(created_date)='".$dat."'");
$total_booking_quantity+=$booking_quantity;
//
$oem_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."'  and stage='OEM Billing' and date(created_date)='".$dat."'");
$total_oem_quantity+=$oem_quantity;
//
$total_quantity=$commit_quantity+$booking_quantity+$oem_quantity;
$grand_total_quantity+=$total_quantity;

if($i%2)
{
	$class_new='class="even"';
}
else
	{
		$class_new='class="odd"';
	}
	
 $mail->Body.='<tr>
<td '.$class_new.' rowspan="2">'.$i.'</td>
<td '.$class_new.' rowspan="2">'.$users['name'].'</td>
<td '.$class_new.' rowspan="2">'.$qualified_data.'</td>
<td  '.$class_new.' rowspan="2">'.$dvr_data.'</td>
<td '.$class_new.'  rowspan="2">'.$log_call.'</td>
<td '.$class_new.' >No. of Accounts</td>
<td '.$class_new.' >'.$quote.'</td>
<td '.$class_new.' >'.$verification.'</td>
<td '.$class_new.' >'.$commit.'</td>
<td '.$class_new.' >'.$booking.'</td>
<td '.$class_new.' >'.$oem.'</td>
<td '.$class_new.' >'.$total_acc.'</td>
</tr>
<tr>
<td '.$class_new.' >No. of Licenses</td>
<td '.$class_new.' >'.$quote_quantity.'</td>
<td '.$class_new.' >'.$verification_quantity.'</td>
<td '.$class_new.' >'.$commit_quantity.'</td>
<td '.$class_new.' >'.$booking_quantity.'</td>
<td '.$class_new.' >'.$oem_quantity.'</td>
<td '.$class_new.' >'.$total_quantity.'</td>
</tr>';
$i++;}
$mail->Body.='<tr>
<th rowspan="2" colspan="2">Total</th> 
<th rowspan="2">'.$total_qualified.'</th>
<th rowspan="2">'.$total_dvr.'</th>
<th rowspan="2">'.$total_lac.'</th>
<th>No. of Accounts</th>
<th>'.$total_quote.'</th>
<th>'.$total_verification.'</th>
<th>'.$total_commit.'</th>
<th>'.$total_booking.'</th>
<th>'.$total_oem.'</th>
<th>'.$grand_total_acc.'</th>
</tr>
<tr>
<th>No. of Licenses</th>
<th>'.$total_quote_quantity.'</th>
<th>'.$total_verification_quantity.'</th>
<th>'.$total_commit_quantity.'</th>
<th>'.$total_booking_quantity.'</th>
<th>'.$total_oem_quantity.'</th>
<th>'.$grand_total_quantity.'</th>
</tr>';

$mail->Body.='</tbody>
</table><br/><br/>';

$qualified_data=0;
$dvr_data =0;
$log_call =0;
$quote=0;
$verification=0;
$commit=0;
$booking=0;
$oem=0;
$total_acc=0;
$quote_quantity=0;
$verification_quantity=0;
$commit_quantity=0;
$booking_quantity=0;
$oem_quantity=0;
$total_quantity=0;
$total_qualified=0;
$total_dvr=0;
$total_lac=0;
$total_quote=0;
$total_verification=0;
$total_commit=0;
$total_booking=0;
$total_oem=0;
$grand_total_acc=0;
$total_quote_quantity=0;
$total_verification_quantity=0;
$total_commit_quantity=0;
$total_booking_quantity=0;
$total_oem_quantity=0;
$grand_total_quantity=0;


$dat1=date('Y-m-01');
$dat2=date('Y-m-t');
	 
$mail->Body.='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
<thead class="second_table">
<tr>
<td colspan="12"><strong>Monthly Report</strong></td>
</tr>
<tr>
<td colspan="11"><strong>Stage</strong></td>
<td rowspan="3"><strong>Visibility for day</strong></td>
</tr>
<tr>
<td rowspan="2"><strong>Sr. Number</strong></td>
<td rowspan="2"><strong>'.$data['name'].'</strong></td>
<td><strong>Data Received</strong></td>
<td><strong>DVR</strong></td>
<td><strong>Calls</strong></td>
<td rowspan="2">&nbsp;</td>
<td rowspan="2"><strong>Quote</strong></td>
<td rowspan="2"><strong>Follow-Up</strong></td>
<td rowspan="2"><strong>Commit</strong></td>
<td rowspan="2"><strong>Booking</strong></td>
<td rowspan="2"><strong>OEM Billing</strong></td>
</tr>
<tr>
<td><strong>Qualified</strong></td>
<td><strong>Daily Visit Updated</strong></td>
<td><strong>(Log A Calls)</strong></td>
</tr></thead><tbody>';
$users2=db_query("select * from users where team_id='".$data['id']."'");
$i=1;
while($users=db_fetch_array($users2))
{
 $mail->AddCC($users['email']);

//approved
$qualified_data=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."' and status='Approved'");	
$total_qualified+=$qualified_data;
//end//
$dvr_data=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."' and is_dr=1");
$total_dvr+=$dvr_data;
//
$log_call=getSingleresult("SELECT count(*) FROM `activity_log` JOIN orders on orders.id=activity_log.pid where orders.team_id='".$data['id']."' and orders.created_by='".$users['id']."' and date(activity_log.created_date)>='".$dat1."' and date(activity_log.created_date)<='".$dat2."'");
$total_lac+=$log_call;
//
$quote=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Quote' and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."'");
$total_quote+=$quote;
//
$verification=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and (stage='Verification' or stage='Follow-Up') and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."'");
$total_verification+=$verification;
//
$commit=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Commit' and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."'");
$total_commit+=$commit;
//
$booking=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Booking' and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."'");
$total_booking+=$booking;
//
$oem=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='OEM Billing' and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."'");
$total_oem+=$oem;
//

$total_acc=$commit+$booking+$oem;

$grand_total_acc+=$total_acc;

$quote_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Quote' and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."'");
$total_quote_quantity+=$quote_quantity;
//
$verification_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and (stage='Verification' or stage='Follow-Up') and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."'");
$total_verification_quantity+=$verification_quantity;
//
$commit_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Commit' and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."'");
$total_commit_quantity+=$commit_quantity;
//
$booking_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='Booking' and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."'");
$total_booking_quantity+=$booking_quantity;
//
$oem_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and created_by='".$users['id']."' and stage='OEM Billing' and date(created_date)>='".$dat1."' and date(created_date)<='".$dat2."'");
$total_oem_quantity+=$oem_quantity;
//
$total_quantity=$commit_quantity+$booking_quantity+$oem_quantity;
$grand_total_quantity+=$total_quantity;
	if($i%2)
{
	$class_new='class="even"';
}
else
	{
		$class_new='class="odd"';
	}
	
 $mail->Body.='<tr>
<td  '.$class_new.' rowspan="2">'.$i.'</td>
<td '.$class_new.'  rowspan="2">'.$users['name'].'</td>
<td  '.$class_new.' rowspan="2">'.$qualified_data.'</td>
<td '.$class_new.'  rowspan="2">'.$dvr_data.'</td>
<td '.$class_new.'  rowspan="2">'.$log_call.'</td>
<td '.$class_new.' >No. of Accounts</td>
<td '.$class_new.' >'.$quote.'</td>
<td '.$class_new.' >'.$verification.'</td>
<td '.$class_new.' >'.$commit.'</td>
<td '.$class_new.' >'.$booking.'</td>
<td '.$class_new.' >'.$oem.'</td>
<td '.$class_new.' >'.$total_acc.'</td>
</tr>
<tr>
<td '.$class_new.' >No. of Licenses</td>
<td '.$class_new.' >'.$quote_quantity.'</td>
<td '.$class_new.' >'.$verification_quantity.'</td>
<td '.$class_new.' >'.$commit_quantity.'</td>
<td '.$class_new.' >'.$booking_quantity.'</td>
<td '.$class_new.' >'.$oem_quantity.'</td>
<td '.$class_new.' >'.$total_quantity.'</td>
</tr>';
$i++;}
$mail->Body.='<tr>
<th rowspan="2" colspan="2">Total</th> 
<th rowspan="2">'.$total_qualified.'</th>
<th rowspan="2">'.$total_dvr.'</th>
<th rowspan="2">'.$total_lac.'</th>
<th>No. of Accounts</th>
<th>'.$total_quote.'</th>
<th>'.$total_verification.'</th>
<th>'.$total_commit.'</th>
<th>'.$total_booking.'</th>
<th>'.$total_oem.'</th>
<th>'.$grand_total_acc.'</th>
</tr>
<tr>
<th>No. of Licenses</th>
<th>'.$total_quote_quantity.'</th>
<th>'.$total_verification_quantity.'</th>
<th>'.$total_commit_quantity.'</th>
<th>'.$total_booking_quantity.'</th>
<th>'.$total_oem_quantity.'</th>
<th>'.$grand_total_quantity.'</th>
</tr>';

$mail->Body.='</tbody>
</table><br/><br/>';
		 
		$mail->Body .="Thanks,<br>
			Corel DR Portal";
			$mail->Body .='</div>';
			$mail->AddBCC("ankit.aggarwal@arkinfo.in"); 	  
			$mail->AddBCC("deepranshu.srivastava@arkinfo.in", "Deepranshu Srivastava"); 
			$mail->AddCC("nikhil@corelindia.co.in"); 	
			
		    $mail->Send();
			$mail->ClearAllRecipients();
			sleep(2);
			$qualified_data=0;
$dvr_data =0;
$log_call =0;
$quote=0;
$verification=0;
$commit=0;
$booking=0;
$oem=0;
$total_acc=0;
$quote_quantity=0;
$verification_quantity=0;
$commit_quantity=0;
$booking_quantity=0;
$oem_quantity=0;
$total_quantity=0;
$total_qualified=0;
$total_dvr=0;
$total_lac=0;
$total_quote=0;
$total_verification=0;
$total_commit=0;
$total_booking=0;
$total_oem=0;
$grand_total_acc=0;
$total_quote_quantity=0;
$total_verification_quantity=0;
$total_commit_quantity=0;
$total_booking_quantity=0;
$total_oem_quantity=0;
$grand_total_quantity=0;
	
}
?>
 