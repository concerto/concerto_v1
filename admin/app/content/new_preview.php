<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
?><div>
<?
$dat = $_REQUEST;
$feed = new Feed($dat['feed_id']);
if(!$feed->set){
    echo "Error previewing content on that feed.";
} else {
    $dynamic = $feed->dyn;
    $start=$dat['start_date'].' '.$dat['start_time_hr'].':'.$dat['start_time_min'].' '.$dat['start_time_ampm'];
    $end=$dat['end_date'].' '.$dat['end_time_hr'].':'.$dat['end_time_min'].' '.$dat['end_time_ampm'];
    $preview = $dynamic->preview($_REQUEST['name'], $_SESSION['user']->id, $_REQUEST['content'], $start, $end, date('Y-m-d H:i:s'));
    if(!$preview){
        echo "Preview generation failed.";
    }else{
        echo $preview;
    }
}
?>
</div>
