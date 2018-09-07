<?php
namespace app\admin\model;

use think\Model;

class User extends Model{
 

  public function topics() {
    return $this->hasMany('Topic', 'user_id');
  }
}
