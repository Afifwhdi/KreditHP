<?php

date_default_timezone_set('Asia/Jakarta');
$baseUrl = 'http://127.0.0.1:8000';
$logFile = 'scheduler.log';

function writeLog($message, $logFile)
{
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
    echo $logMessage;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
}

function hitUrl($url)
{
    $context = stream_context_create([
        'http' => [
            'timeout' => 30,
            'method' => 'GET'
        ]
    ]);

    try {
        $response = file_get_contents($url, false, $context);
        return json_decode($response, true);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

writeLog("🚀 WA Reminder Scheduler dimulai...", $logFile);
writeLog("📍 Base URL: {$baseUrl}", $logFile);
writeLog("🕐 Timezone: Asia/Jakarta (WIB)", $logFile);
writeLog("📋 Log file: {$logFile}", $logFile);
writeLog("---", $logFile);

$checkCount = 0;
while (true) {
    $checkCount++;
    $currentTime = date('Y-m-d H:i:s');

    writeLog("🔄 Check #{$checkCount} - {$currentTime}", $logFile);

    $response = hitUrl("{$baseUrl}/cron/scheduled-reminder");

    if (isset($response['error'])) {
        writeLog("❌ Error: " . $response['error'], $logFile);
    } elseif (isset($response['status'])) {

        if ($response['status'] === 'waiting') {
            writeLog("⏰ Status: Waiting - " . ($response['countdown'] ?? 'no countdown'), $logFile);
        } elseif ($response['status'] === 'executed') {
            writeLog("✅ EXECUTED! Reminder berhasil dikirim!", $logFile);
            writeLog("📊 Total sent: " . ($response['total_sent'] ?? '0'), $logFile);

            if (isset($response['details']) && is_array($response['details'])) {
                foreach ($response['details'] as $detail) {
                    $customer = $detail['customer'] ?? 'Unknown';
                    $phone = $detail['phone'] ?? 'N/A';
                    $type = $detail['type'] ?? 'N/A';
                    $status = $detail['wa_status'] ?? 'Unknown';

                    writeLog("  → {$type} to {$customer} ({$phone}): {$status}", $logFile);
                }
            }

            writeLog("🎉 Scheduler selesai! Script akan berhenti.", $logFile);
            break;
        } else {
            writeLog("ℹ️ Status: " . $response['status'], $logFile);
        }
    } else {
        writeLog("⚠️ Unexpected response format", $logFile);
    }

    writeLog("💤 Sleeping 60 seconds...", $logFile);
    writeLog("", $logFile); // Empty line
    sleep(60);
}

writeLog("🛑 Scheduler stopped.", $logFile);
