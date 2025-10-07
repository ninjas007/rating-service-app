<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class DecryptTextCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:decrypt {text?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Decrypt text using Laravel Crypt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil dari argument atau kalau kosong minta lewat prompt
        $encrypted = $this->argument('text') ?? $this->ask('Masukkan teks terenkripsi');

        try {
            $decrypted = decrypt_text($encrypted);

            $this->info("Hasil dekripsi:");
            $this->line($decrypted);
        } catch (\Exception $e) {
            $this->error("Gagal mendekripsi: " . $e->getMessage());
        }
    }
}
