<?php

namespace Drupal\forcontu_filters\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Add filter description here.
 *
 * @Filter(
 *   id = "filter_black_list",
 *   title = @Translation("Black list filter"),
 *   description = @Translation("Replaces all words from a black list"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_IRREVERSIBLE,
 *   settings = {
 *    "black_list" = "jiBa\nBB",
 *   }
 * )
 */
final class FilterBlackList extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode): FilterProcessResult {
    // $black_list = ['jiBa', 'BB'];
    // 在注解中添加默认配置值.
    /*
    使用 explode("\n", $this->settings['black_list']) 将换行符分隔的字符串转换为数组。
    使用 array_map('trim', ...) 去除每个词语的前后空格。
    使用 array_filter(...) 去除空字符串。
     */
    $black_list = array_filter(array_map('trim', explode("\n", $this->settings['black_list'])));

    // -------------------------------------------
    // 获取黑名单字符串.
    $black_list_string = $this->settings['black_list'];

    // 按换行符分割字符串.
    $black_list_array = explode("\n", $black_list_string);

    // 去除每个词语的前后空格.
    $trimmed_black_list = array_map('trim', $black_list_array);

    // 移除空字符串.
    // array_filter 没有回调函数, 移除所有等值为 false 的元素.
    $black_list = array_filter($trimmed_black_list);
    // -------------------------------------------
    $filtered_text = str_replace($black_list, '*****', $text);
    return new FilterProcessResult($filtered_text);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    // 配置变量与表单字段同名.
    $form['black_list'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Black list'),
      '#default_value' => $this->settings['black_list'],
      '#description' => $this->t('A list of words to be banned (one per line)'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE): string {
    return (string) $this->t('@todo Provide filter tips here.');
  }

}
