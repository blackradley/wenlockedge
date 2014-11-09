<header>
	<h1><a class="secret" href="<?= $blogger_page->sourceUrl ?>" target="_blank"><?= $blogger_page->title ?></a></h1>
</header>

<div id="myCarousel" class="carousel visible-desktop visible-tablet hidden-phone">
	<div class="carousel-inner">
	<?php $first = true; ?>
	<?php foreach ($carousel->set as $photo): ?>
		<div class="item<?php ($first)? print ' active"': print '"' ?>><?php $first = false; ?>
			<img src="<?=$photo['image']?>" alt="<?=$photo['title']?>" onContextMenu="return false;">
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

<section class="content-main">
	<p><?= $blogger_page->content ?></p>
</section>
