<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup database Laravel dan simpan dalam file zip ber-password tanpa menyimpan file .sql';

    public function handle()
    {
        try {
            $this->info('⏳ Memulai proses backup...');

            $database   = decrypt_text(env('DB_DATABASE'));
            $username   = decrypt_text(env('DB_USERNAME'));
            $password   = decrypt_text(env('DB_PASSWORD'));
            $host       = decrypt_text(env('DB_HOST'));
            $zipPassword = env('BP', 'BIOEXPERIENCE123'); // default password
            $backupPath = env('BPATH', storage_path('app/backups/'));

            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0777, true);
            }

            $timestamp = date('Ymd_His');
            $zipFile = "{$backupPath}{$timestamp}.7z";

            // Path ke 7z.exe di Windows
            $sevenZip = '"C:\\Program Files\\7-Zip\\7z.exe"';

            // Command gabungan: dump langsung ke 7z
            $command = "mysqldump --quick --single-transaction --user={$username} --password={$password} --host={$host} {$database} | {$sevenZip} a -si{$timestamp}.sql -p{$zipPassword} -y \"{$zipFile}\"";

            $this->info("📦 Membuat zip backup ber-password...");
            $result = null;
            system($command, $result);

            if ($result === 0 && file_exists($zipFile)) {
                $size = filesize($zipFile);
                $sizeMB = round($size / 1048576, 2);
                $this->info("✅ Backup selesai: {$zipFile} ({$sizeMB} MB, password protected)");
            } else {
                $this->error("❌ Gagal membuat backup.");
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }
    }
}
