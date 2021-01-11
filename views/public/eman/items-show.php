<?php
  echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'items show ' . $bodyColumns));

  if(metadata('item', array('Dublin Core', 'Title'))) {
  	$titres = metadata('item', array('Dublin Core', 'Title'), array('all' => true));
  	foreach ($titres as $i => $titre) {
  		echo "<span id='doc-title' style='font-size:$title_size_items; clear:both;display:block;'>$titre</span>";
  	}
  } else {
  	echo "<span id='doc-title' style='font-size:$title_size_items'>$title_items</span>";
  }

  echo get_specific_plugin_hook_output('Coins', 'public_items_show', array('view' => $this, 'item' => $item));
?>

<?php if (metadata('item', array('Dublin Core', 'Creator'))) : ?>
<?php
  $auteurs = metadata('item', array('Dublin Core', 'Creator'), array('delimiter'=>' ; '));
  strpos($auteurs, ';') ? $label = $this->controller->t('Auteurs') : $label = $this->controller->t('Auteur');
  $label = $this->controller->t($author_name_items);
?>
<br /><span class="dclabel" style="font-size:<?php echo $author_size_items ?>;"><?php echo $label; ?></span><!--  (DC.Creator) --> : <span class="dcvalue" style="font-size:<?php echo $author_size_items ?>;"><?php echo $auteurs;?></span><br />
<?php endif; ?>

<?php echo $collection_link; ?>

<?php
/*
  echo get_specific_plugin_hook_output('UniversalViewer', 'public_items_show', array(
        'record' => $item,
        'view' => $this,
    ));
*/
?>
<?php echo $content; ?>
<span  class="dclabel" style='float:right;clear:right;'><?php echo str_replace('Notice créée par', $this->controller->t('Notice créée par'), get_specific_plugin_hook_output('Bookmarks', 'public_items_show', array('view' => $this, 'item' => $item))); ?></span>
<span  class="dclabel" style='float:right;clear:right;'><?php echo $this->controller->t('Notice créée le') . ' ' . date('d/m/Y', strtotime(metadata('item', 'added'))); ?> </span>
<span  class="dclabel" style='float:right;clear:right;'><?php echo $this->controller->t('Dernière modification le') . ' ' . date('d/m/Y', strtotime(metadata('item', 'modified'))); ?> </span>
<br /><br /><hr />
<?php echo get_specific_plugin_hook_output('Bnfmashup', 'public_items_show', array('view' => $this, 'item' => $item)); ?>

<ul class="item-pagination navigation">

<?php
 // Previous / Next file Pager complet ?
 if (isset($collection)) {

  $collection = get_current_record('collection');

  $maxItems = 25;

  $items = get_records('Item', array('collection' => $collection, 'sort_field' => 'id', 'sort_dir' => 'desc'), 1000);

  $nbItems = count($items);
  if ($nbItems > 1) :
    if ($nbItems > $maxItems) {
  	  // Seach index of current file in all files
  	  $i = 0;
  	  foreach($items as $struct) {
  	  	if ($item->id == $struct->id) {
  	  		$currentItem = $struct;
  	  		break;
  	  	}
  	  	$i++;
  	  }

  		// Slice array to contain only 25 files with current in the middle
  		$i > $maxItems / 2 ? $start = round($i - $maxItems / 2) : $start = 0;

  		$items = array_slice($items, $start, $maxItems);
    }

    $pager = array();
    $i = $current = 0;
    $prec = $suiv = "";

    set_loop_records('item', $items);
    foreach (loop('items') as $loopItem) {
      $classes = "eman-item-link";
      if ($loopItem->id == $item->id) {
      	$classes .= ' eman-item-link-current';
      	$current = $i;
      	if ($i > 0) {
  				$prec = link_to_item('<span style="font-size:32px;float:left;">&loarr;</span> ', array(), 'show', $items[$i-1]);
      	}
      	if ($i < count($items) - 1) {
  				$suiv = link_to_item('<span style="font-size:32px;">&roarr;</span>', array(), 'show', $items[$i+1]);
      	}
      }
      $pager[] = link_to_item(metadata($loopItem, array('Dublin Core', 'Title')), array('class' => $classes), 'show', $items[$i]);
    	$i++;
    }

    $pager = "<div id='eman-pager-items'><h3>" . $this->controller->t('Autres notices de la collection') . "</h3>" . $prec . implode('<div class="eman-items-sep"> | </div>', $pager) . $suiv . "</div>";

    echo get_specific_plugin_hook_output('UserProfiles', 'public_items_show', array('view' => $this, 'item' => $item));
    echo $pager;
  endif;
  ?>
  </ul>
<?php } ?>
<div id='transcripted' style='display:none;'><?php echo $markTranscripted ?></div>
<style>
.suite, .replier {
  cursor: pointer;
  font-style:italic;
  font-weight: bold;
  clear:both;
  display:block;
  float:right;
}
.field-uitemplates-title {
  float:left;
  clear:left;
  display: block;
}
.uit-field, .fieldcontentshort, .fieldcontentcomplet {
  margin-bottom: 1em;
  display:block;
  float:left;
  clear:right;
  padding-left: 2px;
}
li .uit-field {
  padding: 0;
  margin: 0;
}
.uit-field-wrapper {
  display:block;
  overflow: auto;
}
.uit-list,
.uit-list li,
.uit-field-wrapper {
  clear:both;
}

.field-uitemplates {
  clear:both;
  display: block;
  overflow: auto;
}

.field-uitemplates-title::after {
  content:' : ';
  padding-right: 3px;
}
.block-uitemplates {
  overflow:visible;
}

.transcripted img {
  border: 1px solid #ac2f2f;
  border-radius: 5px;
}
</style>

 <script>
 $ = jQuery;

 $(document).ready(function(){
   $('#files-carousel').slick({
//     autoplay: true,
//     autoplaySpeed: 2000,
		dots: true,
		appendDots: '#plugin_gallery > span',
    rows: 4,
    slidesPerRow: 4,
//     lazyLoad: 'progressive',
    pauseOnFocus: true,
  });
  $('.suite').click(function() {
    $(this).parent().parent().find('.fieldcontentcomplet').show();
    $(this).parent().parent().find('.fieldcontentshort').hide();
  });
  $('.replier').click(function() {
    $(this).parent().parent().find('.fieldcontentcomplet').hide();
    $(this).parent().parent().find('.fieldcontentshort').show();
  });
  $('#transcripted > span').each(function (i, e) {
    $('#itemfiles').find('div#' + e.innerHTML).addClass('transcripted');
  });
});
 </script>

<?php echo foot(); ?>
