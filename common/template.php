<?
/*
Class: Template
Status: Working
Functionality:
	set_properties		Writes all data back the the template table
	list_fields		Lists all the fields belonging to a template
	update_field		Updates the properties for a field
	add_field		Creates a new field on a template
	list_all		Lists all the templates matching a WHERE syntax
	get_all		Get all the templates matching a WHERE syntax
	delete_field		Deletes a field from a template.  Does not handle the actual positions
	destroy		Deletes a template and all of its fields.  Does not handle screens using the template
	preview		Renders a preview of a template
Comments:
	We forgot about this one.  Revived from the dead.
*/
class Template{
     var $id;
     var $name;
     var $filename;
     var $height;
     var $width;
     var $creator;
     var $modified;
     var $hidden;
     
     var $aspect_ratio;

     var $set;

     function __construct($tid = ''){
          if($tid != '' && is_numeric($tid)){
               $sql = "SELECT * FROM template WHERE id = $tid LIMIT 1";
               $res = sql_query($sql);
               if($res != 0){
                    $data = (sql_row_keyed($res,0));
                    $this->id = $data['id'];
                    $this->name = $data['name'];
                    $this->filename = $data['filename'];
                    $this->height = $data['height'];
                    $this->width = $data['width'];
                    $this->creator = $data['creator'];
                    $this->modified = $data['modified'];
                    $this->hidden = $data['hidden'];
                    
                    $this->aspect_ratio = $this->width / $this->height;
                    
                    $this->set = true;
                    return true;
               } else {
                    $this->set = false;
                    return false;
               }
          } else {
               $this->set = false;
               return true;
          }
     }
     
     function set_properties(){
          $name_clean = escape($this->name);
          $filename_clean = escape($this->filename);
          $creator_clean = escape($this->creator);
          if(!is_numeric($this->width) || !is_numeric($this->height) || !is_bool($this->hidden)){
            return false;
          }
          $sql = "UPDATE template SET name = '$name_clean', filename = '$filename_clean', height = $height, width  = $width, creator = '$creator_clean', modified = NOW(), hidden = $this->hidden WHERE id = $this->id LIMIT 1";
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
          if(!is_numeric($field_id_in)|| !is_numeric($type_id_in) || !is_numeric($left_in) || !is_numeric($top_in) ||!is_numeric($width_in) || !is_numeric($height_in)){
            return false;
          }
          $name_cleaned = escape($name_in);
          $style_cleaned = escape($style_in);

          $sql = "UPDATE `field` SET name = '$name_cleaned', type_id = $type_id_in, style = '$style_cleaned', `left` = $left_in, `top` = $top_in, `width` = $width_in, `height` = $height_in WHERE id = $field_id_in AND template_id = $this->id";
          $res = sql_query($sql);
          if($res){
            return true;
          } else {
            return false;
          }
     }

     function add_field($name_in, $type_id_in, $style_in, $left_in, $top_in, $width_in, $height_in){
          //Spend some time cleaning things up
          if(!is_numeric($type_id_in) || !is_numeric($left_in) || !is_numeric($top_in) ||!is_numeric($width_in) || !is_numeric($height_in)){
            return false;
          }
          $name_cleaned = escape($name_in);
          $style_cleaned = escape($style_in);

          $sql = "INSERT INTO `field` (name, template_id, style, type_id, `left`, `top`, `width`, `height`) VALUES ('$name_cleaned', $this->id, '$style_cleaned', $type_id_in, $left_in, $top_in, $width_in, $height_in)";
          $res = sql_query($sql);

          if($res){
            return true;
          } else {
            return false;
          }

     }
     //List all templates, optional WHERE syntax
     function list_all($where = ''){
          $sql = "SELECT * FROM template $where";
          $res = sql_query($sql);
          $i=0;
          while($row = sql_row_keyed($res,$i)){
               $data[$i]['id'] = $row['id'];
               $data[$i]['name'] = $row['name'];
               $data[$i]['filename'] = $row['filename'];
               $data[$i]['height'] = $row['height'];
               $data[$i]['width'] = $row['width'];
               $i++;
          }
          return $data;
     }

     function get_all($where = ''){
          $sql = "SELECT id FROM template $where";
          $res = sql_query($sql);
          $i=0;
          $found = false;
          while($row = sql_row_keyed($res,$i)){
               $found = true;
               $data[] = new Template($row['id']);
               $i++;
          }
          if($found){
               return $data;
          } else {
            return false;
          }
     }

     function delete_field($field_id){
          if(!is_numeric($field_id)){
            return false;
          }
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

          $sql1 = "DELETE FROM template WHERE id = $this->id LIMIT 1";
          mysql_query($sql1);

          return true;
     }
     /*
      * creates a thumbnail of a screen with overlays
      */
     function preview($width=400, $height=300, $act_field = false){
      if(!isset($this->filename)){
        $new_image = imagecreatetruecolor(100, 100);
        die;
      }else{
        $new_width = $width;
        $new_height = $height;

        list($width, $height) = getimagesize(TEMPLATE_DIR.$this->filename);

        if(!isset($new_width) || !isset($new_height)){
          $new_width = $width;
          $new_height = $height;
        }

      $ratio = $width / $height;
      $new_ratio = $new_width / $new_height;

      if($ratio < $new_ratio) {
        $new_height = $new_height;
        $new_width = $new_height * $ratio;
      } else {
        $new_width = $new_width;
        $new_height = $new_width / $ratio;
      }
      $new_image = imagecreatetruecolor($new_width, $new_height);
      $image = imagecreatefromjpeg(TEMPLATE_DIR.$this->filename);

      imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

      $box_color=imagecolorallocatealpha($new_image, 100, 100, 100, 64);
      $box_color2=imagecolorallocatealpha($new_image, 75, 75, 75, 40);
      $text_color=imagecolorallocate($new_image, 255,255,255);
      $font_size=$new_height/14;
      $font=COMMON_DIR.'FreeSans.ttf';

      foreach ($this->list_fields() as $field) {
        //echo $field['id'].'.'.$act_field.' ';
        if($field['id']==$act_field) {
            imagefilledrectangle($new_image,$new_width*$field['left'],$new_height*$field['top'],
              $new_width*($field['left']+$field['width']),$new_height*($field['top']+$field['height']),$box_color2);
            imagerectangle($new_image,$new_width*$field['left'],$new_height*$field['top'],
              $new_width*($field['left']+$field['width']),$new_height*($field['top']+$field['height']),$text_color);
        } else{
          imagefilledrectangle($new_image,$new_width*$field['left'],$new_height*$field['top'],
            $new_width*($field['left']+$field['width']),$new_height*($field['top']+$field['height']),$box_color);
        }
        $tbox = imageTTFBBox ($font_size,0,$font,$field['name']);
        imageTTFText($new_image,$font_size,0,
          $new_width*($field['left']+$field['width']/2)-($tbox[2]-$tbox[0])/2,
          $new_height*($field['top']+$field['height']/2)-($tbox[5]-$tbox[1])/2,
          $text_color,$font,$field['name']);

        $theight = $tbox[1];
        $twidth= $tbox[2];
      }
    }
    $offset = 60*30; // half an hour
    header('Content-type: image/jpeg');
    header('Cache-Control: public, must-revalidate');
    header('Expires: '. gmdate('D, d M Y H:i:s', time() + $offset) .' GMT');
    header('Pragma: cache');

    imagejpeg($new_image, NULL, 100);
    imagedestroy($new_image);
    imagedestroy($image);
    exit();
     }
}
?>
