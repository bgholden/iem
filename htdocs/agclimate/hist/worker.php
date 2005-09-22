<?php
include("../../../config/settings.inc.php");
include("$rootpath/include/database.inc.php");
 // worker.php
 // Fascilitate download of AgClimate Data
 // Daryl Herzmann 26 Mar 2003
header("Content-type: text/plain");

include("../../include/agclimateLoc.php");

$d = Array("comma" => "," , "tab" => "\t", "space" => " ");

$vardict = Array(
  "c11" => "High Air Temp",
  "c12" => "Low Air Temp",
  "c30" => "Soil Temp",
  "c40" => "Average Wind Vel",
  "c509" => "Max Wind Vel (1 min)",
  "c529" => "Max Wind Vel (5 sec)",
  "c90" => "Daily Precip",
  "c20"  => "Ave RH%",
  "c80"  => "Solar Rad",
  "c70"  => "Potential ET",
  "c100" => "Air Temp",
  "c800" => "Solar Radiation",
  "c900" => "Hourly Precip",
  "c300" => "4 inch Soil Temp",
  "c200" => "RH%",
  "c400" => "Wind Speed",
  "c600" => "Wind Direction"
);

$st   = $_GET['sts'];
$vars = $_GET['vars']; 
$yyyy = $_GET['year'];
$s_mo = $_GET['startMonth'];
$s_dy = $_GET['startDay'];
$s_hr = $_GET['startHour'];
$e_mo = $_GET['endMonth'];
$e_dy = $_GET['endDay'];
$e_hr = $_GET['endHour'];
$qcflg= $_GET['qcflags'];
$delim= $_GET['delim'];
$todisk=$_GET['todisk'];
$cr = $_GET['lf'] == "dos" ? "\r\n" : "\n";

// Error Catching
if (sizeof($st) == 0) die("You did not select a station");
if (sizeof($vars) == 0) die("You did not select a variable");


$rtype = "hourly";
if (strlen($s_hr) == 0)
  $rtype = "daily";
if (strlen($delim) == 0)
  $delim = "tab";

$fvars = Array();
if (strlen($qcflg) > 0){  // They want QC too!
  foreach ($vars as $var){
    $fvars[] = $var;
    $fvars[] = $var ."_f";
    $vardict[$var."_f"] = "QC";
  }
} else {
  $fvars = $vars;
}
$num_vars = sizeof($fvars);

$sts  = mktime($s_hr,0,0, $s_mo, $s_dy, $yyyy);
$ets  = mktime($e_hr,0,0, $e_mo, $e_dy, $yyyy);

if ($sts > $ets) die("Your start time is greater than your end time!");

$tbl = strftime("t%Y", $sts) ."_". $rtype;

$str_stns = implode("','", $st);
$str_vars = implode(",", $fvars);
$str_sts  = strftime("%Y-%m-%d %H:00", $sts);
if ($rtype == 'hourly')
  $str_ets  = strftime("%Y-%m-%d %H:00", $ets + 86400);
else
  $str_ets  = strftime("%Y-%m-%d %H:00", $ets );

$sql = "SELECT station, to_char(valid, 'YYYY-MM-DD HH24:MI') as dvalid, 
   $str_vars from $tbl 
   WHERE station IN ('$str_stns') and
   valid BETWEEN '$str_sts' and '$str_ets'
   ORDER by station, valid";

$c = iemdb("isuag");
$rs = pg_exec($c, $sql);
pg_close($c);

if (strlen($todisk) > 0) {
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=changeme.txt");
}


echo "# ISU Ag Climate Download -- Iowa Environmental Mesonet $cr";
echo "# For units and more information: $cr";
echo "#    http://mesonet.agron.iastate.edu/agclimate/info.txt $cr";
echo "# Data Contact: $cr";
echo "#    Daryl Herzmann akrherz@iastate.edu 515.294.5978 $cr";


echo "Site ID" . $d[$delim] ."Site Name". $d[$delim] . "valid".  $d[$delim];
for ($j=0; $j < $num_vars;$j++){
  echo $vardict[$fvars[$j]]. $d[$delim];
}
echo $cr;


for( $i=0; $row = @pg_fetch_array($rs,$i); $i++) {
  echo $row["station"] . $d[$delim] . $ISUAGcities[$row["station"]]['city'] 
    . $d[$delim] . $row["dvalid"] . $d[$delim];
  for ($j=0; $j < $num_vars;$j++){
    echo $row[$fvars[$j]]. $d[$delim];
  }
  echo $cr;
}
?>

