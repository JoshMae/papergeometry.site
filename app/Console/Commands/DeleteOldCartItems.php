<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldCartItems extends Command
{
    protected $signature = 'cart:delete-old';
    protected $description = 'Delete cart items that are older than 24 hours';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $limitTime = now()->subDay();
        DB::table('carrito')->where('created_at', '<', $limitTime)->delete();
        $this->info('Old cart items deleted successfully.');
    }
}
