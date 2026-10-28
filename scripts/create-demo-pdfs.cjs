const {chromium}=require('C:/Users/A/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
const fs=require('fs'),crypto=require('crypto');
const esc=s=>String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
(async()=>{
 const rows=JSON.parse(fs.readFileSync('writable/pdf-demo-source.json','utf8'));
 const browser=await chromium.launch({headless:true,channel:'chrome'});
 const page=await browser.newPage();
 const result=[];
 for(const a of rows){
  const filename=crypto.randomBytes(16).toString('hex')+'.pdf';
  await page.setContent('<!doctype html><html lang="th"><meta charset="utf-8"><style>@page{size:A4;margin:22mm}body{font:15px/1.9 Tahoma,sans-serif;color:#30283a}header{border-bottom:3px solid #51317b;padding-bottom:18px;display:flex;justify-content:space-between;align-items:center}.brand{font:bold 28px Georgia;color:#51317b}.demo{color:#795c24;background:#fff1d5;padding:5px 14px;font-size:12px}h1{font-size:23px;line-height:1.7;margin:30px 0 15px}.meta{color:#827b8b;font-size:12px}.body{white-space:pre-wrap;margin-top:28px}footer{margin-top:45px;border-top:1px solid #ded7e5;padding-top:18px;font-size:12px;color:#827b8b}.note{background:#f3eef7;padding:15px;margin-top:30px;font-size:12px}</style><header><div class="brand">TG SAVING</div><div class="demo">เอกสาร DEMO</div></header><h1>'+esc(a.title)+'</h1><p class="meta">ประกาศสหกรณ์ | เอกสารตัวอย่างเลขที่ DEMO-'+String(a.id).padStart(3,'0')+'</p><div class="body">'+esc(a.body)+'</div><div class="note">เอกสารนี้สร้างขึ้นเพื่อทดสอบระบบอ่าน PDF เท่านั้น ไม่ใช่ประกาศจริง ไม่มีผลบังคับใช้ และไม่มีลายมือชื่อผู้อนุมัติ</div><footer>สหกรณ์ออมทรัพย์ · TGSaving<br>ข้อมูลสาธิตสำหรับพัฒนาเว็บไซต์</footer></html>');
  await page.evaluate(()=>document.fonts.ready);
  await page.pdf({path:'writable/uploads/'+filename,format:'A4',printBackground:true});
  result.push({id:a.id,pdf:filename});
 }
 fs.writeFileSync('writable/pdf-demo-result.json',JSON.stringify(result));
 await browser.close();console.log('Created '+result.length+' demo PDFs');
})().catch(e=>{console.error(e);process.exit(1)});

