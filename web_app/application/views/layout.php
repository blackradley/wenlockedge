<!DOCTYPE html>
<!--[if IE 7 ]>					<html class="ie lt-ie8 ie7" lang="en"> <![endif]-->
<!--[if gt IE 7]>  				<html class="ie gt-ie7"> <![endif]-->
<!--[if !IE]><!--> 				<html lang="en"><!--<![endif]-->
<head>
<meta charset="utf-8">
<title><?=$title ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?=$title ?> - <?=$graph->description ?>">

<!-- Le styles -->
<link href="/public/css/bootstrap.css" rel="stylesheet">
<link href="/public/css/bootstrap-responsive.css" rel="stylesheet" />
<link href="/public/css/cookiecuttr.css" rel="stylesheet" />
<link href="/public/css/customization.css" rel="stylesheet" />
<link href="/public/css/ss-social.css" rel="stylesheet" />
<link href="/public/css/ss-standard.css" rel="stylesheet" />

<!-- For benefit of IE etc. that don't understand media queries -->
<script src="/public/js/respond.min.js"></script>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/public/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/public/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/public/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="/public/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="/public/ico/favicon.ico" sizes="16x16 24x24 32x32 64x64" type="image/vnd.microsoft.icon">
<!--<link rel="shortcut icon" href="/public/ico/favicon.png">-->
</head>
<body>	
	<div id="masthead">
		<div class="container">
			<div class="row">
				<div class="span8 masthead-main">
					<a href="/" class="logo visible-desktop visible-tablet hidden-phone">
						<img src="/public/img/logo-snibston-21st-anniversary.gif" />
					</a>
					
					<nav class="navbar">
						<div class="navbar-inner">
							<a class="btn btn-navbar" data-toggle="collapse"
								data-target=".nav-collapse"> <span class="icon-bar"></span> <span
								class="icon-bar"></span> <span class="icon-bar"></span>
							</a> 

							<span class="hidden-desktop hidden-tablet visible-phone">
								<a class="brand" href="/">Snibston Discovery Museum</a>
							</span>
							
							<div class="nav-collapse collapse">
								<ul class="nav">
									<li><a href="/">Home</a></li>
									<li class="dropdown">
		              					<a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="/welcome/visit">Visit <b class="ss-icon ss-dropdown"></b></a>
		              					<ul class="dropdown-menu">
		                					<li><a href="/welcome/visit">Visit Snibston</a></li>
		                					<li><a href="/welcome/eat">Eat and Shop</a></li>
		                					<li><a href="/welcome/about">About</a></li>
		                					<li><a href="/welcome/facilities">Facilities and Access</a></li>
											<li><a href="/welcome/location">How to find us</a></li>
										</ul>
						            </li>
						            <li><a href="/welcome/century">Century Theatre</a></li>
									<li><a href="/welcome/events">What's On</a></li>
									<li class="dropdown">
		              					<a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="/welcome/learn">Learn <b class="ss-icon ss-dropdown"></b></a>
		              					<ul class="dropdown-menu">
		               						<li><a href="/welcome/learn">Learn at Snibston</a></li>
		              					    <li><a href="/welcome/education">Making an Educational Visit</a></li>
		              					    <li><a href="/welcome/sessions">Learning Sessions</a></li>
		                					<li><a href="/welcome/sen">SEN</a></li>
		                				</ul>
						            </li>
									<li><a href="/welcome/hire" class="last">Hire</a></li>
								</ul>								
							</div>
							
						</div><!--/.navbar-inner-->							
					</nav><!--/.navbar-->						
				</div><!--/.span8-->	
				
				<div class="span4 masthead-aside">
					<dl>
						<dt>Email:</dt>
							<dd><a href="mailto:snibston@leics.gov.uk" class="email">snibston@leics.gov.uk</a></dd>
					</dl>
					<dl>
						<dt>Telephone:</dt>
						 	<dd class="tel"><?=$graph->phone ?></dd>
					</dl>
				</div><!--/.span4-->
				
				<img src="/public/img/circles-head.png" alt="" class="header-circles visible-desktop hidden-tablet hidden-phone" />
				
			</div><!--/.row-->
		</div><!--/.container-->
	</div><!--/#masthead-->
	
	<div id="section-content">	
		<div class="container">			
			<div class="row">
				<div class="span8 content">
					<?=$content ?>
				</div>
				
				<div class="span4 bs-docs-sidebar">
					
					<aside class="times-prices">
						<h3><a class="secret" href="<?=$graph->sourceUrl ?>" target="_blank">Opening Times &amp; Prices</a></h3>
						<ul class="opening-times">
							<li><b>Today:</b> <span class="times"><?=$graph->openingHoursToday ?></span></li>
							<?php foreach ($graph->openingHours as $key => $value) : ?>
							<li><b><?=$key ?>:</b> <span class="times"><?=$value ?></span></li>
							<?php endforeach ?>
						</ul>
						
						<!--<h3><a class="secret" href="<?=$prices_post->sourceUrl ?>"><?=$prices_post->title ?></a></h3>-->						
						<ul class="prices">
							<li><?=$prices_post->content ?></li>
						</ul>
					</aside>
					<aside class="unfilled social">		
						<!-- TODO: Make this asynchronous, else it tends to screw things up -->
						<div id="TA_rated451" class="TA_rated">
							<ul id="E9M87jFX" class="TA_links mU7Mte9d0Nm">
								<li id="QMkxnFvxOWj" class="B5H8Qzh">
									<a href="http://www.tripadvisor.co.uk/Attraction_Review-g815421-d809601-Reviews-Snibston_Discovery_Museum_and_Country_Park-Coalville_Leicestershire_England.html" target="_blank">Trip Advisor</a>
								</li>
							</ul>
						</div>
						<script src="http://www.jscache.com/wejs?wtype=rated&amp;uniq=451&amp;locationId=809601&amp;lang=en_UK"></script>		
						
						<div class="social-wrapper">		
							<a href="https://www.facebook.com/Snibston" title="Facebook" class="icon first" target="_blank"><span class='ss-icon ss-social-circle'>Facebook</span></a>
			
							<a href="https://twitter.com/SnibstonLCC" title="Twitter" class="icon" target="_blank"><span class='ss-icon ss-social-circle'>Twitter</span></a>
						
							<a href="http://www.flickr.com/photos/lccheritage/" title="Flickr" class="icon last" target="_blank"><span class='ss-icon ss-social-circle'>Flickr</span></a>
						</div>
					</aside>		
					
					<aside class="location">
 						<h3><a class="secret" href="/welcome/location">How to find us</a></h3>
						<p>Our <a href="/welcome/location">location</a>, and all you need to know about <a href="/welcome/facilities">facilities and access</a>.</p>
						<a href="/welcome/location" class='hidden-phone'><img src="/public/img/map.gif" /></a>
					</aside>
										
					<aside class="news">
 						<h3><a class="secret" href="https://twitter.com/SnibstonLCC" target="_blank">Latest News</a></h3>
						<div class="tweet">Sorry, no news yet.</div>
					</aside>
				</div>
			</div><!--/.row-->
			
			<div class="footer-circles visible-desktop hidden-tablet hidden-phone">
				<img src="/public/img/circles-foot-left.gif" class="circle-left" />
				<img src="/public/img/circles-foot-right.gif" class="circle-right" />
			</div><!--/.footer-circles-->
			
		</div><!--/.container-->
	</div><!--/#section-content-->	

 	<footer>
		<div id="associations" class="hidden-phone">			
		    <div class="container">
			    <div class="row">
					<div class="span12">
						<span class="association" style="width:10.512820512%"><img src="/public/img/logo-accredited-museum.gif" alt=" " class="first" /></span>
						<span class="association" style="width:30.769230769%"><img src="/public/img/logo-arts-council.gif" alt=" " /></span>
						<span class="association" style="width:16.324786324%"><img src="/public/img/logo-loc-quality-badge.gif" alt=" " /></span>
						<span class="association" style="width:8.632478632%"><img src="/public/img/logo-enjoy-england.gif" alt=" " /></span>
						<span class="association" style="width:17.606837606%"><img src="/public/img/logo-enjoy-england-silver-2010.gif" alt=" " /></span>
						<span class="association" style="width:16.153846153%"><img src="/public/img/logo-enjoy-england-gold-2010.gif" alt=" " class="last" /></span>
					</div>
			    </div><!--/.row-->
			</div><!--/.container-->
		</div><!--/#associations-->
		
		<div class="container legal">
	        <p>Leicestershire County Council &copy; <?php echo date('Y');?> &middot; 
	        	<a href="/welcome/privacy">Privacy</a>
	        </p>
		</div><!--/.container-->
    </footer>

	<!-- Le javascript -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="/public/js/bootstrap.min.js"></script>
	<script src="/public/js/jquery.cookie.js"></script>
	<script src="/public/js/jquery.cookiecuttr.js"></script>
	<script src="/public/js/jquery.tweet.js"></script>
	<script src="/public/js/ss-social.js"></script>
	<?php if (isset($page_specific_scripts)) {foreach ($page_specific_scripts as $script): ?>
		<script src="<?php echo $script ?>"></script>
	<?php endforeach; } ?>
		
	<script type="text/javascript">
    if (jQuery.cookie('cc_cookie_accept') == "cc_cookie_accept") {
        // Collect the Google Analytics
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		  ga('create', 'UA-36961584-2', 'snibston.com');
		  ga('send', 'pageview');
		
		// Update the news - add #visitsnibston to query to limit twitter feed
      	$(".tweet").tweet({
          username: "SnibstonLCC",
          join_text: "auto",
          avatar_size: 32,
          count: 7,
          template: "{text}",
          loading_text: "loading tweets..."
      });
	}
	
	jQuery(function($){
		$.cookieCuttr();
		// Roll the carousel
		$('.carousel').carousel();

		// Not for IE7 thank you
		if (!$('html').hasClass("ie7")) {
			
			// Adjust header widths now
			adjust_header_width('h1', 70);
			adjust_header_width('h3', 50);

			// And adjust header widths when resize window
			$(window).resize(function() {
				adjust_header_width('h1', 70);
			 	adjust_header_width('h3', 50);
			});		
		}	
	});
	
	function adjust_header_width(header, single_line_height){
		$(header).each(function(){
			var header_block_width = $(this).width();
			var header_block_height = $(this).height();
			var header_inline_width = $(this).css('display','inline').width();			
			// If height a single line, make width match that of inline equivalent
			if(header_block_height <= single_line_height){
				$(this).width( header_inline_width );
			}else{
				$(this).width( 'auto' );
			}			
			// Put back to block again
			$(this).css('display','block');
		});
	}
    </script>
</body>
</html>
