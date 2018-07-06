<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen

 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_images.php 8657 2015-01-19 19:16:02Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

function getYoutubeIdFromUrl($url) {
    $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $qs);
        if(isset($qs['v'])){
            return $qs['v'];
			}else if(isset($qs['vi'])){
            return $qs['vi'];
        }
    }
    if(isset($parts['path'])){
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path)-1];
    }
    return false;
}

if(VmConfig::get('usefancy',1)){
	vmJsApi::addJScript( 'fancybox/jquery.fancybox-1.3.4.pack', false);
	vmJsApi::css('jquery.fancybox-1.3.4');
	$document = JFactory::getDocument ();
	$imageJS = '
	jQuery(document).ready(function() {
		Virtuemart.updateImageEventListeners()
	});
	Virtuemart.updateImageEventListeners = function() {
		jQuery("a[rel=vm-additional-images]").fancybox({
			"titlePosition" 	: "inside",
			"transitionIn"	:	"elastic",
			"transitionOut"	:	"elastic"
		});
		jQuery(".additional-images a.product-image.image-0").removeAttr("rel");
		jQuery(".additional-images img.product-image").click(function() {
			jQuery(".additional-images a.product-image").attr("rel","vm-additional-images" );
			jQuery(this).parent().children("a.product-image").removeAttr("rel");
			var src = jQuery(this).parent().children("a.product-image").attr("href");
			jQuery(".main-image img").attr("src",src);
			jQuery(".main-image img").attr("alt",this.alt );
			jQuery(".main-image a").attr("href",src );
			jQuery(".main-image a").attr("title",this.alt );
			jQuery(".main-image .vm-img-desc").html(this.alt);
		}); 
	}
	';
} else {
	vmJsApi::addJScript( 'facebox',false );
	vmJsApi::css( 'facebox' );
	$document = JFactory::getDocument ();
	$imageJS = '
	jQuery(document).ready(function() {
		Virtuemart.updateImageEventListeners()
	});
	Virtuemart.updateImageEventListeners = function() {
		jQuery("a[rel=vm-additional-images]").facebox();
		var imgtitle = jQuery("span.vm-img-desc").text();
		jQuery("#facebox span").html(imgtitle);
	}
	';
}
vmJsApi::addJScript('imagepopup',$imageJS);

if (!empty($this->product->images)) {
	if(count($this->product->images > 1)) { ?>
		<div class="row product-slide-detail">
			
			<div class="slider-for" id="">
				<?php foreach($this->product->images as $image): ?>
				<div class="product-img product-image">
					<?php 
						$file_url_nonimage = "images/imageNotAvailable.jpg";
						$file_url_image =  $image->file_url;
					?>
					<img src="<?php echo (@getimagesize($file_url_image))?$file_url_image:$file_url_nonimage; ?>" class="non-zoom"/>
				</div>
				
				<?php endforeach; ?>
				
				
				<?php 
				
				$youtube_id = array();
				if( isset($this->product->customfieldsSorted['normal'])) {
					foreach ( $this->product->customfieldsSorted['normal'] as $item ){
						if ( $item->virtuemart_custom_id == 3 ) echo $item->display;
						
						$doc = new DOMDocument();
						$doc->loadHTML($item->customfield_value);
						
						if ($doc->getElementsByTagName('iframe')->item(0)!=null){
							$src = $doc->getElementsByTagName('iframe')->item(0)->getAttribute('src');
							$youtube_id[] = getYoutubeIdFromUrl($src);
						}
					}
				}
				?>
				
			</div>
			
			<div class="slider-nav">
				<?php foreach($this->product->images as $image): ?>
					<div class="product-img-slide">
						<?php 
							$file_url_nonimage = "images/imageNotAvailable.jpg";
							$file_url_image =  $image->file_url_thumb;
						?>
						<img src="<?php echo (@getimagesize($file_url_image))?$file_url_image:$file_url_nonimage; ?>" class="" nopin = "nopin"/>
					</div>
				<?php endforeach; ?>
				
				<?php foreach($youtube_id as $item): ?>
					<div class="product-img-slide">
						<img class="" src="https://img.youtube.com/vi/<?php echo $item; ?>/default.jpg" alt="" nopin = "nopin"/> 
					</div>
				<?php endforeach; ?>
				
			</div>
		</div>
		
		
		
	<?php }
	else { 
		$image = $this->product->images[0];
	?>
		<div class="main-image">
			<img src="<?php echo $image->file_url ?>" class="img-responsive" />
		</div>
	<?php }
}
?>
