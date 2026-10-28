<?php
namespace App\Controllers;
use App\Models\Article;
class Admin extends BaseController {
 protected $helpers = ['form','url'];
 public function login() {
  if ($this->request->is('post')) {
   $key = 'login-'.hash('sha256',$this->request->getIPAddress());
   if (!service('throttler')->check($key,5,60)) return redirect()->back()->with('error','ลองเข้าสู่ระบบอีกครั้งใน 1 นาที');
   $user = db_connect()->table('staff')->where('username',(string)$this->request->getPost('username'))->get()->getRowArray();
   if ($user && password_verify((string)$this->request->getPost('password'),$user['password'])) {
    session()->regenerate(true); session()->set('staff_id',$user['id']); return redirect()->to('/admin');
   }
   return redirect()->back()->with('error','ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
  }
  return view('login');
 }
 public function logout() { session()->destroy(); return redirect()->to('/admin/login'); }
 public function index() {
  $model = new Article();
  $status = (string)$this->request->getGet('status');
  $q = trim((string)$this->request->getGet('q'));
  if (in_array($status,['draft','published','trash'],true)) $model->where('status',$status);
  if ($q !== '') $model->like('title',$q);
  return view('admin',['articles'=>$model->orderBy('updated_at','DESC')->paginate(20),'pager'=>$model->pager,'q'=>$q,'status'=>$status,
   'total'=>(new Article())->countAllResults(),
   'published'=>(new Article())->where('status','published')->countAllResults(),
   'drafts'=>(new Article())->where('status','draft')->countAllResults()]);
 }
 public function edit($id = null) {
  $article = $id ? (new Article())->find($id) : null;
  if ($id && !$article) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  return view('edit',['article'=>$article]);
 }
 public function save() {
  $editId = filter_var($this->request->getPost('id'), FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]);
  $editUrl = base_url('admin/news/'.($editId ?: 'new'));
  $rules = ['title'=>'required|max_length[255]','body'=>'required|max_length[100000]','category'=>'required|in_list['.implode(',',Article::CATEGORIES).']','status'=>'required|in_list[draft,published]','id'=>'permit_empty|is_natural_no_zero'];
  $publicity = $this->request->getPost('category') === 'ข่าวประชาสัมพันธ์';
  $publishing = $publicity && $this->request->getPost('status') === 'published';
  $rules['location'] = ($publishing ? 'required' : 'permit_empty').'|max_length[255]';
  $rules['event_at'] = ($publishing ? 'required' : 'permit_empty').'|valid_date[Y-m-d\\TH:i]';
  if (!$this->validateData($this->request->getPost(),$rules)) return redirect()->to($editUrl)->withInput()->with('error',implode(' ', $this->validator->getErrors()));
  $model = new Article(); $id = $this->request->getPost('id');
  $existing = $id ? $model->find($id) : null;
  if ($id && !$existing) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  $data = array_intersect_key($this->request->getPost(),array_flip(['title','body','category','status']));
  $data['location'] = trim((string)$this->request->getPost('location')) ?: null;
  $data['event_at'] = $this->request->getPost('event_at') ? str_replace('T',' ',$this->request->getPost('event_at')).':00' : null;
  $gallery = Article::galleryFiles($existing ?? []);
  $galleryUploads = [];
  for ($slot=0; $slot<5; $slot++) {
   $key = 'gallery_'.($slot+1);
   $upload = $this->request->getFile($key);
   if ($upload && $upload->getError() !== UPLOAD_ERR_NO_FILE) {
    if (!$this->validateData([],[$key=>"uploaded[$key]|is_image[$key]|mime_in[$key,image/jpeg,image/png,image/webp]|max_size[$key,1024]|max_dims[$key,6000,6000]"])) return redirect()->to($editUrl)->withInput()->with('error','รูปแกลเลอรีที่ '.($slot+1).' ต้องเป็น JPG, PNG หรือ WebP ขนาดไม่เกิน 1 MB');
    $galleryUploads[$slot] = $upload;
   } elseif ($this->request->getPost('remove_'.$key)) { $gallery[$slot] = null; }
  }
  if ($publishing) {
   for ($slot=0; $slot<5; $slot++) {
    if (empty($gallery[$slot]) && !isset($galleryUploads[$slot])) return redirect()->to($editUrl)->withInput()->with('error','กรุณาแนบรูปถ่ายครบ 5 รูปก่อนเผยแพร่ข่าวประชาสัมพันธ์ หรือบันทึกเป็นฉบับร่าง');
   }
  }
  $pdf = $this->request->getFile('pdf');
  $hasPdfUpload = $pdf && $pdf->getError() !== UPLOAD_ERR_NO_FILE;
  if ($hasPdfUpload && (!$this->validateData([],['pdf'=>'uploaded[pdf]|ext_in[pdf,pdf]|mime_in[pdf,application/pdf]|max_size[pdf,2048]']) || file_get_contents($pdf->getTempName(),false,null,0,5) !== '%PDF-')) {
   return redirect()->to($editUrl)->withInput()->with('error','กรุณาแนบไฟล์ PDF ที่ถูกต้อง ขนาดไม่เกิน 2 MB');
  }
  $retainedPdf = !empty($existing['pdf']) && !$this->request->getPost('remove_pdf');
  if ($data['category'] === 'ประกาศสหกรณ์' && $data['status'] === 'published' && !$hasPdfUpload && !$retainedPdf) {
   return redirect()->to($editUrl)->withInput()->with('error','กรุณาแนบไฟล์ PDF ก่อนเผยแพร่ประกาศ หรือบันทึกเป็นฉบับร่าง');
  }
  $file = $this->request->getFile('image');
  if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
   if (!$this->validateData([],['image'=>'uploaded[image]|is_image[image]|mime_in[image,image/jpeg,image/png,image/webp]|max_size[image,4096]|max_dims[image,6000,6000]'])) return redirect()->to($editUrl)->withInput()->with('error','กรุณาใช้รูป JPG, PNG หรือ WebP ขนาดไม่เกิน 4 MB และ 6000 × 6000 พิกเซล');
   $ext = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$file->getMimeType()];
   $data['image'] = bin2hex(random_bytes(16)).'.'.$ext;
   $file->move(WRITEPATH.'uploads',$data['image']);
  } elseif ($this->request->getPost('remove_image')) { $data['image'] = null; }
  if ($hasPdfUpload) {
   $data['pdf'] = bin2hex(random_bytes(16)).'.pdf';
   $pdf->move(WRITEPATH.'uploads',$data['pdf']);
  } elseif ($this->request->getPost('remove_pdf')) { $data['pdf'] = null; }
  foreach ($galleryUploads as $slot=>$upload) {
   $extension = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$upload->getMimeType()];
   $gallery[$slot] = bin2hex(random_bytes(16)).'.'.$extension;
   $upload->move(WRITEPATH.'uploads',$gallery[$slot]);
  }
  $data['gallery'] = json_encode(array_map(static fn($slot)=>$gallery[$slot] ?? null,range(0,4)));
  if ($id) $data['id'] = $id;
  $model->save($data);
  return redirect()->to('/admin')->with('message','บันทึกข่าวเรียบร้อยแล้ว');
 }
 public function delete($id) {
  (new Article())->update($id,['status'=>'trash']);
  return redirect()->to('/admin')->with('message','ย้ายข่าวลงถังขยะแล้ว สามารถเปิดแก้ไขเพื่อกู้คืนได้');
 }
}
