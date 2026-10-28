const {chromium}=require('C:/Users/A/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
const fs=require('fs');
(async()=>{
 const b=await chromium.launch({headless:true,channel:'chrome'});
 const p=await b.newPage({viewport:{width:1280,height:900}});
 await p.goto('http://localhost:8085/');
 const links=await p.locator('.announcement-panel .update-more').evaluateAll(a=>a.map(x=>x.href));
 for(const url of links){
  await p.goto(url);
  if(await p.locator('.article-image').count())throw Error('Image remains');
  const src=await p.locator('object.announcement-pdf').getAttribute('data');
  const r=await p.request.get(src);
  if(r.status()!==200||!r.headers()['content-type'].includes('application/pdf')||!(await r.body()).subarray(0,5).equals(Buffer.from('%PDF-')))throw Error('PDF response');
  const dl=await p.request.get(src+'?download=1');if(!dl.headers()['content-disposition'].startsWith('attachment'))throw Error('Download header');
 }
 await p.screenshot({path:'writable/announcement-pdf-desktop.png'});
 await p.setViewportSize({width:390,height:844});
 if(await p.evaluate(()=>document.documentElement.scrollWidth>innerWidth))throw Error('Mobile overflow');
 await p.goto('http://localhost:8085/admin/login');
 await p.locator('[name=username]').fill('admin');await p.locator('[name=password]').fill(process.env.CMS_TEST_PASSWORD);
 await p.locator('button.full').click();await p.waitForURL('**/admin');
 await p.goto('http://localhost:8085/admin/news/new');
 await p.locator('[name=title]').fill('[TEST] PDF lifecycle');
 await p.locator('[name=body]').fill('PDF test');
 await p.locator('[name=status]').selectOption('published');
 await p.locator('button.full').click();await p.locator('.error').waitFor();
 if(!(await p.locator('.error').innerText()).includes('PDF'))throw Error('Missing PDF validation');
 await p.locator('[name=pdf]').setInputFiles({name:'bad.pdf',mimeType:'application/pdf',buffer:Buffer.from('not a pdf')});
 await p.locator('button.full').click();await p.locator('.error').waitFor();
 const demo=JSON.parse(fs.readFileSync('writable/pdf-demo-result.json','utf8'))[0];
 await p.locator('[name=pdf]').setInputFiles('writable/uploads/'+demo.pdf);
 await p.locator('[name=status]').selectOption('draft');
 await p.locator('button.full').click();await p.waitForURL('**/admin');
 const edit=await p.getByRole('link',{name:'[TEST] PDF lifecycle',exact:true}).getAttribute('href'),id=edit.split('/').pop();
 fs.writeFileSync('writable/pdf-test-result.json',JSON.stringify({id:Number(id)}));
 const anon=await b.newContext();const client=anon.request;
 let r=await client.get('http://localhost:8085/news/'+id+'/pdf');if(r.status()!==404)throw Error('Draft PDF leak');
 await p.goto(edit);r=await p.request.get('http://localhost:8085/news/'+id+'/pdf');if(r.status()!==200)throw Error('Admin draft preview');
 await p.locator('[name=status]').selectOption('published');await p.locator('button.full').click();await p.waitForURL('**/admin');
 r=await client.get('http://localhost:8085/news/'+id+'/pdf');if(r.status()!==200)throw Error('Publish');
 await p.goto(edit);await p.locator('[name=remove_pdf]').check();await p.locator('button.full').click();await p.locator('.error').waitFor();
 await p.locator('[name=remove_pdf]').uncheck();await p.locator('[name=status]').selectOption('draft');await p.locator('button.full').click();await p.waitForURL('**/admin');
 r=await client.get('http://localhost:8085/news/'+id+'/pdf');if(r.status()!==404)throw Error('Withdraw PDF leak');
 await b.close();console.log('PASS: 5 PDF readers, downloads, mobile, missing/invalid uploads, valid upload, draft privacy, publication and withdrawal');
})().catch(e=>{console.error(e);process.exit(1)});

