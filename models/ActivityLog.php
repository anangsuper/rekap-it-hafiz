<?php
class ActivityLog {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function add($userId, $action, $description) {
        $sql = "INSERT INTO activity_logs (user_id, action, description) VALUES (?, ?, ?)";
        $result = $this->db->prepare($sql)->execute([$userId, $action, $description]);
        
        // Kirim notifikasi ke Telegram jika token & chat_id terkonfigurasi
        $this->sendTelegramNotification($userId, $action, $description);

        // Kirim notifikasi ke WhatsApp jika terkonfigurasi
        $this->sendWhatsAppNotification($userId, $action, $description);
        
        return $result;
    }

    private function sendWhatsAppNotification($userId, $action, $description) {
        $fonnteToken = (defined('FONNTE_TOKEN') && FONNTE_TOKEN !== '') ? FONNTE_TOKEN : getenv('FONNTE_TOKEN');
        $targetPhone = (defined('WHATSAPP_NUMBER') && WHATSAPP_NUMBER !== '') ? WHATSAPP_NUMBER : (getenv('WHATSAPP_NUMBER') ?: '089523140757');

        if (!$fonnteToken) {
            return; // Fonnte belum dikonfigurasi
        }

        try {
            // Ambil nama pengguna
            $stmt = $this->db->prepare("SELECT nama FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            $namaUser = $user ? $user['nama'] : 'Sistem';

            $message = "⚠️ *LOG AKTIVITAS REKAP IT*\n\n";
            $message .= "*Pengguna:* " . $namaUser . "\n";
            $message .= "*Aksi:* " . $action . "\n";
            $message .= "*Keterangan:* " . $description . "\n";
            $message .= "*Waktu:* " . date('d M Y H:i:s') . " WIB";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $targetPhone,
                    'message' => $message,
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $fonnteToken
                ),
            ));

            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                error_log("cURL Error (Fonnte WA): " . curl_error($curl));
            } else {
                error_log("Fonnte WA Response: " . $response);
            }
            curl_close($curl);
        } catch (Exception $e) {
            error_log("Gagal mengirim notifikasi WhatsApp: " . $e->getMessage());
        }
    }

    private function sendTelegramNotification($userId, $action, $description) {
        $botToken = (defined('TELEGRAM_BOT_TOKEN') && TELEGRAM_BOT_TOKEN !== '') ? TELEGRAM_BOT_TOKEN : getenv('TELEGRAM_BOT_TOKEN');
        $chatId = (defined('TELEGRAM_CHAT_ID') && TELEGRAM_CHAT_ID !== '') ? TELEGRAM_CHAT_ID : getenv('TELEGRAM_CHAT_ID');

        if (!$botToken || !$chatId) {
            return; // Telegram tidak dikonfigurasi
        }

        try {
            // Ambil nama pengguna
            $stmt = $this->db->prepare("SELECT nama FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            $namaUser = $user ? $user['nama'] : 'Sistem';

            $message = "⚠️ *LOG AKTIVITAS REKAP IT*\n\n";
            $message .= "*Pengguna:* " . $namaUser . "\n";
            $message .= "*Aksi:* " . $action . "\n";
            $message .= "*Keterangan:* " . $description . "\n";
            $message .= "*Waktu:* " . date('d M Y H:i:s') . " WIB";

            $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage";
            $data = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown'
            ];

            // Kirim menggunakan cURL secara cepat agar tidak menghambat load page user
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            error_log("Gagal mengirim notifikasi Telegram: " . $e->getMessage());
        }
    }

    public function getRecent($limit = 10) {
        $sql = "SELECT l.*, u.nama 
                FROM activity_logs l 
                LEFT JOIN users u ON l.user_id = u.id 
                ORDER BY l.id DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
