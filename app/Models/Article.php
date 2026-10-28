<?php
namespace App\Models;
class Article extends \CodeIgniter\Model {
 protected $table = 'articles';
 protected $allowedFields = ['title','category','body','status','image','pdf','location','event_at','gallery'];
 protected $useTimestamps = true;
 public const CATEGORIES = ['ประกาศสหกรณ์','ข่าวประชาสัมพันธ์','ข่าวกิจกรรม'];
 public static function galleryFiles(array $article): array {
  $items = json_decode($article['gallery'] ?? '[]',true);
  return is_array($items) ? array_slice($items,0,5) : [];
 }
}
