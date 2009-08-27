<?
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
//Helps export templates.
//The image link will only work if templates are in their default location
include('../../config.inc.php');
include(COMMON_DIR.'mysql.inc.php');//Tom's sql library interface + db connection settings

if(isset($_GET['id']) && is_numeric($_GET['id'])){
  $id = $_GET['id'];
  $sql = "SELECT * FROM template WHERE id = $id LIMIT 1";
  $res = sql_query($sql);
  $t_row = sql_row_keyed($res,0);
  
  $sql = "SELECT field.name, field.style, field.left, field.top, field.width, field.height, type.name as type FROM field LEFT JOIN type ON field.type_id = type.id WHERE template_id = $id";
  $f_res = sql_query($sql);
  
  $fn = str_replace(' ', '_', $t_row['name']) . '.xml';

  header ("content-type: text/xml");
  header ('Content-Disposition: attachment; filename="' . $fn . '"');
  
  echo "<?xml version=\"1.0\"?>\n";
?>
<template>
  <name><?= $t_row['name'] ?></name>
  <width><?= $t_row['width'] ?></width>
  <height><?= $t_row['height'] ?></height>
  <author><?= $t_row['creator'] ?></author>
<? $i=0;
   while($f_row = sql_row_keyed($f_res,$i)){?>
  <field>
    <name><?= $f_row['name'] ?></name>
    <type><?= $f_row['type'] ?></type>
    <style><?= str_replace("\r\n", ' ', $f_row['style']) ?></style>
    <left><?= $f_row['left'] ?></left>
    <top><?= $f_row['top'] ?></top>
    <width><?= $f_row['width'] ?></width>
    <height><?= $f_row['height'] ?></height>
  </field>
<? $i++; } ?>
</template>
<? } else {
  $sql = "SELECT id, name,filename FROM template WHERE hidden = 0";
  $res = sql_query($sql);
?>
<ul>
  <? $i=0;
  while($row = sql_row_keyed($res,$i)){ ?>
  <li><a href="template_exp.php?id=<?= $row['id']?>"><?= $row['name'] ?></a> <a href="<?= ROOT_URL ?>content/templates/<?= $row['filename']?>">[img]</a></li>
<?  $i++; } ?>
</ul>
<? } ?>
