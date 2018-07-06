<?php
defined('_JEXEC') or die;
$arr =array(249, 259);
?>

<nav id="mp-menu" class="mp-menu">
	<div class="mp-level">
		<h2 class="mp-home"><span class="fa fa-home"></span> Home</h2>
		<a href="#" class="close mp-close">Close</a>
		<ul>
			<?php
			foreach ($list as $i => &$item) {
			if ( !in_array($item->id,$arr) ):
				$class = 'item-' . $item->id;

				if (($item->id == $active_id) OR ( $item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id)) {
					$class .= ' current';
				}

				if (in_array($item->id, $path)) {
					$class .= ' active';
				} elseif ($item->type == 'alias') {
					$aliasToId = $item->params->get('aliasoptions');

					if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
						$class .= ' active';
					} elseif (in_array($aliasToId, $path)) {
						$class .= ' alias-parent-active';
					}
				}

				if ($item->type == 'separator') {
					$class .= ' divider';
				}

				if ($item->deeper && $item->id!=248) {
					$class .= ' deeper';
				}

				if ($item->parent) {
					$class .= ' parent';
				}
				if($item->anchor_css)
				{
					$class .= ' '.$item->anchor_css;
				}
				if (!empty($class)) {
					$class = ' class="' . trim($class) . '"';
				}
			
				echo '<li' . $class . '>';

				if (!$item->deeper) {
					// Render the menu item.
					switch ($item->type) :
						case 'separator':
						case 'heading':
						case 'url':
						case 'component': 
							require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
							break;

						default: 
							require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
							break;
					endswitch;
				}else {
					echo '<a href="'.$item->link.'" class="'.$item->anchor_css.'">' . $item->title . '</a>';
				}
				// The next item is deeper.
				
					if ($item->deeper) {
						if($item->level != 2) 
							echo '<div class="mp-level"><h2 class="icon icon-display">' . $item->title . '</h2><a class="mp-back" href="#">back</a><ul>';
					} elseif ($item->shallower) {
						// The next item is shallower.
						echo '</li>';
						if( $item->level != 3 )
							echo str_repeat('</ul></div></li>', $item->level_diff);
					} else {
						// The next item is on the same level.
						echo '</li>';
					}
			endif;
			}
			?>
		</ul>
	</div>
</nav>

