<?php
namespace App\Database\Migrations;
class AddNewsGallery extends \CodeIgniter\Database\Migration {
 public function up() {
  $this->forge->addColumn('articles', [
   'location'=>['type'=>'VARCHAR','constraint'=>255,'null'=>true],
   'event_at'=>['type'=>'DATETIME','null'=>true],
   'gallery'=>['type'=>'TEXT','null'=>true],
  ]);
 }
 public function down() { $this->forge->dropColumn('articles',['location','event_at','gallery']); }
}
