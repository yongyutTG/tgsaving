<?php
namespace App\Commands;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
class CreateAdmin extends BaseCommand {
 protected $group = 'CMS';
 protected $name = 'cms:admin';
 protected $description = 'Create an administrator with a random password.';
 public function run(array $params) {
  $username = $params[0] ?? 'admin';
  $db = db_connect();
  if ($db->table('staff')->where('username',$username)->countAllResults()) { CLI::error('User already exists.'); return; }
  $password = bin2hex(random_bytes(10));
  $db->table('staff')->insert(['username'=>$username,'password'=>password_hash($password,PASSWORD_DEFAULT)]);
  CLI::write('Username: '.$username); CLI::write('Password: '.$password);
 }
}
