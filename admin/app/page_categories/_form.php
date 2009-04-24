<!-- Beginning Category Form -->
<?php
   //assuming $this->category is null or the category we want to edit
   $cat = $this->category;
?>
     <table style="clear:none" class='edit_win' cellpadding='6' cellspacing='0'>
       <tr> 
         <td class='firstrow'><h5>Category Name</h5></td>
         <td class='edit_col firstrow'>
           <input type="text" id="name" name="category[name]" value="<?=$cat['name']?>">
         </td>
       </tr>
       <tr> 
         <td class='firstrow'><h5>Path</h5><p>Letters, numbers, and hyphens only please.  No special characters or spaces.  Keep it short.</td>
         <td class='edit_col firstrow'>
           <input type="text" id="name" name="category[path]" value="<?=$cat['path']?>">
         </td>
       </tr>
       <tr> 
         <td class='firstrow'><h5>Layout</h5><p>Should be a filesystem path to a valid Concerto php template from the admin directory.</td>
         <td class='edit_col firstrow'>
           <input type="text" id="layout" name="category[layout]" value="<?=$cat['layout']?>">
         </td>
       </tr>
<?php
if(is_numeric($cat['id'])) {
?>
       <tr>
         <td><h5>Default Page</h5></td>
         <td><select name="category[default_page]">
                <option value=""<?php if(!isset($cat['default_page'])) echo ' SELECTED'; ?>></option>
<?php
   $pages = sql_select('page',array('id','name'),'page_category_id = '.$cat['id']);
   if(is_array($pages)) {
      foreach($pages as $page) {
?>
                <option value="<?= $page['id'] ?>"<?php if($cat['default_page']==$page['id']) echo ' SELECTED'; ?>><?=$page['name']?></option>
<?php
      }
   }
?>
             </select></td>
       </tr>
<?php
}
?>
     </table>
     <br clear="all" />
<!-- End Category Form -->
