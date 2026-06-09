<?php
$viewPath = __DIR__ . '/document/checklist/type/view/marine-offshore-cranes.php';
$content = file_get_contents($viewPath);
if ($content === false) {
    fwrite(STDERR, "Could not read view file.\n");
    exit(1);
}
$pattern = '/<td><strong>(\d+\.\d+)<\/strong><\/td>\s*<td><strong>(.*?)<\/strong><\/td>\s*<td style="text-align: center;"><strong>(.*?)<\/strong><\/td>/s';
preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
$sections = [];
foreach ($matches as $match) {
    $number = $match[1];
    $item = trim(preg_replace('/\s+/', ' ', strip_tags($match[2])));
    $ref = trim(preg_replace('/\s+/', ' ', strip_tags($match[3])));
    $section = explode('.', $number)[0];
    $sections[$section][] = ['number' => $number, 'item' => $item, 'ref' => $ref];
}
$sectionTitles = [
    '1' => '1. GENERAL REQUIREMENTS',
    '2' => '2. INSPECTION POINTS',
    '3' => '3. INSPECTION POINTS',
];
$php = "<?php\n";
$php .= "$sections = [\n";
foreach ($sectionTitles as $key => $title) {
    if (!isset($sections[$key])) continue;
    $php .= "    '" . addslashes($title) . "' => [\n";
    foreach ($sections[$key] as $item) {
        $php .= "        [\n";
        $php .= "            'number' => '" . addslashes($item['number']) . "',\n";
        $php .= "            'item' => '" . addslashes($item['item']) . "',\n";
        $php .= "            'ref' => '" . addslashes($item['ref']) . "',\n";
        $php .= "        ],\n";
    }
    $php .= "    ],\n";
}
$php .= "];\n";
file_put_contents(__DIR__ . '/marine_sections.php', $php);
echo "Wrote marine_sections.php\n";
