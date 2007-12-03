<?php


function init_db( ) {    
    mysql_pconnect("studentsenate.rpi.edu", "signage", "216719")
        or die("MySQL connect failed! error = " . mysql_error( ));
    mysql_select_db("signage_hardware")
        or die("MySQL select database failed! error = " . mysql_error( ));
}

function get_new_hardware_list( ) {
    $output = array( );
    ($result = mysql_query("select mac from new_hardware limit 100"))
        or die("MySQL query failed! error = " . mysql_error( ));

    while ($row = mysql_fetch_array($result)) {
        array_push($output, $row[0] /* mac address */);
    }

    return $output;
    
}



?>

