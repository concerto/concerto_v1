<?php
/*
 * image view fetches and sends a resized (or not) image for use in the admin panel
 * essentially taken fron bravochicken's 
 */
include_once(CONTENT_DIR . 'render/render.php');
render('image', $this->file, $this->width, $this->height);
?>
