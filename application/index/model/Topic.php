<?php
namespace app\index\model;

use think\Model;

class Topic extends Model{
  public static function getTopics($page, $limitNum, $sortInfo) {
    $sortInfo['field'] = $sortInfo['field'] ? $sortInfo['field'] : 'created_at';
    $sortInfo['order'] = $sortInfo['order'] ? $sortInfo['order'] : 'desc';
    return Topic::withCount(['replies', 'praises'])
      ->where(['is_delete' => 0])
      ->page($page, $limitNum)
      ->order($sortInfo['field'], $sortInfo['order'])
      ->select();
  }

  public static function getPageInfo($page, $limitNum) {
    $count = self::where(['is_delete' => 0])->count();
    return self::_getPageInfo($page, $limitNum, $count);
  }

  private static function _getPageInfo($page, $limitNum, $count) {
    $page = intval($page);
    $page = $page < 1 ? 1 : $page;
    $limitNum = intval($limitNum) < 1 ? 1 : intval($limitNum);
    $maxPage = ceil($count / $limitNum);
    $page = $page > $maxPage ? $maxPage : $page;

    $showPages = [];
    for ($leftPage = $page - 3; $leftPage <= $page; $leftPage++) {
      if ($leftPage > 0) {
        $showPages[] = $leftPage;
      }
    }

    for ($i = 1; $i <= 3; $i++) {
      if ($page + $i <= $maxPage) {
        $showPages[] = $page + $i;
      }
    }
    return ['page' => $page, 'pageNum' => $maxPage, 'showPages' => $showPages];
  }

  public static function getSearchPageInfo($page, $limitNum, $keyword) {
    $map = [];
    $map['title'] = ['like', '%'.$keyword.'%'];
    $topic = new self();
    $count = $topic->where($map)->count();
    return self::_getPageInfo($page, $limitNum, $count);
  }

  public static function getTagPageInfo($page, $limitNum, $count) {
    return self::_getPageInfo($page, $limitNum, $count);
  }

  public static function search($page, $limitNum, $keyword) {
    $map = [];
    $map['title'] = ['like', '%'.$keyword.'%'];
    return Topic::withCount(['replies', 'praises'])
      ->where($map)
      ->page($page, $limitNum)
      ->select();
  }

  public static function getTagTopics($ids) {
    $map = [];
    $map['id'] = ['in', $ids];
    return Topic::withCount(['replies', 'praises'])
      ->where($map)
      ->select();
  }

  public static function getTopic($id) {
    return self::withCount(['replies', 'praises'])->find($id);
  }

  public function user() {
    return $this->belongsTo('User', 'user_id');
  }

  public function replies() {
    return $this->hasMany('Reply', 'topic_id');
  }

  public function praises() {
    return $this->hasMany('Praise', 'topic_id');
  }
}
