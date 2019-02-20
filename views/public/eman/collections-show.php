<?php
echo head(array('title' => metadata('collection', array('Dublin Core', 'Title')),'bodyclass' => 'items show')); ?>

<?php 
  // Arbre Parent : vide si collection m�re
  $childCollection = get_db()->getTable('CollectionTree')->getAncestorTree($collectionId);  
	$parentId = get_db()->getTable('CollectionTree')->getParentCollection($collectionId);
	if ($parentId <> 0) {
		$parentCollection = get_record_by_id('Collection', $parentId);
		$parentName = metadata($parentCollection, array('Dublin Core', 'Title'));		
	}
?>	

<h1><?php 
		if(metadata('collection', array('Dublin Core', 'Title'))) {
				$titres = metadata('collection', array('Dublin Core', 'Title'), array('all' => true));
				foreach ($titres as $i => $titre) {
					echo "<h1>$titre</h1>";
				}
		} else {
				echo $title; 
		}?>
</h1>
<?php
    $auteur = metadata('collection', array('Dublin Core', 'Creator'));
    // Nom du jpeg = nom de l'auteur en minuscule sans espace
    $image = strtolower(strstr($auteur, ',', true)) . '.jpg';
    $linkCollection = "collections/show/" . metadata($collection, 'id');
    $path = FILES_DIR . "/original/$image";
    if (file_exists($path)) {
      $path = WEB_ROOT . "/files/original/$image";
      echo "<img class='eman-auteur' style='display:blockhright:100px;max-width:100px;float:right;clear:right;margin:10px;' src='" . $path . "' />";      
    }
    echo "<br />";
?>
<?php $creator = metadata('collection', array('Dublin Core', 'Creator'), array ('delimiter' => ' ; '));
  
  if ($creator) { ?>
    <span  class="dclabel">Auteur<!--  (DC.Creator) --> : <?php echo $creator;?></span><br />    
  <?php } ?>
<?php if ($childCollection) { ?>
	Collection parente : <a href="<?php echo WEB_ROOT; ?>/collections/show/<?php echo $parentId; ?>"><?php echo $parentName; ?></a> 
<?php } else { ?>
	<a href="<?php echo WEB_ROOT; ?>">Revenir &agrave; l'accueil</a> 
<?php } ?>

<?php echo get_specific_plugin_hook_output('Coins', 'public_collections_show', array('view' => $this, 'collection' => $collection));?>

<?php if (metadata('collection', array('Dublin Core', 'Creator'))) :?> 

<?php  endif; ?>	
	
<?php echo $content; ?>
<span class="dclabel" style="clear:both;display:block;float:right;">Collection cr&eacute;&eacute;e le <?php echo date('d/m/Y', strtotime(metadata('collection', 'added'))); ?> </span>



<?php echo foot(); ?>