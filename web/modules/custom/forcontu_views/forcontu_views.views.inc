<?php

/**
 * @file
 * Views hooks for the Forcontu views module.
 */

/**
 * Implements hook_views_data().
 */
function forcontu_views_views_data() {
  $data = [];

  $data['forcontu_node_highlighted'] = [];
  $data['forcontu_node_highlighted']['table'] = [];
  $data['forcontu_node_highlighted']['table']['group'] = t('Forcontu Views');
  $data['forcontu_node_highlighted']['table']['provider'] = 'forcontu_views';
  $data['forcontu_node_highlighted']['table']['join'] = [
    'node_field_data' => [
      'left_field' => 'nid',
      'field' => 'nid',
    ],
  ];
  $data['forcontu_node_highlighted']['highlighted'] = [
    'title' => t('Highlighted content'),
    'help' => t('Boolean field for highlighted content.'),
    'field' => [
      'id' => 'boolean_icon',
      'click sortable' => TRUE,
      'output formats' => [
        'sticky' => [t('Highlighted'), t('Not Highlighted')],
      ],
    ],
    'sort' => [
      'id' => 'highlighted_order',
    ],
    'filter' => [
      'id' => 'boolean',
      'label' => t('Highlighted'),
      'type' => 'yes-no',
      'use_equal' => TRUE,
    ],
  ];

  $data['forcontu_node_highlighted']['highlighted_recent'] = [
    'title' => t('Highlighted recent content'),
    'help' => t('Highlighted recent content.'),
    'filter' => [
      'id' => 'highlighted_recent',
      'label' => t('Highlighted recent'),
    ],
  ];

  $data['forcontu_views_users'] = [];
  $data['forcontu_views_users']['table'] = [];
  $data['forcontu_views_users']['table']['group'] = t('Forcontu Views');
  $data['forcontu_views_users']['table']['provider'] = 'forcontu_views';
  // 将表添加为基础表，因此我们可以创建一个以“ Forcontu Views Users”作为基础元素的视图.
  $data['forcontu_views_users']['table']['base'] = [
    'field' => 'id',
    'title' => t('Forcontu Views Users'),
    'help' => t('Employee additional data'),
  ];

  $data['forcontu_views_users']['table']['join'] = [
    'users' => [
      'left_field' => 'uid',
      'field' => 'uid',
    ],
  ];

  $data['forcontu_views_users']['id'] = [
    'title' => t('ID'),
    'help' => t('The unique ID.'),
    'field' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
  ];

  $data['forcontu_views_users']['employee_code'] = [
    'title' => t('Employee code'),
    'help' => t('Employee code'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['forcontu_views_users']['job_title'] = [
    'title' => t('Job title'),
    'help' => t('Job title'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
  ];

  $data['forcontu_views_users']['uid'] = [
    'title' => t('User'),
    'help' => t('User object'),
    'relationship' => [
      'group' => t('Users'),
      'label' => t('The user from forcontu_views_users table'),
      'title' => t('User from forcontu_views_users table'),
      'help' => t('Get user entity.'),
      'base' => 'users_field_data',
      'base field' => 'uid',
      'relationship field' => 'uid',
      'id' => 'standard',
    ],
  ];
  return $data;
}

/**
 * Implements hook_views_data_alter().
 *
 * Using when add computed filed.
 */
function forcontu_views_views_data_alter(array &$data) {

  // Add custom computed field.
  if (isset($data['node_field_data'])) {
    $data['node_field_data']['published_by_on'] = [
      'title' => t('Published by'),
      'field' => [
        'id' => 'published_by_on',
      ],
    ];
  }

  // Don't success.
  // 检查分类术语中是否存在 "field_slug" 字段。.
  // Works only if "field_slug" is defined on taxonomy terms.
  if (isset($data['taxonomy_term__field_slug'])) {
    $field_slug = $data['taxonomy_term__field_slug']['field_slug'];

    // Create a new views argument for reading slug arguments and interpreting
    // them as taxonomy terms.
    $data['taxonomy_term__field_slug']['field_slug_entity_id'] = [
      'group' => $field_slug['group'],
      'title' => t('Custom: Has taxonomy term with slug'),
      'title short' => t('Slug'),
      'help' => $field_slug['help'],
      // We override the argument. This is where we do our tricks!
      'argument' => [
        'table' => 'taxonomy_term__field_slug',
        'field' => 'entity_id',
        'id' => 'term_slug',
        'zero is null' => TRUE,
      ],
    ];
  }
}
