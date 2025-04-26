<?php
error_reporting(0);
ini_set('display_errors', 0);

// 1) جلب المتغيّرات البيئية
$token = getenv('7610782352:AAG1_pkpRrRSz62D1Fe9CBw8DK_m77Q-iWw');
$admin = (int)getenv('7790070110');
define('API_KEY', $token);

// 2) تحديد $update و $from_id
//    تأكد من أنّ index.php يعرّف $update قبل require
if (!isset($update) || !is_array($update)) {
    exit;  // لا تحديثات، انهي السكربت
}
$from_id = $update['message']['from']['id'] ?? 0;

// 3) مسار التخزين
$storagePath = __DIR__ . '/storage';
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0755, true);
}

// 4) Health check لـ Render
if ($_SERVER['REQUEST_URI'] === '/health') {
    http_response_code(200);
    exit("OK");
}

// 5) (اختياري) عرض رابط setWebhook فقط عند طلب خاص
// if (isset($_GET['show_webhook_link'])) {
//     $webhookUrl = getenv('RENDER_EXTERNAL_URL') . '/index.php';
//     echo "<a href=\"https://api.telegram.org/bot".API_KEY.
//          "/setWebhook?url={$webhookUrl}\">{$webhookUrl}</a>";
//     exit;
// }

// 6) دالة الطلب إلى Telegram
function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        error_log(curl_error($ch));
        return null;
    }
    return json_decode($res, true);
}

// 7) مثال بسيط: رد ترحيبي
if (isset($update['message']['text'])) {
    bot('sendMessage', [
        'chat_id' => $from_id,
        'text'    => "أهلًا! لقد وصلت رسالتك: " . $update['message']['text']
    ]);
}

// 8) القسم الخاص بتحميل الملفات
if (isset($update['message']['document'])) {
    // جلب ملف JSON المُدخَل
    $file = bot("getFile", ['file_id' => $update['message']['document']['file_id']]);
    $filePath = $file['result']['file_path'] ?? '';
    $fileUrl = "https://api.telegram.org/file/bot".API_KEY."/".$filePath;

    // إنشاء مجلد للمستخدم
    $userStorage = $storagePath . "/user_" . $from_id;
    if (!is_dir($userStorage)) {
        mkdir($userStorage, 0755, true);
    }

    // حفظ الملف
    $fileName    = basename($filePath);
    $fileContent = file_get_contents($fileUrl);
    file_put_contents($userStorage . "/" . $fileName, $fileContent);

    // تأكيد للمستخدم
    bot('sendMessage', [
        'chat_id' => $from_id,
        'text'    => "تم حفظ الملف: {$fileName}"
    ]);
}

// يمكنك إضافة باقي منطق البوت هنا...
