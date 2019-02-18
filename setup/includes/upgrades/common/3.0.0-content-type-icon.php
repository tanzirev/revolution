<?php
/**
 * Upgrade script for adding icon field to content types.
 *
 * @var modX
 * @package setup
 */

$class = 'modContentType';
$column = 'icon';

$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column', ['column' => $column, 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'addField'], [$class, $column]);


$map = [
    'text/html' => '',
    'text/xml' => 'icon-xml',
    'text/plain' => 'icon-txt',
    'text/css' => 'icon-css',
    'text/javascript' => 'icon-js',
    'application/rss+xml' => 'icon-rss',
    'application/json' => 'icon-json',
    'application/pdf' => 'icon-pdf',
];

$succeeded = $failed = 0;

/** @var modContentType $contentType */
foreach ($modx->getIterator($class) as $contentType) {
    $data = $contentType->toArray();
    $defaultValue = array_key_exists($data['mime_type'], $map)
        ? $map[$data['mime_type']]
        : '';

    if (empty($data['icon'])) {
        $contentType->set($column, $defaultValue);
        $contentType->save();
    }
}
