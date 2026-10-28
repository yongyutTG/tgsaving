<?php
namespace App\Database\Seeds;
class DemoSeeder extends \CodeIgniter\Database\Seeder {
 public function run() {
  if ($this->db->table('articles')->countAllResults() > 0) { echo "Skipped: articles already exist.\n"; return; }
  $items = [
   ['ประกาศสหกรณ์','[Demo] แจ้งกำหนดการประชุมใหญ่สามัญประจำปี','ตัวอย่างประกาศเชิญสมาชิกเข้าร่วมประชุมใหญ่สามัญประจำปี เพื่อรับทราบผลการดำเนินงานและแลกเปลี่ยนความคิดเห็น'],
   ['ข่าวประชาสัมพันธ์','[Demo] เริ่มต้นวางแผนการออม เพื่ออนาคตที่มั่นคง','ตัวอย่างบทความแนะนำการตั้งเป้าหมายการออม จัดสรรรายรับรายจ่าย และสร้างวินัยทางการเงินอย่างสม่ำเสมอ'],
   ['ข่าวกิจกรรม','[Demo] กิจกรรมปลูกต้นไม้ ร่วมสร้างพื้นที่สีเขียว','ตัวอย่างข่าวกิจกรรมสมาชิกและเจ้าหน้าที่ร่วมกันดูแลพื้นที่สีเขียว เพื่อส่งเสริมความร่วมมือและความรับผิดชอบต่อสังคม'],
   ['ประกาศสหกรณ์','[Demo] แนะนำช่องทางติดตามข่าวสารของสหกรณ์','ตัวอย่างประกาศเกี่ยวกับช่องทางประชาสัมพันธ์ สมาชิกสามารถติดตามข่าวล่าสุดจากเว็บไซต์ของสหกรณ์'],
   ['ข่าวกิจกรรม','[Demo] ภาพบรรยากาศเวทีพบปะสมาชิก','ตัวอย่างข่าวการพบปะสมาชิก เปิดพื้นที่รับฟังข้อเสนอแนะ และแลกเปลี่ยนแนวคิดในการพัฒนาบริการ'],
   ['ข่าวประชาสัมพันธ์','[Demo] เตรียมเอกสารให้พร้อมก่อนติดต่อสหกรณ์','ตัวอย่างบทความแนะนำการเตรียมเอกสารก่อนใช้บริการ รายละเอียดจริงจะเผยแพร่เมื่อได้รับการตรวจสอบจากเจ้าหน้าที่'],
  ];
  foreach ($items as $i=>$item) {
   $name = md5('tgsaving-demo-'.$i).'.png';
   $img = imagecreatetruecolor(900,540);
   $colors = [[83,53,116],[162,126,75],[69,110,94],[91,82,124],[108,82,115],[68,95,126]];
   [$r,$g,$b] = $colors[$i];
   imagefill($img,0,0,imagecolorallocate($img,$r,$g,$b));
   $accent = imagecolorallocate($img,min(255,$r+34),min(255,$g+34),min(255,$b+34));
   for($j=0;$j<5;$j++) imageellipse($img,750,250,200+$j*100,200+$j*100,$accent);
   $gold = imagecolorallocate($img,237,207,153);
   imagefilledrectangle($img,75,115,83,400,$gold);
   imagestring($img,5,115,150,'TG SAVING',$gold);
   imagestring($img,5,115,205,'TOGETHER, WE GROW',imagecolorallocate($img,255,255,255));
   imagestring($img,4,115,365,'DEMO / 0'.($i+1),$gold);
   imagepng($img,WRITEPATH.'uploads/'.$name); imagedestroy($img);
   $date = date('Y-m-d H:i:s',strtotime('-'.$i.' days'));
   $this->db->table('articles')->insert(['title'=>$item[1],'category'=>$item[0],'body'=>$item[2]."\n\nข้อมูลนี้จัดทำเพื่อสาธิตการแสดงผลของเว็บไซต์เท่านั้น ไม่ใช่ประกาศ กำหนดการ หรือคำแนะนำอย่างเป็นทางการของสหกรณ์\n\nเจ้าหน้าที่สามารถเข้าสู่ระบบเพื่อแก้ไขเนื้อหา เปลี่ยนรูปปก และกำหนดสถานะการเผยแพร่ได้",'image'=>$name,'status'=>'published','created_at'=>$date,'updated_at'=>$date]);
  }
  $this->db->table('articles')->insert(['title'=>'[Demo] ข่าวฉบับร่างสำหรับทดลองแก้ไข','category'=>'ข่าวประชาสัมพันธ์','body'=>'ข่าวนี้ยังไม่เผยแพร่ ผู้เข้าชมเว็บไซต์จะไม่เห็นจนกว่าเจ้าหน้าที่จะเปลี่ยนสถานะเป็นเผยแพร่','status'=>'draft','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
 }
}
