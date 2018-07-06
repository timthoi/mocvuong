<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$user            = JFactory::getUser();
$this->language  = $doc->language;
$this->direction = $doc->direction;

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$com      = $app->input->getCmd('option', '');
$sitename = $app->get('sitename');
$lang     = $app->input->getCmd('lang', '');


$doc->addStyleSheet($this->baseurl . '/templates/system/css/system.css');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<?php echo ($this->params->get('after_head')!="")?$this->params->get('after_head'):"";?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
	<link href="templates/<?php echo $this->template ?>/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="templates/<?php echo $this->template ?>/css/lib.css" rel="stylesheet"/>
    <link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/font-awesome.min.css">
    <link href="templates/<?php echo $this->template ?>/assets/mlpushmenu/css/mlpushmenu.css" rel="stylesheet"/>
    <link href="templates/<?php echo $this->template ?>/css/print.css" rel="stylesheet" media="print"/>
    <link href="templates/<?php echo $this->template ?>/js/jquery-scroll/jquery.scrollbar.css" rel="stylesheet"/>
    <link href="templates/<?php echo $this->template ?>/js/flexslider/flexslider.css" rel="stylesheet"/>
	<link href="templates/<?php echo $this->template ?>/js/slick/slick.css" rel="stylesheet"/>
	<link href="templates/<?php echo $this->template ?>/js/slick/slick-theme.css" rel="stylesheet"/>
    <link href="templates/<?php echo $this->template ?>/css/screen.css" rel="stylesheet"/>
    <link href="templates/<?php echo $this->template ?>/css/style.css" rel="stylesheet"/>
      
    <script src="templates/<?php echo $this->template ?>/js/lib.js" type="text/javascript"></script>
    <script src="templates/<?php echo $this->template ?>/assets/mlpushmenu/js/modernizr.custom.js" type="text/javascript"></script>
    <script src="templates/<?php echo $this->template ?>/assets/mlpushmenu/js/classie.js" type="text/javascript"></script>
    <script src="templates/<?php echo $this->template ?>/assets/mlpushmenu/js/mlpushmenu.js" type="text/javascript"></script>
    <script src="templates/<?php echo $this->template ?>/js/jquery-scroll/jquery.scrollbar.min.js" type="text/javascript"></script>
    <script src="templates/<?php echo $this->template ?>/js/flexslider/jquery.flexslider-min.js" type="text/javascript"></script>
	<script src="templates/<?php echo $this->template ?>/js/slick/slick.min.js" type="text/javascript"></script>
	
    <script src="templates/<?php echo $this->template ?>/js/app.js" type="text/javascript"></script>
    <script src="templates/<?php echo $this->template ?>/js/html5.js" type="text/javascript"></script>
     
    <script src="templates/<?php echo $this->template ?>/js/owl-carousel/owl.carousel.js" type="text/javascript"></script>
	<script src="templates/<?php echo $this->template ?>/js/elevate-zoom/jquery.elevateZoom-3.0.8.min.js" type="text/javascript"></script>
	<?php echo ($this->params->get('before_head')!="")?$this->params->get('before_head'):"";?>
</head>
<?php

