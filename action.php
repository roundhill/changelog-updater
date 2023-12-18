<?php

require 'vendor/autoload.php';

if (count($argv) < 5) {
    echo 'Usage: php action.php <changelog-contents> <release> <description> <entry-title> [entry-link]';
    exit(1);
}

$file = $argv[1];
$release = $argv[2];
$description = $argv[3];
$title = $argv[4];
$link = ! empty($argv[5]) ? $argv[5] : null;

if (! file_exists($file)) {
    echo 'Provided changelog is empty';
    exit(1);
}

preg_match('/## Changelog:(.*?)\n\n/s', $description, $matches);
$changelogText = $matches[1] ?? '';
$visibility = strpos($changelogText, '- [x]') !== false ? 'Public:' : 'Internal:';

$descriptionToken = 'PR title_:';
$description = strstr($changelogText, $descriptionToken);
if ($description !== false) {
    $description = substr($description, strlen($descriptionToken));
    $description = trim($description);
}

$changelogTitle = !empty($description) ? $description : $title;

$changelog = \ClaudioDekker\ChangelogUpdater\ChangelogParser::parse(file_get_contents($file));
$changelog->release($release)->section($visibility)->addEntry($changelogTitle, $link);

file_put_contents($file, (string) $changelog);

exit(0);