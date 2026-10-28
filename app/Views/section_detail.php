<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<article class="container article">
 <a class="back" href="<?= base_url('section/'.$section) ?>">← กลับ<?= esc($label) ?></a>
 <div class="eyebrow purple"><?= esc($label) ?> · DEMO</div>
 <h1><?= esc($item['title']) ?></h1>
 <div class="demo-disclaimer">ข้อมูลสมมติสำหรับสาธิตเว็บไซต์เท่านั้น ไม่ใช่ข้อมูลหรือเงื่อนไขบริการจริง</div>
 <p class="demo-description"><?= esc($item['summary']) ?></p>
 <div class="article-body"><?= nl2br(esc($item['body'])) ?></div>
 <?php if (in_array($section,['downloads','reports','videos'],true)): ?><p class="demo-asset-note"><?= $section === 'videos' ? 'รายการนี้เป็นรายละเอียดวิดีโอตัวอย่าง ยังไม่มีไฟล์วิดีโอให้เล่น' : 'รายการนี้เป็นรายละเอียดเอกสารตัวอย่าง ยังไม่มีไฟล์เอกสารให้ดาวน์โหลด' ?></p><?php endif ?>
</article>
<?= $this->endSection() ?>