//Detect special conditions devices
$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
$class = '';
//do something with this information
if( $iPod || $iPhone || $iPad){
    $class = 'class="iOs"';
}
?> 
<body <?php echo $class ?>>
<?php echo ($this->params->get('after_body')!="")?$this->params->get('after_body'):"";?>
<div class="mp-container"> <!-- Wrap everything with this .mp-container -->
    <div class="mp-pusher" id="mp-pusher">
    	<!-- mp-menu -->
    	<jdoc:include type="modules" name="mobile-menu" style="none" />
    	<!-- /mp-menu -->
    	<div class="scroller"><!-- This is for emulating position fixed of the nav -->
    		<div class="scroller-inner"><!-- Put all stuffs in this div. -->
    			<!-- Body -->
    			<div class="body">
                    <?php if($this->countModules('topmenu')):?>
                    <div id="top">
                        <div class="container">
                            <div class="navbar-right">
                                <div class="top-menu">
                				<jdoc:include type="modules" name="topmenu" />
                                </div>
                                <?php if($this->countModules('search')):?>
                				    <jdoc:include type="modules" name="search" />
                                <?php endif; ?>
                			</div>
                        </div>
                    </div>
                    <?php endif;?>
                    
                    <header id="header">
                    	<div class="mainHeader">
                            <div class="temp-bg"></div>
                    		<div class="container">
                    			<h1><a href="." title="Shevron">Shevron</a></h1>
                                
                                <div class="navbar-header">
									<button type="button" class="navbar-toggle" id="trigger">
										<span class="sr-only">Toggle navigation</span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
								</div>
                    
                                <?php if($this->countModules('mainmenu')):?>
                                    <div id="navbar" class="navbar-collapse navbar-left">
                                        <jdoc:include type="modules" name="mainmenu" style="none" />
                                    </div>
                                <?php endif;?>
                    		</div>
                    	</div>
                    </header>
                    
                    <?php if($this->countModules('slideshow')):?>
                    <div id="slider">
                        <jdoc:include type="modules" name="slideshow" style="none" />  
                    </div>
                    <?php endif;?>

                    
                    <!-- BLOCK 2: MIDDLE PAGE -->
                    <?php if($this->countModules('breadcrumb') && $view != 'featured'):?>
                    <div id="breadcrumb">
                        <div class="container">
                            <jdoc:include type="modules" name="breadcrumb" style="xhtml" />
                        </div>
                    </div>
                    <?php endif; ?>
                   
                    <div class="maincontent">                        
                        <div class="<?=($com != 'com_rsform') ? 'container' : '' ?>">
                            <?php if($this->countModules('filter')):?>    
                                <jdoc:include type="modules" name="filter" style="none" />
                            <?php endif;?>
                            <?php if($this->countModules('contenttop')):?>
                                <jdoc:include type="modules" name="contenttop" style="xhtml" />
                            <?php endif;?>
                            <?php if($this->countModules('left')):?>
                            <div class="menuleft col-md-3">       
                                <jdoc:include type="modules" name="left" style="xhtml" />
                            </div>
                            <?php endif;?>
                            <div class="contentSide">
                                <jdoc:include type="message" />
                                <jdoc:include type="component" /> 
                                <jdoc:include type="modules" name="content-bottom" style="none" />
                            </div>  

                        </div>
                    </div>                   
                    

                    <!-- END MIDDLE PAGE -->                    
                    <?php if($this->countModules('bottom')):?>	
                    <jdoc:include type="modules" name="bottom" style="none"/>                    	
                    <?php endif;?>  

    			</div>
    			<!-- Footer -->
    			<div class="footer" role="contentinfo">
                    <div class="container">
                        <div class="wrapperFooter">
                            <?php if($this->countModules('mailing-list')):?>
                                <jdoc:include type="modules" name="mailing-list" style="bottom" />
                            <?php endif; ?>
                            <?php if($this->countModules('customer-services')):?>
                            <div class="col-sm-4 col-xs-12 customer-services">
                                <jdoc:include type="modules" name="customer-services" style="bottom" />
                            </div>
                            <?php endif; ?>
                            <jdoc:include type="modules" name="footer" style="bottom" />
                        </div>
                	</div>
    			</div>
                <footer class="footerCopyright">
                    <div class="container">
                        <div class="container-inner">
                        <jdoc:include type="modules" name="copyright" style="none"/>
                        </div>
                    </div>
                </footer>
                <jdoc:include type="modules" name="debug" style="none" />
    		</div>
    	</div>
    </div>
</div>

<script>
new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ), {
	type : 'cover'
} );
</script>


<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-59659b25f5e9dca0"></script>


	
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	
	ga('create', 'UA-86667683-1', 'auto');
	ga('send', 'pageview');
	
</script>

<!-- Facebook Pixel Code -->
<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
	document,'script','https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '1639735236318981');
	fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=1639735236318981&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
<?php echo ($this->params->get('before_body')!="")?$this->params->get('before_body'):"";?>
</body>
</html>