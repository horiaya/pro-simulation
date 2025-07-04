<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;

class CleanupOldTransactionMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:cleanup-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete messages and images for completed transactions older than 7 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Transaction::where('status', 'completed')
            ->where('completed_at', '<', now()->subDays(7))
            ->each(function ($transaction) {
                foreach ($transaction->messages as $message) {
                    if ($message->image_path) {
                        Storage::disk('public')->delete($message->image_path);
                    }
                    $message->delete();
                }
            });

        $this->info('古い取引メッセージを削除しました！');
    }
}
