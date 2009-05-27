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
?><h2>Welcome to Concerto!</h2>
<h3>Please enter some information about yourself to get started.</h3>
<form method="POST" action="<?=ADMIN_URL?>/users/create">
<?php
   include('_form.php');
?>
<tr>
	<td>
		<h3>Concerto User Agreement</h3>
	</td>
	<td>
		<p>All content submitted to Concerto remains the property of the person or organization that uploaded it.</p>
		<p>All users, by creating accounts and uploading content, certify that they have proper rights (or permission) to any wording or imagery utilized. Further, they certify that their content does not blatantly violate community standards of decency or vulgarity. Decisions made by moderators regarding content approval are final.</p>
		<p>The content of the Concerto system does not represent the policies or opinions of the Rensselaer Union or Rensselaer Polytechnic Institute.</p>
		<p>By clicking on the Submit button below, you agree to the terms of the above user agreement.</p>
	</td>
</tr>

<input type="submit" />
</form>
