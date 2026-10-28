<?php
namespace App\Database\Migrations;

class AddAnnouncementPdf extends \CodeIgniter\Database\Migration
{
 public function up() {
  $this->forge->addColumn('articles', ['pdf'=>['type'=>'VARCHAR','constraint'=>255,'null'=>true]]);
 }
 public function down() { $this->forge->dropColumn('articles','pdf'); }
}
