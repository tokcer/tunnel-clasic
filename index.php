<?php
function feedback404() {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Babi Kali Betul Ya!</h1>";
    echo "Lah dasar babi, sudah dibilangin masih baca terus dasar BABI!!";
    exit();
}

// PERBAIKAN DI SINI: Jika parameter "games" TIDAK ADA/KOSONG, muat home.php (Domain Utama)
if (!isset($_GET['games']) || empty(trim($_GET['games']))) {
    if (file_exists('home.php')) {
        include('home.php');
    } else {
        feedback404();
    }
    exit();
}

$file1 = "list1.txt";
$file2 = "list2.txt";
$filePelengkap = "pelengkap.txt";

if (!file_exists($file1) || !file_exists($file2)) {
    feedback404();
}

$list1 = file($file1, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$list2 = file($file2, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$pelengkap = file_exists($filePelengkap) 
             ? file($filePelengkap, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) 
             : [];

$target_string = strtolower(trim($_GET['games']));

$matched_index = -1;
$brand_A = "";
$brand_B = "";

// Cek di list1.txt
foreach ($list1 as $index => $item) {
    if (strtolower(trim($item)) === $target_string) {
        $matched_index = $index;
        $brand_A = strtoupper(trim($list1[$index]));
        if (isset($list2[$index])) {
            $brand_B = strtoupper(trim($list2[$index]));
        }
        break;
    }
}

// Jika tidak ketemu di list1, cek di list2.txt
if ($matched_index === -1) {
    foreach ($list2 as $index => $item) {
        if (strtolower(trim($item)) === $target_string) {
            $matched_index = $index;
            $brand_A = strtoupper(trim($list1[$index]));
            if (isset($list2[$index])) {
                $brand_B = strtoupper(trim($list2[$index]));
            }
            break;
        }
    }
}

// Jika tidak ditemukan di kedua list, panggil fungsi 404
if ($matched_index === -1 || empty($brand_A) || empty($brand_B)) {
    feedback404();
}

// Ambil pelengkap secara berputar (modulo) jika file pelengkap tersedia
$selected_pelengkap = "";
if (!empty($pelengkap)) {
    $p_Index = $matched_index % count($pelengkap);
    $selected_pelengkap = trim($pelengkap[$p_Index]);
}

// Gabungkan Brand A, Brand B, dan Pelengkap (Gunakan $BRANDS agar sinkron dengan template)
$BRANDS = trim($brand_A . " x " . $brand_B . " " . $selected_pelengkap);

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$fullUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$slug_A = strtolower(str_replace(' ', '-', trim($brand_A)));
$slug_B = strtolower(str_replace(' ', '-', trim($brand_B)));
$ampUrl = "https://yuk-mari.online/" . $slug_A . "-" . $slug_B;
?>
