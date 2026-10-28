<!doctype html>
<html lang="th"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= esc($title ?? 'สหกรณ์ออมทรัพย์พนักงานบริษัท การบินไทย จำกัด') ?></title><link rel="icon" type="image/png" href="<?= base_url('favicon.png') ?>"><link rel="stylesheet" href="<?= base_url('assets/site.css') ?>"></head>
<body>
<div class="demo-bar">DEMO · เว็บไซต์ตัวอย่างเพื่อทดสอบระบบ ข้อมูลทั้งหมดไม่ใช่ประกาศจริง</div>
<header class="site-header"><div class="container header-inner"><a class="brand" href="<?= base_url() ?>"><img class="brand-logo" src="<?= base_url('assets/logo_coop.png') ?>" alt="" width="64" height="64"><span><strong>สหกรณ์ออมทรัพย์พนักงานบริษัท การบินไทย จำกัด</strong><small>เป็นสหกรณ์ออมทรัพย์ที่มั่นคง โปร่งใส ก้าวไกลด้วยเทคโนโลยี เอื้ออาทรต่อสมาชิกและสังคม</small></span></a><div class="header-note">ร่วมออมวันนี้ เพื่ออนาคตที่มั่นคง<br><small>เว็บไซต์ประชาสัมพันธ์สำหรับสมาชิก</small></div></div></header>
<nav class="main-nav" aria-label="เมนูหลัก"><div class="container nav-inner">
<a href="<?= base_url() ?>">หน้าหลัก</a>
<?php foreach (config('SiteSections')->menus as $slug=>$label): ?>
<a href="<?= $slug === 'publicity' ? base_url('?category='.urlencode('ข่าวประชาสัมพันธ์').'#news') : base_url('section/'.$slug) ?>"><?= esc($label) ?></a>
<?php endforeach ?>
</div></nav>
<main><?= $this->renderSection('content') ?></main>
<footer><div class="container footer-inner"><div><strong>สหกรณ์ออมทรัพย์พนักงานบริษัท การบินไทย จำกัด</strong><p>พื้นที่ข่าวสารและความรู้ เพื่อสมาชิกสหกรณ์</p></div><p>เว็บไซต์สาธิต · ยังไม่เปิดให้บริการจริง<br>พัฒนาด้วย CodeIgniter 4</p></div></footer>
</body></html>
