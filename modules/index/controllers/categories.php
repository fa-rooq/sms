<?php
/**
 * @filesource modules/index/controllers/categories.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Categories;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=index-categories.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * หมวดหมู่หลัก
   *
   * @param Request $request
   *
   * @return string
   */
  public function render(Request $request)
  {
    $index = (object)array(
        // ประเภทที่ต้องการ
        'type' => $request->request('type')->topic(),
        // ชื่อหมวดหมู่ที่สามารถใช้งานได้
        'categories' => Language::get('CATEGORIES'),
    );
    if (!isset($index->categories[$index->type])) {
      $index->type = \Kotchasan\ArrayTool::getFirstKey($index->categories);
    }
    // ข้อความ title bar
    $title = $index->categories[$index->type];
    $this->title = Language::trans('{LNG_List of} ').$title;
    // เลือกเมนู
    $this->menu = 'settings';
    // สามารถตั้งค่าระบบได้
    if (Login::checkPermission(Login::isMember(), 'can_config')) {
      // แสดงผล
      $section = Html::create('section', array(
          'class' => 'content_bg',
      ));
      // breadcrumbs
      $breadcrumbs = $section->add('div', array(
        'class' => 'breadcrumbs',
      ));
      $ul = $breadcrumbs->add('ul');
      $ul->appendChild('<li><span class="icon-settings">{LNG_Settings}</span></li>');
      $ul->appendChild('<li><span>'.$title.'</span></li>');
      $section->add('header', array(
        'innerHTML' => '<h2 class="icon-category">'.$this->title.'</h2>',
      ));
      // แสดงฟอร์ม
      $section->appendChild(createClass('Index\Categories\View')->render($index));

      return $section->render();
    }
    // 404

    return \Index\Error\Controller::execute($this);
  }
}