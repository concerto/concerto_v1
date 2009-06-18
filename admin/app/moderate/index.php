<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
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
?><h2>Click on the feed to start moderating.</h2>
<?php
if(isset($this->feeds)){
    foreach($this->feeds as $feed_id => $feed){
        echo "<h3>{$feed->name}</h3>\n"; 
        if($this->count[$feed->id]==0){
            echo "No items awaiting moderation";
        } else {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".ADMIN_URL."/moderate/feed/{$feed->id}\"><span class='emph'>{$this->count[$feed->id]}</span> items awaiting moderation</a>\n";
        }
    }
} else {
    echo "No feeds are awaiting moderation.";
}
?>
<br /><br /><br /><br /><br />
