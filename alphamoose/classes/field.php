<?
/*
Class: Field
Status: New
Functionality:
	BLAH		blah
Comments: 
Untested still
*/

class Field{
	var $id;
	var $name;
	var $template_id;
	var $type_id;
	var $tag;
	var $left;
	var $top;
	var $width;
	var $height;
	
	var $screen_id;
	var $screen_set;
	var $screen_pos;
	
	var $set;
	
	function __contruct($id_in='', $screen_id_in=''){
		if($id_in != ''){
			$sql = "SELECT * FROM field WHERE id = '$id_in' LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$this->id = $data['id'];
				$this->name = $data['name'];
				$this->template_id = $data['template_id'];
				$this->type_id = $data['type_id'];
				$this->tag = $data['tag'];
				$this->left = $data['left'];
				$this->top = $data['top'];
				$this->width = $data['width'];
				$this->height = $data['height'];
				
				$this->set = true;
				
				if($screen_id_in != ''){
					$this->screen_id = $screen_id_in;
					$sql = "SELECT id FROM position WHERE screen_id = $this->screen_id AND field_id = $this->id";
					$res2 = sql_query($sql);
					$i = 0;
					while($pos_row = sql_row_keyed($res2, $i)){
						$pos_id = $pos_row['id'];
						$this->screen_pos[$i] = new Position($pos_id);					
					}
					$this->screen_set = true;
				} else {
					$this->screen_set = false;
				}
			} else {
				return false;
			}
		} else {
			$this->set = false;
			return truel
		}
	
	}

}

?>