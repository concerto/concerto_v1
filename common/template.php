<?
/*
Class: Template
Status: Fresh
Functionality:
	set_properties		Writes all data back the the screen table
Comments:
	Not intended for production use yet, just a start
*/
class Template{
     var $id;     var $name;
     var $filename;
     var $set;
	 
	
     function __construct($tid = ''){
          if($tid != ''){
               $sql = "SELECT * FROM template WHERE id = $tid LIMIT 1";
               $res = sql_query($sql);
               if($res != 0){                    $data = (sql_row_keyed($res,0));
                    $this->id = $data['id'];
                    $this->name = $data['name'];
                    $this->filename = $data['filename'];
                    
                    $this->set = true;
                    return true;
               } else {
                    $this->set = false;
                    return false;
               }
          } else {
               $set = false;
               return true;
          }
     }
     
     function set_properties(){
          $name_clean = escape($this->name);
          $filename_clean = escape($this->filename);

          $sql = "UPDATE template SET name = '$name_clean', filename = '$filename_clean' WHERE id = $this->id LIMIT 1";
          $res = sql_query($sql);
		if($res){
			return true;
		} else {
			return false;
		}
     }

     function list_fields(){
          $sql = "SELECT * FROM field WHERE template_id = $this->id";
          $res = sql_query($sql);
		$i=0;
		while($row = sql_row_keyed($res,$i)){
		     $data[$i]['id'] = $row['id'];
			$data[$i]['name'] = $row['name'];
			$data[$i]['type_id'] = $row['type_id'];
			$data[$i]['style'] = $row['style'];
               $data[$i]['left'] = $row['left'];
               $data[$i]['top'] = $row['top'];
               $data[$i]['width'] = $row['width'];
               $data[$i]['height'] = $row['height'];
		     $i++;
		}
		return $data;
     }

     function update_field($field_id_in, $name_in, $type_id_in, $style_in, $left_in, $top_in, $width_in, $height_in){
          //Spend some time cleaning things up
          if(!is_numeric($field_id_in)){
				return false;
		}
          if(!is_numeric($type_id_in)){
				return false;
		}
          if(!is_numeric($left_in)){
				return false;
		}
          if(!is_numeric($top_in)){
				return false;
		}
          if(!is_numeric($width_in)){
				return false;
		}
          if(!is_numeric($height_in)){
				return false;
		}
          $name_cleaned = escape($name_in);
          $style_cleaned = escape($style_in);

          $sql = "UPDATE `field` SET name = '$name_cleaned', type_id = $type_id_in, style = '$style_cleaned', `left` = $left_in, `top` = $top_in, `width` = $width_in, `height` = $height_in WHERE id = $field_id_in AND template_id = $this->id";
          echo $sql;          
          $res = sql_query($sql);
		if($res){
			return true;
		} else {
			return false;
		}

     }

     function add_field($name_in, $type_id_in, $style_in, $left_in, $top_in, $width_in, $height_in){
          //Spend some time cleaning things up
          if(!is_numeric($type_id_in)){
				return false;
		}
          if(!is_numeric($left_in)){
				return false;
		}
          if(!is_numeric($top_in)){
				return false;
		}
          if(!is_numeric($width_in)){
				return false;
		}
          if(!is_numeric($height_in)){
				return false;
		}
          $name_cleaned = escape($name_in);
          $style_cleaned = escape($style_in);

          $sql = "INSERT INTO `field` (name, template_id, style, type_id, `left`, `top`, `width`, `height`) VALUES ('$name_cleaned', $this->id, '$style_cleaned', $type_id_in, $left_in, $top_in, $width_in, $height_in)";
          $res = sql_query($sql);
          echo $sql;
		if($res){
			return true;
		} else {
			return false;
		}

     }

     function delete_field($field_id){
          $sql = "DELETE FROM field WHERE id = $field_id AND template_id = $this->id LIMIT 1";
          mysql_query($sql);
          return true;
     }

     function destroy(){
          $sql = "DELETE FROM field WHERE template_id = $this->id";
          mysql_query($sql);
          
          //Remove the template file
          $path = TEMPLATE_DIR . $this->filename;          
          unlink($path);

          $sql1 = "DELETE FROM template WHERE id = $this->id";
          mysql_query($sql1);

          return true;     
     }
}
?>


