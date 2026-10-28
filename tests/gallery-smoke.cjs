const {chromium}=require('C:/Users/A/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright'),fs=require('fs');
(async()=>{
 const b=await chromium.launch({headless:true,channel:'chrome'}),p=await b.newPage({viewport:{width:1280,height:1000}});
 await p.goto('http://localhost:8085/');
 const links=await p.locator('.publicity-panel .update-more').evaluateAll(ns=>ns.map(n=>n.href));
 for(const url of links){
  await p.goto(url);
  if(await p.locator('.news-photo-gallery img').count()!==5)throw Error('Gallery count');
  if(!(await p.locator('.news-event-info').innerText()).includes('09:00'))throw Error('Event date');
  for(const src of await p.locator('.news-photo-gallery img').evaluateAll(ns=>ns.map(n=>n.src))){if((await p.request.get(src)).status()!==200)throw Error('Photo missing');}
 }
 await p.screenshot({path:'writable/publicity-gallery-desktop.png',fullPage:true});
 await p.setViewportSize({width:390,height:844});
 if(await p.evaluate(()=>document.documentElement.scrollWidth>innerWidth))throw Error('Overflow');
 await p.screenshot({path:'writable/publicity-gallery-mobile.png',fullPage:true});
 await p.goto('http://localhost:8085/admin/login');await p.locator('[name=username]').fill('admin');await p.locator('[name=password]').fill(process.env.CMS_TEST_PASSWORD);await p.locator('button.full').click();await p.waitForURL('**/admin');
 await p.goto('http://localhost:8085/admin/news/new');
 await p.locator('[name=title]').fill('[TEST] gallery lifecycle');await p.locator('[name=body]').fill('Gallery body');
 await p.locator('[name=category]').selectOption({label:'ข่าวประชาสัมพันธ์'});
 await p.locator('[name=location]').fill('ห้องประชุมทดสอบ');
 await p.locator('[name=event_at]').fill('2026-10-28T13:45');
 await p.locator('[name=status]').selectOption('published');
 await p.locator('button.full').click();await p.locator('.error').waitFor();
 if(!(await p.locator('.error').innerText()).includes('5'))throw Error('Five images required');
 await p.locator('[name=gallery_1]').setInputFiles({name:'bad.jpg',mimeType:'image/jpeg',buffer:Buffer.from('not an image')});
 await p.locator('button.full').click();await p.locator('.error').waitFor();
 if(!(await p.locator('.error').innerText()).includes('JPG'))throw Error('Invalid upload accepted');
 const photos=JSON.parse(fs.readFileSync('writable/gallery-demo-sources.json','utf8'));
 for(let i=0;i<5;i++)await p.locator('[name=gallery_'+(i+1)+']').setInputFiles('writable/uploads/'+photos[i].file);
 await p.locator('button.full').click();await p.waitForURL('**/admin');
 const edit=await p.getByRole('link',{name:'[TEST] gallery lifecycle',exact:true}).getAttribute('href'),id=edit.split('/').pop();
 fs.writeFileSync('writable/gallery-test-result.json',JSON.stringify({id:Number(id)}));
 const anon=await b.newContext(),pub=await anon.newPage();await pub.goto('http://localhost:8085/news/'+id);
 if(await pub.locator('.news-photo-gallery img').count()!==5)throw Error('Saved gallery');
 if(!(await pub.locator('.news-event-info').innerText()).includes('13:45'))throw Error('Saved time');
 const src=await pub.locator('.news-photo-gallery img').first().getAttribute('src');
 await p.goto(edit);
 if(await p.locator('[name=event_at]').inputValue()!=='2026-10-28T13:45')throw Error('Date roundtrip');
 await p.locator('[name=remove_gallery_1]').check();await p.locator('button.full').click();await p.locator('.error').waitFor();
 await p.locator('[name=status]').selectOption('draft');await p.locator('button.full').click();await p.waitForURL('**/admin');
 if((await pub.request.get(src)).status()!==404)throw Error('Draft photo privacy');
 if((await pub.request.get('http://localhost:8085/news/'+id)).status()!==404)throw Error('Draft privacy');
 await b.close();console.log('PASS: five demo galleries, fields, 5-photo validation, invalid upload, upload/save, datetime roundtrip, draft privacy and mobile');
})().catch(e=>{console.error(e);process.exit(1)});

