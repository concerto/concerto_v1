<a href="<?=ADMIN_URL.'/page_categories/edit/'.$this->category['id'] ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Edit Category</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a> 
<a href="<?=ADMIN_URL.'/page_categories/delete/'.$this->category['id'] ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Delete Category</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>

<h3>Category Name: <span class="emph"><?= $this->category['name'] ?></span></h3>
<h3>Path: <span class="emph"><a href="<?=ADMIN_URL.'/pages/show/'.$this->category['path']?>"><?= $this->category['path'] ?></a></span></h3>
<h3>Layout: <span class="emph"><?=$this->category['layout']?></span></h3>
<h3>Default Page: <span class="emph"><a href="<?=ADMIN_URL.'/pages/show/'.$this->category['path']?>"><?= $this->category['default_page_name'] ?></a></span></h3>
<h3>Pages: <span class="emph"><?= $this->count ?></span> <a href="<?=ADMIN_URL.'/pages'?>">(see listing of pages)</a></h3>
