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
 * @author       Web Technologies Group, $Author: zaik $
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision: 543 $
 */
?>
<div id="topbar">
		<h1>ConcertoWALL</h1>
		<p>Unfettered browsing for the common folk.</p>
</div>
<div id="prevbar">&nbsp;</div>
<div id="nextbar">&nbsp;</div>
<div id="wallthumbs">
		<?php
				$jsondata = file_get_contents("http://senatedev.union.rpi.edu/zaikb/conc19/admin/includes/feedjson.php", 'r');
				$feeddata = json_decode($jsondata);
				$count = 0;
				foreach ($feeddata as $obj) { 
						$feed_data = $obj->{'feed'};
						$feed_name = rawurlencode($feed_data[0]->{'name'});
		?>
							  <div class="UIWall_thumb"><a class="overlayTrigger" href="<?= ADMIN_URL ?>/wall/ext?content_id=<?= $obj->{'id'} ?>&amp;feed_name=<?= $feed_name ?>" rel="#oz"><div class="UIWall_wrapper"><img src="<?= $obj->{'content'} ?>" alt="" /></div></a></div>
		  
		<?php
		}
?>
</div>
<div id="oz" class="overlayZoom">
		<div id="wrap"></div>
</div>
