<?php
$content = file_get_contents('c:\xampp\htdocs\whiteapp1\document\checklist\type\view\marine-offshore-cranes.php');
preg_match_all('/<td><strong>(\d+\.\d+)<\/strong><\/td>\s*<td><strong>(.*?)<\/strong><\/td>\s*<td style="text-align: center;"><strong>(.*?)<\/strong><\/td>/s', $content, $matches);
$items = [];
for ($i = 0; $i < count($matches[1]); $i++) {
    $items[] = [
        'number' => $matches[1][$i],
        'text' => trim($matches[2][$i]),
        'ref' => trim($matches[3][$i])
    ];
}
echo json_encode($items, JSON_PRETTY_PRINT);
?>