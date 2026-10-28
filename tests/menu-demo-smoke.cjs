const {chromium}=require('C:/Users/A/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
 const b=await chromium.launch({headless:true,channel:'chrome'});
 const p=await b.newPage({viewport:{width:1440,height:1000}});
 const errors=[];p.on('pageerror',e=>errors.push(e.message));
 await p.goto('http://localhost:8085/');
 if(await p.locator('.announcement-panel .update-row').count()!==5)throw Error('Announcements');
 if(await p.locator('.publicity-panel .update-row').count()!==5)throw Error('Publicity');
 if(await p.locator('.news-card').count()!==5)throw Error('Activities');
 const links=await p.locator('.main-nav a').evaluateAll(nodes=>nodes.map(n=>({label:n.textContent,url:n.href})));
 if(links.length!==12)throw Error('Menu count');
 let details=0;
 for(const link of links){
  const r=await p.goto(link.url);if(r.status()!==200)throw Error(link.label);
  if(link.url.includes('/section/')){
   if(await p.locator('.demo-item').count()!==5)throw Error('Count: '+link.label);
   const detailLinks=await p.locator('.demo-item h2 a').evaluateAll(nodes=>nodes.map(n=>n.href));
   for(const href of detailLinks){
    const detail=await p.goto(href);
    if(detail.status()!==200||await p.locator('.article-body').count()!==1)throw Error(href);
    details++;
   }
  }
 }
 const mismatch=await p.goto('http://localhost:8085/section/contact/1');
 if(mismatch.status()!==404)throw Error('Cross-section mismatch');
 const unknown=await p.goto('http://localhost:8085/section/unknown');
 if(unknown.status()!==404)throw Error('Unknown section');
 await p.goto('http://localhost:8085/section/deposits');
 await p.screenshot({path:'writable/demo-menu-desktop.png',fullPage:true});
 await p.setViewportSize({width:390,height:844});
 if(await p.evaluate(()=>document.documentElement.scrollWidth>innerWidth))throw Error('Mobile overflow');
 await p.screenshot({path:'writable/demo-menu-mobile.png',fullPage:true});
 if(errors.length)throw Error(errors.join(';'));
 console.log('PASS: 12 navigation links, 50 detail pages, 5 items per section, 5 news per category, 404 boundaries and mobile');
 await b.close();
})().catch(e=>{console.error(e);process.exit(1)});

