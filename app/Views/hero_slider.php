<section class="hero hero-slider" aria-label="ภาพประชาสัมพันธ์" aria-roledescription="carousel">
 <div class="container hero-grid">
  <div class="hero-slides">
   <?php $slides = [
    ['saving', 'ทุกการออม สร้างอนาคตที่มั่นคง', 'ร่วมคิด ร่วมออม ร่วมสร้างอนาคต'],
    ['community', 'เติบโตไปด้วยกัน', 'ร่วมเป็นส่วนหนึ่งของชุมชนแห่งการแบ่งปัน'],
    ['news', 'ทุกข่าวสาร ใกล้สมาชิกมากขึ้น', 'ติดตามประกาศและกิจกรรมของสหกรณ์'],
   ]; foreach ($slides as $index => [$file, $heading, $caption]): ?>
   <div class="hero-slide" role="group" aria-roledescription="slide" aria-label="<?= $index + 1 ?> จาก 3" <?= $index ? 'hidden' : '' ?>>
    <img src="<?= base_url('assets/banners/'.$file.'.svg') ?>" alt="<?= esc($heading.' — '.$caption) ?>" width="1440" height="520" <?= $index ? 'loading="lazy"' : 'fetchpriority="high"' ?>>
   </div>
   <?php endforeach ?>
  </div>
  <div class="hero-controls">
   <button type="button" class="hero-prev" aria-label="ภาพก่อนหน้า">←</button>
   <div class="hero-dots" aria-label="เลือกภาพ"><?php foreach ($slides as $index => $slide): ?><button type="button" data-slide="<?= $index ?>" aria-label="แสดงภาพที่ <?= $index + 1 ?>" aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>"></button><?php endforeach ?></div>
   <button type="button" class="hero-next" aria-label="ภาพถัดไป">→</button>
   <button type="button" class="hero-pause" aria-label="หยุดเลื่อนอัตโนมัติ">หยุดชั่วคราว</button>
   <span class="hero-count" aria-live="off">01 / 03</span>
  </div>
 </div>
</section>
<script src="<?= base_url('assets/hero-slider.js') ?>" defer></script>
