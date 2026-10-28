<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateCms extends Migration {
 public function up() {
  $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true], 'username'=>['type'=>'VARCHAR','constraint'=>100], 'password'=>['type'=>'VARCHAR','constraint'=>255]]);
  $this->forge->addKey('id',true); $this->forge->addUniqueKey('username'); $this->forge->createTable('staff');
  $this->forge->addField([
   'id'=>['type'=>'INT','auto_increment'=>true],
   'title'=>['type'=>'VARCHAR','constraint'=>255],
   'category'=>['type'=>'VARCHAR','constraint'=>100],
   'body'=>['type'=>'TEXT'],
   'status'=>['type'=>'VARCHAR','constraint'=>20,'default'=>'draft'],
   'image'=>['type'=>'VARCHAR','constraint'=>255,'null'=>true],
   'created_at'=>['type'=>'DATETIME'], 'updated_at'=>['type'=>'DATETIME'],
  ]);
  $this->forge->addKey('id',true); $this->forge->createTable('articles');
 }
 public function down() { $this->forge->dropTable('articles'); $this->forge->dropTable('staff'); }
}
