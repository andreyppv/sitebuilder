<?php

$base_url = theme_url($site->theme->path) . '/';
$params = $page->elementsIndex();
$items = $page->dndElements;
$view = $site->theme->path . '.' . $page->key;
?>

@include($view, compact('page', 'params', 'newElements'))            