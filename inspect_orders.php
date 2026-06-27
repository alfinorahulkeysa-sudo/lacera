<?php
require __DIR__ . '/vendor/autoload.php';
$bootstrap = require __DIR__ . '/bootstrap/app.php';
$kernel = $bootstrap->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$orders = DB::table('orders')->orderBy('id','desc')->limit(20)->get();
foreach ($orders as $order) {
    echo 'id=' . $order->id
        . ' user_id=' . $order->user_id
        . ' status=' . $order->status
        . ' total_price=' . $order->total_price
        . ' order_number=' . $order->order_number
        . ' payment_method=' . ($order->payment_method ?? 'NULL')
        . ' payment_reference=' . ($order->payment_reference ?? 'NULL')
        . ' payment_url=' . ($order->payment_url ?? 'NULL')
        . ' paid_at=' . ($order->paid_at ?? 'NULL')
        . "\n";
}
