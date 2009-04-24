function removePos(field, feed, feed_name) {
   var e=document.getElementById('pos_'+field+'_'+feed);
   e.parentNode.removeChild(e);
   var sel = document.getElementById('add_'+field);
   var opt = document.createElement('option');
   opt.text = feed_name;
   opt.value = feed;
   try {
      sel.add(opt,null);
   } catch(ex) {
      sel.add(opt); //for IE?
   }
}

function addPos(field, url) {
   var e=document.createElement('li');
   var add=document.getElementById('add_'+field);
   var feed=add.options[add.selectedIndex];
   e.setAttribute('id','pos_'+field+'_'+feed.value);

   e.innerHTML=
'<select name="content[freq]['+field+']['+feed.value+']">'+
'<option value="0">Never</option><option value=".33">Sometimes</option><option value=".66" SELECTED>Moderately</option><option value="1.00">Very Often</option>'+
'</select> display content from <a href="/admin/feeds/show/'+feed.value+'">'+feed.text+'</a> '+
'(<a href="#" onclick="removePos('+field+','+feed.value+',\''+feed.text+'\'); return false;">remove</a>)';

   add.parentNode.parentNode.getElementsByTagName('ul')[0].appendChild(e);   

   add.options[add.selectedIndex]=null;
}