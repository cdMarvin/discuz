<?php
namespace app\index\model;

use think\Model;

class TopicTag extends Model{
  public static function getHotTags($num) {
    $topicTag = new TopicTag();
    return $topicTag->field('tag_id, count(topic_id) as topicNum')
      ->group('tag_id')
      ->limit($num)
      ->select();
  }

  public static function getTagTopicIds($page, $limitNum, $tagId) {
    return self::where('tag_id', $tagId)
      ->page($page, $limitNum)
      ->column('topic_id');
  }

  public static function getTagTopicByTopicId($topicId){
    return self::where(['topic_id' => $topicId])->select();
  }

  public static function isExists($tagId, $topicId) {
    return self::where([
      'tag_id' => $tagId,
      'topic_id' => $topicId
    ])->find();
  }

  public function tag() {
    return $this->belongsTo('Tag', 'tag_id');
  }
}
