<table width="100%" border=1 cellspacing=0 cellpadding=1>
<tr>
 <th colspan="7" bgcolor="#CCCCCC">Select Network</th></tr>
<tr>
<?php
  $d = Array(
    "ASOS" => Array("url" => "$rooturl/ASOS/current.phtml"),
    "AWOS" => Array("url" => "$rooturl/AWOS/current.phtml"),
    "COOP" => Array("url" => "$rooturl/COOP/current.phtml"),
    "DCP" => Array("url" => "$rooturl/DCP/current.phtml"),
    "SchoolNet" => Array("url" => "$rooturl/schoolnet/current.phtml"),
    "OT" => Array("url" => "$rooturl/other/current.phtml"),
    "RWIS" => Array("url" => "$rooturl/RWIS/current.phtml"),
    "RWIS_SF" => Array("url" => "$rooturl/RWIS/currentSF.phtml"),
    "SCAN" => Array("url" => "$rooturl/scan/current.phtml"),
    "My Favorites" => Array("url" => "$rooturl/my/current.phtml"),
    "Road Conditions" => Array("url" => "$rooturl/current/rc.phtml"),
    "All" => Array("url" => "$rooturl/current/all.phtml") );
  
  $i = 0;
  while ( list($key, $value) = each($d) ){
    echo "<th ";
    if ($current_network == $key) 
       echo "bgcolor=\"#666666\"><font color=\"white\">$key</font></th>";
    else 
       echo "><a href=\"". $value["url"] ."\">$key</a></th>";
    if ($i == 5) echo "</tr><tr>";
    $i++;
  }
?>

</tr></table>
