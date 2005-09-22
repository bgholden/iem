<?php 
include("../../config/settings.inc.php");
include('switchtv.php');
  $page = 'snet';
  $TITLE = "IEM | School Network";
include("$rootpath/include/header.php"); 
?>

<h3 class="heading">School Network</h3>

<div class="text">
<table><tr><td style="width: 620px;" valign="top">
<p>As the name implies, these automated weather stations are
located at schools throughout the state.  Currently, 
<a href="http://www.theiowachannel.com/">KCCI-TV</a> (Des Moines, IA),
<a href="http://www.keloland.com">KELO-TV</a> (Sioux Falls, SD), and <a href="http://www.kimt.com">KIMT-TV</a> (Mason City, IA) have 
graciously provided
the IEM with the ability to process data from their observing networks.  
We are pursuing other TV stations, for their networks as well.</p></td><td>

<div class="ninfo">
<b><u>School Network</u></b>
<br>Sampled: 3x / hour
<br>Reports: Continuously
<br>Stations: 66 (KCCI)
<br>Stations: 39 (KELO)
<br />Stations: 8 (KIMT)
<br><a href="/info/network.phtml?network=KCCI">KCCI Locations</a>
<br><a href="/info/network.phtml?network=KELO">KELO Locations</a>
<br><a href="/info/network.phtml?network=KIMT">KIMT Locations</a>
</div></td></tr></table>

<!-- Begin TV tabs -->

<?php include('switchbar.php'); ?>

<?php
  $link = "index.php";
  if ($tv == 'KCCI'){
    include('KCCI.php');
  }
  else if ($tv == 'KELO'){
    include('KELO.php');
  }
  else if ($tv == 'KIMT'){
    include('KIMT.php');
  }
?>
</td></tr></table>
</td></tr></table>
</td></tr></table>

<p>Many of the school net stations are not located in good
meteorological locations.  While the stations may be accurate, their data
may not be representative of the area in general.
Often, they are placed on top of buildings and may
have obstructions which could skew wind and temperature readings.  The
stations are placed at schools for educational purposes and to get students
interested in the weather.</p></div>


<?php include("$rootpath/include/footer.php"); ?>
