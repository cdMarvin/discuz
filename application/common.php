<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

function getCategoryNames($id) {
  $category = config('category');
  foreach($category as $key => $cat) {
    foreach($cat as $catId => $categoryName) {
      if ($catId == $id) {
        return [$key, $categoryName];
      }
    }
  }
  return [];
}
