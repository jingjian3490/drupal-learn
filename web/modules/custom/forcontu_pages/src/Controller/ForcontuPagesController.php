<?php

namespace Drupal\forcontu_pages\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\user\UserInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Returns responses for Forcontu pages routes.
 */
class ForcontuPagesController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build(): array {

    $build['content'] = [
      '#markup' => $this->t('This is a simple page (with no arguments)'),
    ];
    return $build;
  }

  /**
   * Builds the response.
   */
  public function calculator($num1, $num2): array {
    // a) 检查提供的值是否为数字，如果不是，则抛出异常.
    if (!is_numeric($num1) || !is_numeric($num2)) {
      throw new BadRequestHttpException(t('No numeric arguments specified.'));
    }

    // b) 结果将以 HTML 列表（ul）格式显示。每个列表项添加到一个数组中.
    $list[] = $this->t('@num1 + @num2 = @sum', [
      '@num1' => $num1,
      '@num2' => $num2,
      '@sum' => $num1 + $num2,
    ]);
    $list[] = $this->t('@num1 - @num2 = @difference', [
      '@num1' => $num1,
      '@num2' => $num2,
      '@difference' => $num1 - $num2,
    ]);
    $list[] = $this->t('@num1 x @num2 = @product', [
      '@num1' => $num1,
      '@num2' => $num2,
      '@product' => $num1 * $num2,
    ]);

    // c) 避免除零错误.
    if ($num2 != 0) {
      $list[] = $this->t('@num1 / @num2 = @division', [
        '@num1' => $num1,
        '@num2' => $num2,
        '@division' => $num1 / $num2,
      ]);
    }
    else {
      $list[] = $this->t('@num1 / @num2 = undefined (division by zero)', [
        '@num1' => $num1,
        '@num2' => $num2,
      ]);
    }

    // d) 将数组 $list 转换为 HTML 列表（ul）.
    $output['forcontu_pages_calculator'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('Operations:'),
    ];

    // e) 返回包含输出的可渲染数组.
    return $output;
  }

  /**
   * Builds the response.
   */
  public function user(UserInterface $user): array {
    // 可以直接使用 $user 对象.
    $list[] = $this->t("Username: @username", ['@username' => $user->getAccountName()]);
    $list[] = $this->t("Email: @email", ['@email' => $user->getEmail()]);
    $list[] = $this->t("Roles: @roles", ['@roles' => implode(', ', $user->getRoles())]);
    $list[] = $this->t("Last accessed time: @lastaccess", [
      '@lastaccess' => \Drupal::service('date.formatter')->format($user->getLastAccessedTime(), 'short'),
    ]);

    $output['forcontu_pages_user'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('User data:'),
    ];

    return $output;
  }

  /**
   * T.
   */
  public function links() {
    // 链接到 /admin/structure/blocks.
    $url1 = Url::fromRoute('block.admin_display');
    $link1 = Link::fromTextAndUrl($this->t('Go to the Block administration page'), $url1);
    $list[] = $link1;
    // On the front-end page, the render display effect
    // of '$link1->toString()' is the same as '$link1'.
    $list[] = $link1->toString();
    $list[] = $this->t('This text contains a link to %link. Just convert it to String to use it into a text.', [
      '%link' => $link1->toString(),
    ]);

    // 链接到 <front>.
    $url3 = Url::fromRoute('<front>');
    $link3 = Link::fromTextAndUrl($this->t('Go to Front page'), $url3);
    $list[] = $link3;

    // 链接到 /node/1.
    $url4 = Url::fromRoute('entity.node.canonical', ['node' => 1]);
    $link4 = Link::fromTextAndUrl($this->t('Link to node/1'), $url4);
    $list[] = $link4;

    // 链接到 /node/1/edit.
    $url5 = Url::fromRoute('entity.node.edit_form', ['node' => 1]);
    $link5 = Link::fromTextAndUrl($this->t('Link to edit node/1'), $url5);
    $list[] = $link5;

    // 链接到 https://www.forcontu.com.
    $url6 = Url::fromUri('https://www.forcontu.com');
    $link6 = Link::fromTextAndUrl($this->t('Link to www.forcontu.com'), $url6);
    $list[] = $link6;

    // 链接到内部 CSS 文件.
    $url7 = Url::fromUri('internal:/core/themes/bartik/css/layout.css');
    $link7 = Link::fromTextAndUrl($this->t('Link to layout.css'), $url7);
    $list[] = $link7;

    // 链接到内部 Link.
    $url7 = Url::fromUri('internal:/forcontu/pages/calculator/');
    $link7 = Link::fromTextAndUrl($this->t('Link to calculator'), $url7);
    $list[] = $link7;

    // 链接到 https://www.drupal.org 并添加额外的属性.
    $url8 = Url::fromUri('https://www.drupal.org');
    $link_options = [
      'attributes' => [
        'class' => [
          'external-link',
          'list',
        ],
        'target' => '_blank',
        'title' => 'Go to drupal.org',
      ],
    ];
    $url8->setOptions($link_options);
    $link8 = Link::fromTextAndUrl($this->t('Link to drupal.org'), $url8);
    $list[] = $link8;

    $output['forcontu_pages_links'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('Examples of links:'),
    ];
    return $output;
  }

}
