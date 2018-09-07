<?php
namespace app\index\controller;

use app\index\model\Topic as TopicModel;
use app\index\model\Tag as TagModel;
use app\index\model\TopicTag as TopicTagModel;
use app\index\model\Praise as PraiseModel;
use app\index\model\Reply as ReplyModel;

class Topic extends \think\Controller {
  public function index(){
    $getData = input('get.');
    $page = isset($getData['page']) ? $getData['page'] : 1;
    $field = isset($getData['field']) ? $getData['field'] : '';
    $order = isset($getData['order']) ? $getData['order'] : '';
    $sortInfo = ['field' => $field, 'order' => $order];
    $pageInfo = TopicModel::getPageInfo($page, config('limitNum'));
    $cacheName = 'index'.$page.$field.$order;
    if (cache($cacheName)) {
      $topics = cache($cacheName);
    } else {
      $topics = TopicModel::getTopics($pageInfo['page'], config('limitNum'), $sortInfo);
      cache($cacheName, $topics, 20);
    }

    $this->assign([
      'topics' => $topics,
      'user' => session('user'),
      'page' => $pageInfo['page'],
      'pageNum' => $pageInfo['pageNum'],
      'showPages' => $pageInfo['showPages'],
      'hotTags' => TopicTagModel::getHotTags(config('hotTagNum')),
    ]);
    echo $this->fetch('index');
  }

  public function search() {
    $getData = input('get.');
    $page = isset($getData['page']) ? $getData['page'] : 1;
    $keyword = isset($getData['keyword']) ? $getData['keyword'] : '';
    $pageInfo = TopicModel::getSearchPageInfo($page, config('limitNum'), $keyword);
    $topics = TopicModel::search($pageInfo['page'], config('limitNum'), $keyword);
    $this->assign([
      'topics' => $topics,
      'user' => session('user'),
      'page' => $pageInfo['page'],
      'keyword' => $keyword,
      'pageNum' => $pageInfo['pageNum'],
      'showPages' => $pageInfo['showPages'],
      'hotTags' => TopicTagModel::getHotTags(config('hotTagNum')),
    ]);
    echo $this->fetch('index');
  }

  public function tag() {
    $getData = input('get.');
    $page = isset($getData['page']) ? $getData['page'] : 1;
    $tagId = isset($getData['tag']) ? $getData['tag'] : '';
    $count = TopicTagModel::where(['tag_id' => $tagId])->count();
    $pageInfo = TopicModel::getTagPageInfo($page, config('limitNum'), $count);
    $topicIds = TopicTagModel::getTagTopicIds($pageInfo['page'], config('limitNum'), $tagId);
    $topics = TopicModel::getTagTopics($topicIds);
    $this->assign([
      'topics' => $topics,
      'user' => session('user'),
      'page' => $pageInfo['page'],
      'tagId' => $tagId,
      'pageNum' => $pageInfo['pageNum'],
      'showPages' => $pageInfo['showPages'],
      'hotTags' => TopicTagModel::getHotTags(config('hotTagNum')),
    ]);
    echo $this->fetch('index');
  }

  public function newTopic(){
    if (request()->isPost()) {
      $postData = input('post.');
      $user = session('user');
      $topicId = input('get.topicId');
      if (!$topicId) {
        $topic = new TopicModel();
      } else {
        $topic = TopicModel::find($topicId);
      }
      $topic->title = $postData['title'];
      $topic->category_id = $postData['category_id'];
      $topic->content = $postData['content'];
      $topic->user_id = $user->id;
      $topic->created_at = intval(microtime(true));
      $topic->save();
      // 标签处理
      $tags = $postData['tags'];
      TopicTagModel::where(['topic_id' => $topic->id])->delete();
      foreach($tags as $tag) {
        if (is_numeric($tag)) {
          $this->createTopicTag($tag, $topic->id);
          continue;
        }
        $newTag = $this->createTag($tag);
        $this->createTopicTag($newTag->id, $topic->id);
      }
      $message = $topicId ? '编辑帖子成功！' : '发表帖子成功！';
      return $this->success($message, 'topic/index');
    }
    $tags = TagModel::all();
    $this->assign([
      'user' => session('user'),
      'category' => config('category'),
      'tags' => $tags
    ]);
    echo $this->fetch('new_topic');
  }

  public function detail() {
    $topicId = input('get.id');
    $topic = TopicModel::getTopic($topicId);
    $this->assign([
      'user' => session('user'),
      'topic' => $topic,
      'replies' => ReplyModel::where(['topic_id' => $topicId])->select(),
      'topicTags' => TopicTagModel::getTagTopicByTopicId($topic->id),
      'categoryNames' => getCategoryNames($topic->category_id),
    ]);
    echo $this->fetch('detail');
  }

  public function praise() {
    $user = session('user');
    if (!$user) {
      return;
    }
    $topicId = input('get.topicId');
    $praise = PraiseModel::get(['topic_id' => $topicId, 'user_id' => $user->id]);
    if ($praise) {
      $praise->delete();
    } else {
      $praise = new PraiseModel([
        'topic_id' => $topicId,
        'user_id' => $user->id,
        'created_at' => intval(microtime(true)),
      ]);
      $praise->save();
    }
  }
  private function createTopicTag($tagId, $topicId) {
    $topicTag = new TopicTagModel();
    $topicTag->tag_id = $tagId;
    $topicTag->topic_id = $topicId;
    $topicTag->save();
  }

  private function createTag($tagName) {
    $newTag = new TagModel();
    $newTag->name = $tagName;
    $newTag->save();
    return $newTag;
  }
}
