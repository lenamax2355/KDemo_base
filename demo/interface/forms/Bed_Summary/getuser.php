<?php include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
include_once("$srcdir/encounter.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/erx_javascript.inc.php");
?>
<html>
<head>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 1px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
$q = intval($_GET['q']);

/*$con = mysqli_connect('localhost','root','','mangoo');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
$wards="wards";
mysqli_select_db($con,"mangoo");
$sql="SELECT * FROM list_options WHERE list_id='".General."' AND is_default = '".$q."'";
$result = sqlStatement($con,$sql);*/
$sql="SELECT * FROM list_options WHERE list_id='".General."' AND is_default = '".$q."'";
if ($q=='1')
{
//$sql="SELECT * FROM list_options WHERE list_id='".General."' AND is_default = '".$q."'";
echo "<table>
<tr>
<th>Patient Name</th>
<th>Patient ID</th>
<th>Visit ID</th>
<th>Ward</th>
<th>Bed</th>

<th>Admit Date</th>
<th>Diagnosis</th>


</tr>";
    $adm='discharge';
	$sql2="select a.diagnosis,b.pid,a.admit_to_bed,a.admit_to_ward,a.encounter,concat(b.fname,' ',b.lname) name,a.admit_date,b.genericname1 from t_form_admit a,patient_data b where a.status='".$adm."' and a.pid=b.pid";
	$sql3="select * from t_form_admit  where status='".$adm."'";
	$result2=sqlStatement($sql2);

	while($row2=sqlFetchArray($result2))
	{
	
	
    echo "<tr>";
	echo "<td>" . $row2['name'] . "</td>";
	echo "<td>" . $row2['genericname1'] . "</td>";
	echo "<td>" . $row2['encounter'] . "</td>";
	echo "<td>" . $row2['admit_to_ward'] . "</td>";
    echo "<td>" . $row2['admit_to_bed'] . "</td>";
    echo "<td>".$row2['admit_date']."</td>";
	echo "<td>".$row2['diagnosis']."</td>";
    
	
}
echo "</table>";
}
else if($q=='0')
{
echo "<table>";
echo "<tr>
<th>Patient Name</th>
<th>Patient ID</th>
<th>Visit ID</th>
<th>Ward</th>
<th>Bed</th>
<th>Admit Date</th>
</tr>";

    $adm='admit';
	$sql2="select a.pid,a.admit_to_bed,a.admit_to_ward,a.encounter,concat(b.fname,' ',b.lname) name,a.admit_date,b.genericname1 from t_form_admit a,patient_data b where a.status='".$adm."' and a.pid=b.pid order by a.admit_to_ward ";
	$result2=sqlStatement($sql2);
	
while($row2=sqlFetchArray($result2))
	{
	$rdm=$row2['admit_to_ward'];
	$rdm2=$row2['admit_to_bed'];
	$encounter=$row2['encounter'];
	$dur = abs(strtotime($row2['discharge_date']) - strtotime($row2['admit_date']));
	$years = floor($dur / (365*60*60*24));
	$months = floor(($dur - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($dur - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	echo  "<input type=\"hidden\" name=\"admit_to_ward\" value=\"$rdm\" readonly></input>";
    echo "<input type=\"hidden\" name=\"admit_to_bed\" value=\"$rdm2\" readonly></input>";
    echo "<tr>";
	echo "<td>" . $row2['name'] . "</td>";
	echo "<td>" . $row2['genericname1'] . "</td>";
	echo "<td>" . $row2['encounter'] . "</td>";
	//echo "<td>" . "" . "</td>";
	echo "<td>" . $row2['admit_to_ward'] . "</td>";
    echo "<td>" . $row2['admit_to_bed'] . "</td>";
    echo "<td>".$row2['admit_date']."</td>";
    //echo "<td>".$row2['discharge_date']."</td>"; 
	//echo "<td>".$days." days"."</td>";
	//echo "<td>".$row2['diagnosis']."</td> </tr>";
   // echo "<td>" ."<input type=\"checkbox\" name=\"checkbox[]\" id=\"checkbox[]\" value=\"$encounter\"></input>"."</td></tr>";
	
	}

echo "</table>";
}
else if($q=='3')
{

echo "<table>
<tr>
<th>Patient</th>
<th>Encounter</th>
<th>Department</th>
<th>Ward</th>
<th>Bed</th>

<th>Admit Date</th>
<th>Discharge Date</th>
<th>Duration Of Stay</th>
<th>Diagnosis</th>
<th></th>
</tr>";
    $adm='discharge';
	$sql2="select * from t_form_admit where status='".$adm."'";
	$result2=sqlStatement($sql2);

	while($row2=sqlFetchArray($result2))
	{
	
	$e=$row2['encounter'];
	echo "<input type=\"hidden\" name=\"e\" value=\"$e\"></input>";

	$rdm=$row2['admit_to_ward'];
	$rdm2=$row2['admit_to_bed'];
	$dur = abs(strtotime($row2['discharge_date']) - strtotime($row2['admit_date']));
	$years = floor($dur / (365*60*60*24));
	$months = floor(($dur - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($dur - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	echo  "<input type=\"hidden\" name=\"admit_to_ward\" value=\"$rdm\" readonly></input>";
    echo "<input type=\"hidden\" name=\"admit_to_bed\" value=\"$rdm2\" readonly></input>";
    echo "<tr>";
	echo "<td>" . $row2['client_name'] . "</td>";
	echo "<td>" . $row2['encounter'] . "</td>";
	echo "<td>" . "" . "</td>";
	echo "<td>" . $row2['admit_to_ward'] . "</td>";
    echo "<td>" . $row2['admit_to_bed'] . "</td>";
    echo "<td>".$row2['admit_date']."</td>";
    echo "<td>".$row2['discharge_date']."</td>"; 
	echo "<td>".$days." days"."</td>";
	echo "<td>".$row2['diagnosis']."</td>";
	echo "<td>" ."<input type=\"submit\" name=\"fd\" value=\"Final Discharge\"></input>"."</td></tr>";
	}
	
	
echo "</table>";
echo "<input type=\"hidden\" name=\"fd\" value=\"Final Discharge\"></input>";

}
else if($q=='2')
{

echo "<table>
<tr>
<th>Patient</th>
<th>Encounter</th>
<th>Department</th>
<th>Ward</th>
<th>Bed</th>

<th>Admit Date</th>
<th>Planned Discharge Date</th>
<th>Duration Of Stay</th>
<th>Diagnosis</th>
<th></th>
</tr>";
    $adm='admit';
	$sql2="select * from t_form_admit where status='".$adm."'";
	$result2=sqlStatement($sql2);

	while($row2=sqlFetchArray($result2))
	{
	
	$rdm=$row2['admit_to_ward'];
	$rdm2=$row2['admit_to_bed'];
		echo  "<input type=\"hidden\" name=\"admit_to_ward\" value=\"$rdm\" readonly></input>";
       echo "<input type=\"hidden\" name=\"admit_to_bed\" value=\"$rdm2\" readonly></input>";
	$dur = abs(strtotime($row2['discharge_date']) - strtotime($row2['admit_date']));
	$years = floor($dur / (365*60*60*24));
	$months = floor(($dur - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($dur - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	$e=$row2['encounter'];
	echo "<input type=\"hidden\" name=\"e\" value=\"$e\"></input>";

    echo "<tr>";
	echo "<td>" . $row2['client_name'] . "</td>";
	echo "<td>" . $row2['encounter'] . "</td>";
	echo "<td>" . "" . "</td>";
	echo "<td>" . $row2['admit_to_ward'] . "</td>";
    echo "<td>" . $row2['admit_to_bed'] . "</td>";
    echo "<td>".$row2['admit_date']."</td>";
    echo "<td>".$row2['discharge_date']."</td>"; 
	echo "<td>".$days." days"."</td>";
	echo "<td>".$row2['diagnosis']."</td>";
	echo "<td>" ."<input type=\"submit\" name=\"fd\" value=\"Final Admit\"></input>"."</td></tr>";
	}
	
	
echo "</table>";
echo "<input type=\"hidden\" name=\"fd\" value=\"Final Admit\"></input>";


}

?>

</body>
</html>