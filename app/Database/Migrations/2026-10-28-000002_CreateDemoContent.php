<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDemoContent extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT','auto_increment'=>true],
            'section' => ['type'=>'VARCHAR','constraint'=>40],
            'slug' => ['type'=>'VARCHAR','constraint'=>60],
            'title' => ['type'=>'VARCHAR','constraint'=>255],
            'summary' => ['type'=>'TEXT'],
            'body' => ['type'=>'TEXT'],
            'created_at' => ['type'=>'DATETIME'],
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addUniqueKey(['section','slug']);
        $this->forge->createTable('demo_content');
    }

    public function down()
    {
        $this->forge->dropTable('demo_content');
    }
}
