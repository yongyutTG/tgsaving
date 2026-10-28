<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<article class="container article">
 <a class="back" href="<?= base_url() ?>">← กลับหน้าข่าวสาร</a>
 <p class="eyebrow purple"><?= esc($article['category']) ?></p>
 <h1><?= esc($article['title']) ?></h1>
 <p class="muted">เผยแพร่ <?= esc(date('d/m/Y',strtotime($article['created_at']))) ?></p>
 <?php if ($article['category'] === 'ประกาศสหกรณ์'): ?>
  <?php if ($article['pdf']): ?>
   <div class="pdf-actions"><a class="button" href="<?= base_url('news/'.$article['id'].'/pdf') ?>" target="_blank" rel="noopener">เปิดอ่าน PDF ↗</a><a class="button secondary" href="<?= base_url('news/'.$article['id'].'/pdf?download=1') ?>">ดาวน์โหลด PDF</a></div>
   <p class="muted">หากอุปกรณ์ไม่แสดงเอกสารด้านล่าง กด “เปิดอ่าน PDF” หรือดาวน์โหลดเพื่ออ่านได้</p>
   <object class="announcement-pdf" data="<?= base_url('news/'.$article['id'].'/pdf') ?>" type="application/pdf" aria-label="<?= esc('เอกสารประกาศ '.$article['title']) ?>"><p>เปิดอ่านเอกสารได้ที่ <a href="<?= base_url('news/'.$article['id'].'/pdf') ?>">ไฟล์ PDF</a></p></object>
  <?php else: ?><div class="notice">ประกาศนี้ยังไม่มีไฟล์ PDF แนบ</div><?php endif ?>
 <?php elseif ($article['category'] === 'ข่าวประชาสัมพันธ์'): ?>
  <dl class="news-event-info"><div><dt>สถานที่</dt><dd><?= esc($article['location'] ?: 'ยังไม่ระบุ') ?></dd></div><div><dt>วันเวลา</dt><dd><?php if ($article['event_at']): $event = strtotime($article['event_at']); ?><time datetime="<?= esc(date('c',$event)) ?>"><?= date('d/m/',$event).((int)date('Y',$event)+543).' เวลา '.date('H:i',$event) ?> น.</time><?php else: ?>ยังไม่ระบุ<?php endif ?></dd></div></dl>
  <div class="article-body publicity-body"><?= nl2br(esc($article['body'])) ?></div>
  <h2 class="photo-heading">ภาพประกอบข่าว</h2>
  <?php if (str_starts_with($article['title'],'[Demo]')): ?><p class="muted">ภาพถ่ายสาธิตจาก <a href="https://picsum.photos/" target="_blank" rel="noopener">Lorem Picsum</a> ไม่ใช่ภาพเหตุการณ์จริงของข่าว</p><?php endif ?>
  <div class="news-photo-gallery"><?php foreach (\App\Models\Article::galleryFiles($article) as $slot=>$photo): if (!$photo) continue; ?>
   <figure><a href="<?= base_url('media/'.$photo) ?>" target="_blank" rel="noopener" aria-label="เปิดรูปถ่ายที่ <?= $slot+1 ?> ขนาดเต็ม"><img src="<?= base_url('media/'.$photo) ?>" alt="<?= esc($article['title'].' — รูปถ่ายที่ '.($slot+1)) ?>" loading="lazy"></a><figcaption>รูปถ่ายที่ <?= $slot+1 ?></figcaption></figure>
  <?php endforeach ?></div>
 <?php else: ?>
  <?php if ($article['image']): ?><img class="article-image" src="<?= base_url('media/'.$article['image']) ?>" alt="<?= esc($article['title']) ?>"><?php endif ?>
  <div class="article-body"><?= nl2br(esc($article['body'])) ?></div>
 <?php endif ?>
</article>
<?= $this->endSection() ?>
