<?php
$arUrlRewrite=array (
  1 => 
  array (
    'CONDITION' => '#^/services/(.*)?/.*#',
    'RULE' => 'filter=$1',
    'ID' => 'electric:main.contractors',
    'PATH' => '/e/index.php',
    'SORT' => 10,
  ),
  12 => 
  array (
    'CONDITION' => '#^/backoffice/distributors/#',
    'RULE' => '',
    'ID' => 'electric:backoffice.distributors',
    'PATH' => '/backoffice/distributors/index.php',
    'SORT' => 100,
  ),
  14 => 
  array (
    'CONDITION' => '#^/backoffice/contractors/#',
    'RULE' => '',
    'ID' => 'electric:backoffice.contractors',
    'PATH' => '/backoffice/contractors/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/integration/(.*)?/.*#',
    'RULE' => 'component=$1',
    'ID' => 'integration',
    'PATH' => '/integration/index.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/order/success/(.*)?/#',
    'RULE' => 'ID=$1',
    'ID' => 'electric:order.element',
    'PATH' => '/order/success/index.php',
    'SORT' => 100,
  ),
  13 => 
  array (
    'CONDITION' => '#^/backoffice/vendors/#',
    'RULE' => '',
    'ID' => 'electric:backoffice.vendors',
    'PATH' => '/backoffice/vendors/index.php',
    'SORT' => 100,
  ),
  15 => 
  array (
    'CONDITION' => '#^/backoffice/clients/#',
    'RULE' => '',
    'ID' => 'electric:backoffice.clients',
    'PATH' => '/backoffice/clients/index.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/api/(.*)?/.*#',
    'RULE' => 'component=$1',
    'ID' => 'api',
    'PATH' => '/api/index.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/education/#',
    'RULE' => '',
    'ID' => 'electric:educations',
    'PATH' => '/education/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/articles/#',
    'RULE' => '',
    'ID' => 'electric:main',
    'PATH' => '/articles/index.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/events/#',
    'RULE' => '',
    'ID' => 'electric:events',
    'PATH' => '/events/index.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/order/#',
    'RULE' => '',
    'ID' => 'electric:order',
    'PATH' => '/order/index.php',
    'SORT' => 100,
  ),
  16 => 
  array (
    'CONDITION' => '#^/forum/#',
    'RULE' => '',
    'ID' => 'bitrix:forum',
    'PATH' => '/forum/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => 'electric:news',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/e/#',
    'RULE' => '',
    'ID' => 'electric:main.contractors',
    'PATH' => '/e/index.php',
    'SORT' => 100,
  ),
);
