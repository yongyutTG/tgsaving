<!doctype html>
<html lang="th"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= esc($title ?? 'TGSaving • สหกรณ์ออมทรัพย์') ?></title><link rel="stylesheet" href="<?= base_url('assets/site.css') ?>"></head>
<body>
<div class="demo-bar">DEMO · เว็บไซต์ตัวอย่างเพื่อทดสอบระบบ ข้อมูลทั้งหมดไม่ใช่ประกาศจริง</div>
<header class="site-header"><div class="container header-inner"><a class="brand" href="<?= base_url() ?>"><span class="brand-mark">TG<span>saving</span></span><span><strong>สหกรณ์ออมทรัพย์</strong><small>TGSAVING · เติบโตไปด้วยกัน</small></span></a><div class="header-note">ร่วมออมวันนี้ เพื่ออนาคตที่มั่นคง<br><small>เว็บไซต์ประชาสัมพันธ์สำหรับสมาชิก</small></div></div></header>
<nav class="main-nav" aria-label="เมนูหลัก"><div class="container nav-inner">
<a href="<?= base_url() ?>">หน้าหลัก</a>
<?php foreach (config('SiteSections')->menus as $slug=>$label): ?>
<a href="<?= $slug === 'publicity' ? base_url('?category='.urlencode('ข่าวประชาสัมพันธ์').'#news') : base_url('section/'.$slug) ?>"><?= esc($label) ?></a>
<?php endforeach ?>
</div></nav>
<main><?= $this->renderSection('content') ?></main>
<footer><div class="container footer-inner"><div><strong>TGSAVING</strong><p>พื้นที่ข่าวสารและความรู้ เพื่อสมาชิกสหกรณ์</p></div><p>เว็บไซต์สาธิต · ยังไม่เปิดให้บริการจริง<br>พัฒนาด้วย CodeIgniter 4</p></div></footer>
</body></html>
