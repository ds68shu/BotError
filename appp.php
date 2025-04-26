<?php
error_reporting(0);
ini_set('display_errors', 0);

// الحصول على المتغيرات من إعدادات Render
$token = getenv('7610782352:AAG1_pkpRrRSz62D1Fe9CBw8DK_m77Q-iWw');
$admin = (int)getenv('7790070110');
define('API_KEY', $token);

// إعداد مسارات التخزين الدائمة
$storagePath = __DIR__ . '/storage';
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0755, true);
}

// وظيفة التحقق من صحة الخدمة لـ Render
if ($_SERVER['REQUEST_URI'] == '/health') {
    http_response_code(200);
    exit("OK");
}

// عرض رابط SetWebhook مع عنوان Render الخارجي
$webhookUrl = getenv('RENDER_EXTERNAL_URL') . '/index.php';
echo "setWebhook ~> <a href=\"https://api.telegram.org/bot".API_KEY."/setwebhook?url={$webhookUrl}\">{$webhookUrl}</a>";

function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if(curl_error($ch)) {
        error_log(curl_error($ch));
    } else {
        return json_decode($res);
    }
}

// البقية من الكود مع تعديل المسارات
$usrbot = bot("getme")->result->username;
$emoji = "➡️\n🎟️\n↪️\n🔘\n🏠";
$emoji = explode("\n", $emoji);
$b = $emoji[rand(0, 4)];
$NamesBACK = "رجوع $b";

define("USR_BOT", $usrbot);

function SETJSON($INPUT) {
    global $storagePath;
    $F = $storagePath . "/UploadEr.json";
    $N = json_encode($INPUT, JSON_PRETTY_PRINT);
    file_put_contents($F, $N);
}

// التعديل على مسار قراءة الملف
$uploaderFile = $storagePath . "/UploadEr.json";
if (!file_exists($uploaderFile)) {
    file_put_contents($uploaderFile, '{}');
}
$UploadEr = json_decode(file_get_contents($uploaderFile), true);

// ... (بقية الكود الأصلي مع استبدال جميع مراجع UploadEr.json بالمسار الجديد)

// تعديل قسم رفع الملفات
if($update->message->document) {
    $file_id = "https://api.telegram.org/file/bot".API_KEY."/".bot("getfile",["file_id"=>$update->message->document->file_id])->result->file_path;
    
    // إنشاء مجلد خاص لكل مستخدم
    $userStorage = $storagePath . "/user_" . $from_id;
    if (!is_dir($userStorage)) {
        mkdir($userStorage, 0755, true);
    }
    
    // حفظ الملف في مسار المستخدم
    $fileName = basename($file_id);
    $fileContent = file_get_contents($file_id);
    file_put_contents($userStorage . "/" . $fileName, $fileContent);
    
    // ... (بقية معالجة الملف)
}

// أعد كتابة جميع الإشارات إلى UploadEr.json لاستخدام المسار الجديد
// واستبدل جميع mkdir("UploadEr") بالمسار $storagePath