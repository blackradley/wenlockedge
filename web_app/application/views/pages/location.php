<header>
	<h1><a class="secret" href="<?= $blogger_page->sourceUrl ?>" target="_blank"><?= $blogger_page->title ?></a></h1>
</header>

<section class="content-main map-holder">	
	<h3><a class="secret" href="<?=$graph->sourceUrl?>" target="_blank">Map</a></h3>

	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	<script>
	var map;

	function initialize() {
		var myLatlng = new google.maps.LatLng(<?= $graph->location->latitude ?>,<?= $graph->location->longitude ?>);
		var mapOptions = {
				zoom: 12,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
				};
		var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

		var contentString = '<h4><?= $graph->name ?></h4>' + 
			'<p><?= $graph->location->address ?></br></p>';

	    var infowindow = new google.maps.InfoWindow({
	        content: contentString
	    });

	    var marker = new google.maps.Marker({
	        position: myLatlng,
	        map: map,
	        title: '<?= $graph->name ?>'
	    });
    
	    google.maps.event.addListener(marker, 'click', function() {
	      infowindow.open(map, marker);
	    });
	    infowindow.open(map, marker);

	    // Pan the map a bit so the info window is nicely positioned
	    google.maps.event.addListener(infowindow, 'domready', function() {
	        map.panBy(0, -75)
	    });
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	</script>
	
	<div id="map_canvas" style="width: 100%; height: 400px;"></div>
</section>

<section class="content-main">	
	<?= $blogger_page->content ?>
</section>
