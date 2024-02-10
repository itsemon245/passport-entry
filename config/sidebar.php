<?php

return [
    'Dashboard' => (object)[
        "name" => 'dashboard',
        'icon'=> '<x-heroicon-o-home class="w-5 h-5"/>',
        'isAdminOnly'=> false,
        'isUserOnly'=> false
    ],
    'New Entry' => (object)[
        "name" => 'entry.index',
        'icon' => '<x-heroicon-o-document-plus class="w-5 h-5"/>',
        'isAdminOnly'=> true,
        'isUserOnly'=> false
    ],
    'Report' => (object)[
        "name" => 'report.index',
        'icon' => '<x-heroicon-o-clipboard-document-list class="w-5 h-5"/>',
        'isAdminOnly'=> true,
        'isUserOnly'=> false
    ],
    'My Report' => (object)[
        "name" => 'report.client',
        'icon' => '<x-heroicon-o-document-chart-bar class="w-5 h-5"/>',
        'isAdminOnly'=> false,
        'isUserOnly'=> true,
    ],
    'Clients' => (object)[
        "name" => 'client.index',
        'icon' => '<x-heroicon-o-user-group class="w-5 h-5"/>',
        'isAdminOnly'=> true,
        'isUserOnly'=> false
    ],
    'Payment Entry' => (object)[
        "name" => 'payment.index',
        'icon' => '<x-heroicon-o-currency-bangladeshi class="w-5 h-5"/>',
        'isAdminOnly'=> true,
        'isUserOnly'=> false
    ],
    'Payment History' => (object)[
        "name" => 'payment.history',
        'icon' => '<x-heroicon-o-clock class="w-5 h-5"/>',
        'isAdminOnly'=> true,
        'isUserOnly'=> false
    ],
];