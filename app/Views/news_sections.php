<?php
$months = [1=>'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
?>
<div class="updates-columns">
<?php foreach (['ประกาศสหกรณ์'=>'ANNOUNCEMENT','ข่าวประชาสัมพันธ์'=>'NEWS'] as $name=>$english): ?>
<section class="updates-panel <?= $english === 'NEWS' ? 'publicity-panel' : 'announcement-panel' ?>" aria-label="<?= esc($name) ?>">
 <header><div class="updates-english"><?= $english ?></div><h2><?= $name === 'ประกาศสหกรณ์' ? 'ประกาศ' : 'ข่าวประชาสัมพันธ์' ?></h2></header>
 <?php foreach ($sections[$name] as $a): $date = strtotime($a['created_at']); ?>
 <article class="update-row">
  <time class="update-date" datetime="<?= date('Y-m-d',$date) ?>"><strong><?= date('d',$date) ?></strong><span><?= $months[(int)date('n',$date)] ?></span><b><?= (int)date('Y',$date)+543 ?></b></time>
  <?php if ($english === 'NEWS'): ?>
  <a class="update-thumbnail" href="<?= base_url('news/'.$a['id']) ?>" aria-label="<?= esc($a['title']) ?>"><?php if ($a['image']): ?><img src="<?= base_url('media/'.$a['image']) ?>" alt="" loading="lazy"><?php else: ?><span>TG saving</span><?php endif ?></a>
  <?php else: ?>
  <h3 class="update-title"><a href="<?= base_url('news/'.$a['id']) ?>"><?= esc($a['title']) ?></a></h3>
  <?php endif ?>
  <div class="update-copy"><?php if ($english === 'NEWS'): ?><h3><a href="<?= base_url('news/'.$a['id']) ?>"><?= esc($a['title']) ?></a></h3><?php else: ?><p><?= esc(mb_strimwidth($a['body'],0,110,'…')) ?></p><?php endif ?><a class="update-more" href="<?= base_url('news/'.$a['id']) ?>">อ่านต่อ &gt;&gt;</a></div>
 </article>
 <?php endforeach ?>
 <?php if (!$sections[$name]): ?><p class="updates-empty">ยังไม่มีข่าวในหมวดนี้</p><?php endif ?>
 <a class="updates-all" href="<?= base_url('?category='.urlencode($name).'#news') ?>">ดูทั้งหมด →</a>
</section>
<?php endforeach ?>
</div>
<h2 class="activity-heading">ข่าวกิจกรรม</h2>

