<?php
namespace app\index\controller;

use app\index\model\Reply as RepleyModel;

class Reply extends \think\Controller {
  public function newReply(){
    $postData = input('post.');
    $user = session('user');
    $reply = new RepleyModel();
    $reply->content = $postData['content'];
    if (isset($postData['reply_id']) && intval($postData['reply_id']) > 0) {
      $reply->reply_id = intval($postData['reply_id']);
    } else {
      $reply->reply_id = 0;
    }
    $reply->topic_id = $postData['topic_id'];
    $reply->created_at = intval(microtime(true));
    $reply->user_id = $user->id;
    $reply->save();
    $this->success('回复成功！');
  }

}
