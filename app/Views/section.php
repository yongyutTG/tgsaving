<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<section class="container demo-section">
 <a class="back" href="<?= base_url() ?>">← กลับหน้าหลัก</a>
 <div class="section-heading"><div><div class="eyebrow purple">TGSAVING · DEMO CONTENT</div><h1><?= esc($label) ?></h1></div><span class="tag">ข้อมูลตัวอย่าง <?= count($items) ?> รายการ</span></div>
 <p class="demo-description">เนื้อหาสมมติสำหรับทดลองเว็บไซต์ ไม่ใช่ข้อมูลบริการหรือประกาศจริงของสหกรณ์</p>
 <div class="demo-items">
 <?php foreach ($items as $index=>$item): ?>
 <article class="demo-item">
  <div class="demo-number" aria-hidden="true"><?= str_pad((string)($index+1),2,'0',STR_PAD_LEFT) ?></div>
  <div class="demo-item-content"><span class="demo-label">DEMO · <?= esc($label) ?></span><h2><a href="<?= base_url('section/'.$section.'/'.$item['id']) ?>"><?= esc($item['title']) ?></a></h2><p><?= esc($item['summary']) ?></p><a class="read-more" href="<?= base_url('section/'.$section.'/'.$item['id']) ?>">ดูรายละเอียดตัวอย่าง <span>↗</span></a></div>
 </article>
 <?php endforeach ?>
 </div>
 <?php if (!$items): ?><div class="empty">ยังไม่มีข้อมูลตัวอย่างในหมวดนี้</div><?php endif ?>
</section>
<?= $this->endSection() ?>
