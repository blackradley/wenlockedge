<header>
	<h1><a class="secret" href="<?=$events_list->sourceUrl?>" target="_blank">What's On</a></h1>
</header>

<span itemprop="geo" itemscope itemtype="http://data-vocabulary.org/​Geo">
	<meta itemprop="latitude" content="<?= $graph->location->latitude ?>" />
	<meta itemprop="longitude" content="<?= $graph->location->longitude ?>" />
</span>

<section class="content-main">	
	<?php foreach ($events_list->events as $event): ?>
		<div class="item pull-left event-wide" itemscope itemtype="http://data-vocabulary.org/Event">
			<img class="pull-left" itemprop="photo" style="margin:0 35px 35px 0;" src="<?=$event->pic?>" alt="<?=$event->name?>">
			<div>
				<h4 itemprop="summary"><a href="<?=$event->url?>" target="_blank"><?=$event->name?></a></h4>
				<p class="datetime"><?=$event->when?></p>
				<p itemprop="description" class="comment more">
					<?=$event->descriptionLong?>
				</p>
			</div>
		</div>
	<?php endforeach ?>
</section>