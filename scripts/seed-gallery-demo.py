import urllib.request,json,hashlib,pathlib,sqlite3,concurrent.futures
from PIL import Image,ImageDraw
root=pathlib.Path.cwd()
def get(url):
 req=urllib.request.Request(url,headers={'User-Agent':'TGSavingDemo/1.0'})
 with urllib.request.urlopen(req,timeout=45) as r:return r.read()
items=json.loads(get('https://picsum.photos/v2/list?limit=20'))[10:15]
def fetch(item):
 name=hashlib.md5(('publicity-demo-'+item['id']).encode()).hexdigest()+'.jpg'
 path=root/'writable/uploads'/name
 if not path.exists():path.write_bytes(get('https://picsum.photos/id/'+item['id']+'/1200/800'))
 im=Image.open(path);im.verify()
 return dict(file=name,author=item['author'],source=item['url'],id=item['id'])
with concurrent.futures.ThreadPoolExecutor(max_workers=5) as pool:photos=list(pool.map(fetch,items))
(root/'writable/gallery-demo-sources.json').write_text(json.dumps(photos,ensure_ascii=False,indent=2),encoding='utf8')
db=sqlite3.connect(root/'writable/cms.sqlite')
articles=db.execute("SELECT id,created_at FROM articles WHERE category=? AND title LIKE '[Demo]%' AND status='published' ORDER BY id",('ข่าวประชาสัมพันธ์',)).fetchall()
for i,(aid,created) in enumerate(articles):
 gallery=[p['file'] for p in photos[i:]+photos[:i]]
 db.execute("UPDATE articles SET gallery=?,location=?,event_at=? WHERE id=? AND gallery IS NULL",(json.dumps(gallery),'พื้นที่กิจกรรมตัวอย่าง (Demo)',created[:10]+' 09:00:00',aid))
db.commit();db.close()
thumbs=[]
for p in photos:
 im=Image.open(root/'writable/uploads'/p['file']).convert('RGB');im.thumbnail((360,240));thumbs.append(im)
canvas=Image.new('RGB',(360*5,240),'white')
for i,im in enumerate(thumbs):canvas.paste(im,(i*360,0))
canvas.save(root/'writable/gallery-demo-contact.jpg')
print('Prepared 5 photo assets and galleries for',len(articles),'demo articles')

