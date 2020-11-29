<html>
<head>
<meta name="subject" content="Certificate">
<meta name="copyright"content="Created by Ma'shum Abdul Jabbar">
<title>Certificate</title>
</head>
<body>
<style>
table,tr,td{
	width: 100%;
	text-align:center;
}
</style>
<?php 
$total = $tbl_attenders->num_rows();
$x = 0;
foreach($tbl_attenders->result() as $attenders){
$x++;	
if($x<=$total){ ?>
<table>
	<tr>
		<td style="padding-top:250px; font-family:'Arial'; font-size:26pt; font-weight:bold;">
			<?php echo strtoupper($attenders->attenders_name);?>
		</td>
	</tr>
</table>
<?php 
}
if($x<$total){ echo "<pagebreak>"; }
 } ?>
</body>
</html>