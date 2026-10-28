(() => {
 const root = document.querySelector('.hero-slider');
 if (!root) return;
 const slides = [...root.querySelectorAll('.hero-slide')];
 const dots = [...root.querySelectorAll('[data-slide]')];
 const pause = root.querySelector('.hero-pause');
 const motion = matchMedia('(prefers-reduced-motion: reduce)');
 let index = 0, timer, paused = motion.matches, hovered = false, touchX = null;
 function show(next) {
  index = (next + slides.length) % slides.length;
  slides.forEach((slide, i) => { slide.hidden = i !== index; });
  dots.forEach((dot, i) => dot.setAttribute('aria-pressed', String(i === index)));
  root.querySelector('.hero-count').textContent = String(index + 1).padStart(2, '0') + ' / 03';
 }
 function schedule() {
  clearInterval(timer);
  if (!paused && !hovered && !document.hidden && !root.contains(document.activeElement)) timer = setInterval(() => show(index + 1), 5000);
 }
 function updatePause() {
  pause.textContent = paused ? 'เล่นอัตโนมัติ' : 'หยุดชั่วคราว';
  pause.setAttribute('aria-label', paused ? 'เริ่มเลื่อนอัตโนมัติ' : 'หยุดเลื่อนอัตโนมัติ');
  schedule();
 }
 root.querySelector('.hero-prev').addEventListener('click', () => { show(index - 1); schedule(); });
 root.querySelector('.hero-next').addEventListener('click', () => { show(index + 1); schedule(); });
 dots.forEach((dot, i) => dot.addEventListener('click', () => { show(i); schedule(); }));
 pause.addEventListener('click', () => { paused = !paused; updatePause(); });
 root.addEventListener('mouseenter', () => { hovered = true; schedule(); });
 root.addEventListener('mouseleave', () => { hovered = false; schedule(); });
 root.addEventListener('focusin', schedule);
 root.addEventListener('focusout', () => setTimeout(schedule, 0));
 root.addEventListener('keydown', event => {
  if (!['ArrowLeft','ArrowRight'].includes(event.key)) return;
  event.preventDefault(); show(index + (event.key === 'ArrowRight' ? 1 : -1)); schedule();
 });
 root.addEventListener('touchstart', event => { touchX = event.changedTouches[0].clientX; }, {passive:true});
 root.addEventListener('touchend', event => {
  if (touchX === null) return;
  const delta = event.changedTouches[0].clientX - touchX;
  if (Math.abs(delta) > 50) { show(index + (delta < 0 ? 1 : -1)); schedule(); }
  touchX = null;
 }, {passive:true});
 document.addEventListener('visibilitychange', schedule);
 motion.addEventListener('change', event => { paused = event.matches; updatePause(); });
 updatePause();
})();
