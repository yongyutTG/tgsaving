<?php
namespace App\Controllers;
use App\Models\Article;
class Portal extends BaseController {
 protected $helpers = ['form','url'];
 public function index() {
  $model = new Article();
  $model->where('status','published');
  $q = trim((string)$this->request->getGet('q'));
  $category = (string)$this->request->getGet('category');
  if ($q !== '') $model->like('title',$q);
  if (in_array($category,Article::CATEGORIES,true)) $model->where('category',$category);
  $sections = [];
  if ($q === '' && $category === '') {
   foreach (Article::CATEGORIES as $name) {
    $limit = 5;
    $sections[$name] = (new Article())->where('status','published')->where('category',$name)->orderBy('created_at','DESC')->orderBy('id','DESC')->findAll($limit);
   }
  }
  return view('portal',['articles'=>$model->orderBy('created_at','DESC')->paginate(9),'pager'=>$model->pager,'q'=>$q,'category'=>$category,'sections'=>$sections]);
 }
 public function section($section) {
  $menus = config('SiteSections')->menus;
  if (!isset($menus[$section]) || $section === 'publicity') throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  $items = db_connect()->table('demo_content')->where('section',$section)->orderBy('created_at','DESC')->orderBy('id','DESC')->get()->getResultArray();
  return view('section',['title'=>$menus[$section].' · TGSaving','label'=>$menus[$section],'section'=>$section,'items'=>$items]);
 }
 public function sectionDetail($section,$id) {
  $menus = config('SiteSections')->menus;
  if (!isset($menus[$section]) || $section === 'publicity') throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  $item = db_connect()->table('demo_content')->where('section',$section)->where('id',$id)->get()->getRowArray();
  if (!$item) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  return view('section_detail',['title'=>$item['title'].' · TGSaving','label'=>$menus[$section],'section'=>$section,'item'=>$item]);
 }
 public function article($id) {
  $article = (new Article())->where('status','published')->find($id);
  if (!$article) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  return view('article',['article'=>$article]);
 }
 public function pdf($id) {
  $model = new Article();
  if (!session()->get('staff_id')) $model->where('status','published');
  $article = $model->find($id);
  if (!$article || !preg_match('/^[a-f0-9]{32}\.pdf$/D', $article['pdf'] ?? '')) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  $path = WRITEPATH.'uploads/'.$article['pdf'];
  if (!is_file($path)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  $disposition = $this->request->getGet('download') === '1' ? 'attachment' : 'inline';
  return $this->response->setContentType('application/pdf')
   ->setHeader('Content-Disposition',$disposition.'; filename="announcement-'.(int)$id.'.pdf"')
   ->setHeader('X-Content-Type-Options','nosniff')
   ->setHeader('Cache-Control','private, no-store')
   ->setBody(file_get_contents($path));
 }
 public function media($name) {
  if (!preg_match('/^[a-f0-9]{32}\\.(jpg|png|webp)$/D',$name)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  if (!session()->get('staff_id') && !(new Article())->where('status','published')->groupStart()->where('image',$name)->orLike('gallery','"'.$name.'"')->groupEnd()->first()) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  $path = WRITEPATH.'uploads/'.$name;
  if (!is_file($path)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  return $this->response->setHeader('X-Content-Type-Options','nosniff')->setHeader('Cache-Control','private, no-store')->setContentType(mime_content_type($path))->setBody(file_get_contents($path));
 }
}
