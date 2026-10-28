<?php $gallery = \App\Models\Article::galleryFiles($article ?? []); ?>
<fieldset class="gallery-editor"><legend>รายละเอียดข่าวประชาสัมพันธ์</legend>
 <p class="muted">กรอกสถานที่ วันเวลา และรูปถ่าย 5 รูปก่อนเผยแพร่ข่าวประชาสัมพันธ์</p>
 <label>สถานที่<input name="location" maxlength="255" value="<?= esc($value('location')) ?>" placeholder="เช่น ห้องประชุมสหกรณ์"></label>
 <label>วันและเวลากิจกรรม<input type="datetime-local" name="event_at" value="<?= esc($value('event_at') ? date('Y-m-d\\TH:i',strtotime($value('event_at'))) : '') ?>"></label>
 <p class="muted">เวลาไทย (Asia/Bangkok) · JPG, PNG, WebP รูปละไม่เกิน 1 MB · เรียงตามช่อง 1–5</p>
 <div class="gallery-editor-grid"><?php for ($slot=0; $slot<5; $slot++): ?>
  <div class="gallery-slot"><label>รูปถ่ายที่ <?= $slot+1 ?><input type="file" name="gallery_<?= $slot+1 ?>" accept="image/jpeg,image/png,image/webp"></label>
  <?php if (!empty($gallery[$slot])): ?><img src="<?= base_url('media/'.$gallery[$slot]) ?>" alt="รูปถ่ายที่ <?= $slot+1 ?>" loading="lazy"><label class="checkbox"><input type="checkbox" name="remove_gallery_<?= $slot+1 ?>" value="1"> เอารูปนี้ออก</label><?php endif ?></div>
 <?php endfor ?></div>
 <p class="muted">เลือกไฟล์ใหม่ในช่องเดิมเพื่อเปลี่ยนรูป หากบันทึกไม่ผ่าน กรุณาเลือกไฟล์ใหม่อีกครั้ง</p>
</fieldset>

