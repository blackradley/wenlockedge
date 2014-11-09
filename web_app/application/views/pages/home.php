<header>
	<h1>Snibston Discovery Museum</h1>
</header>

<div id="myCarousel" class="carousel visible-desktop visible-tablet hidden-phone">
	<div class="carousel-inner">
	<?php $first = true; ?>
	<?php foreach ($carousel->set as $photo): ?>
		<div class="item<?php ($first)? print ' active"': print '"' ?>><?php $first = false; ?>
			<img src="<?=$photo['image']?>" alt="Image titled <?=$photo['title']?>"  onContextMenu="return false;">
			<div class="carousel-caption">
				<h4><?=$photo['title']?></h4>
				<p class="visible-desktop hidden-tablet hidden-phone"><?=$photo['content']?></p>
			</div>
		</div>
	<?php endforeach ?>
	</div>
	<a class="left carousel-control" href="#myCarousel" data-slide="prev">&#8249;</a>
	<a class="right carousel-control" href="#myCarousel" data-slide="next">&#8250;</a>
</div>

<section class="intro-blurb">	
	<p><?=$graph->about?></p>
	<p><?=addAnchorTags($graph->description)?></p>
</section>

<section class="content-main">
	<h3><a class="secret" href="<?=$events_list->sourceUrl?>" target="_blank">Must See Events</a></h3>
	<?php foreach ($events_list->events as $event): ?>
		<div class="item pull-left">
			<img class="pull-left" style="margin:0 35px 35px 0;" src="<?=$event->pic?>" alt="<?=$event->name?>">
			<div>
				<h4><a href="<?=$event->url?>" target="_blank"><?=$event->name?></a></h4>
				<p class="datetime"><?=$event->when?></p>
				<p><?=$event->descriptionLong?></p>
			</div>
		</div>
	<?php endforeach ?>
</section>
