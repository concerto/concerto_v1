<h2><?= $this->feed->name ?></h2>
<ul>
<? foreach($this->feed->get_types() as $type_id => $type){ ?>
<li><a href="<?= ADMIN_URL ?>/browse/show/<?= $this->feed->id ?>/type/<?= $type_id ?><?= isset($this->args[2]) ? "/{$this->args[2]}" : "" ?>"><?= $type ?></a></li>
<? } ?>
</ul>
<a href="<?= ADMIN_URL ?>/browse/">Back to Feed Listings</a>