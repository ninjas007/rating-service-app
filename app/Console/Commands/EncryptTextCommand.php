<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class EncryptTextCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:encrypt {text?}';
    // ⬆ text? = optional, jadi bisa kasih langsung di command, bisa juga kosong

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt a given text using Laravel Crypt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil dari argument atau kalau kosong minta lewat prompt
        $text = $this->argument('text') ?? $this->ask('Masukkan teks yang ingin dienkripsi');

        try {
            $encrypted = encrypt_text($text);
            $this->info("Hasil enkripsi:");
            $this->line($encrypted);

        } catch (\Exception $e) {
            $this->error("Gagal mengenkripsi: " . $e->getMessage());
        }
    }
}
