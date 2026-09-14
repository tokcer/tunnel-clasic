<?php
header("Content-Type: application/xml; charset=utf-8");

$file1 = "list1.txt";
$file2 = "list2.txt";

if (!file_exists($file1) || !file_exists($file2)) {
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
    echo '</urlset>';
    exit();
}

$list1 = file($file1, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$list2 = file($file2, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$current_date = gmdate('Y-m-d\TH:i:s+00:00');

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;
echo '<!--    created with Free Online Sitemap Generator www.xml-sitemaps.com    -->' . PHP_EOL;

// Halaman Utama (Homepage)
echo '<url>' . PHP_EOL;
echo '<loc>' . $protocol . '://' . $host . '/</loc>' . PHP_EOL;
echo '<lastmod>' . $current_date . '</lastmod>' . PHP_EOL;
echo '<priority>1.00</priority>' . PHP_EOL;
echo '</url>' . PHP_EOL;

// Daftar Parameter Brand dari list1
$total_rows = max(count($list1), count($list2));

for ($i = 0; $i < $total_rows; $i++) {
    if (isset($list1[$i]) && !empty(trim($list1[$i]))) {
        $slug = trim($list1[$i]);
        $loc = $protocol . '://' . $host . '/?games=' . urlencode($slug);
        
        echo '<url>' . PHP_EOL;
        echo '<loc>' . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
        echo '<lastmod>' . $current_date . '</lastmod>' . PHP_EOL;
        echo '<priority>1.00</priority>' . PHP_EOL;
        echo '</url>' . PHP_EOL;
    }
}

echo '</urlset>';
?>
