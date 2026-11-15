<?php

return [

    'name' => env('FACTORY_NAME', 'Padma Button Works'),
    'timezone' => env('FACTORY_TIMEZONE', 'Asia/Dhaka'),
    'currency' => env('FACTORY_CURRENCY', 'BDT'),
    'overhead_rate' => (float) env('FACTORY_OVERHEAD_RATE', 0.15),
    'scrap_alert_ratio' => (float) env('FACTORY_SCRAP_ALERT', 0.08),

    'ai' => [
        'enabled' => (bool) env('FACTORY_AI_ENABLED', false),
    ],

    /*
    | Modules can be switched off without deleting tables.
    | Disabled modules hide navigation and reject writes.
    */
    'modules' => [
        'catalog' => true,
        'inventory' => true,
        'planning' => true,
        'shopfloor' => true,
        'quality' => true,
        'maintenance' => true,
        'hr' => true,
        'sales' => true,
        'procurement' => true,
        'finance' => true,
        'insights' => true,
        'system' => true,
    ],

    /*
    | view  = open records
    | manage = create and update
    | delete = destroy records
    | super_admin always passes.
    */
    'access' => [
        'catalog' => [
            'view' => ['production_manager', 'sales', 'qc', 'operator'],
            'manage' => ['production_manager', 'sales'],
            'delete' => ['production_manager'],
        ],
        'inventory' => [
            'view' => ['production_manager', 'accountant', 'operator'],
            'manage' => ['production_manager', 'accountant'],
            'delete' => ['production_manager'],
        ],
        'planning' => [
            'view' => ['production_manager', 'sales'],
            'manage' => ['production_manager'],
            'delete' => ['production_manager'],
        ],
        'shopfloor' => [
            'view' => ['production_manager', 'qc', 'operator', 'hr'],
            'manage' => ['production_manager', 'operator', 'qc'],
            'delete' => ['production_manager'],
        ],
        'quality' => [
            'view' => ['production_manager', 'qc', 'operator'],
            'manage' => ['production_manager', 'qc'],
            'delete' => ['production_manager'],
        ],
        'maintenance' => [
            'view' => ['production_manager', 'operator'],
            'manage' => ['production_manager'],
            'delete' => ['production_manager'],
        ],
        'hr' => [
            'view' => ['hr', 'production_manager'],
            'manage' => ['hr'],
            'delete' => ['hr'],
        ],
        'sales' => [
            'view' => ['sales', 'production_manager', 'accountant'],
            'manage' => ['sales'],
            'delete' => ['sales'],
        ],
        'procurement' => [
            'view' => ['production_manager', 'accountant'],
            'manage' => ['production_manager', 'accountant'],
            'delete' => ['production_manager'],
        ],
        'finance' => [
            'view' => ['accountant', 'sales', 'production_manager'],
            'manage' => ['accountant'],
            'delete' => ['accountant'],
        ],
        'insights' => [
            'view' => ['production_manager', 'sales', 'accountant', 'qc', 'hr'],
            'manage' => ['production_manager'],
            'delete' => [],
        ],
        'system' => [
            'view' => ['production_manager'],
            'manage' => [],
            'delete' => [],
        ],
    ],

    'polyester_stages' => [
        ['code' => 'mixing', 'name' => 'Mixing / Formula preparation', 'minutes_per_thousand' => 25],
        ['code' => 'casting', 'name' => 'Sheet or rod casting', 'minutes_per_thousand' => 40],
        ['code' => 'blanking', 'name' => 'Blanking / Cutting', 'minutes_per_thousand' => 20],
        ['code' => 'turning', 'name' => 'Turning / Machining', 'minutes_per_thousand' => 55],
        ['code' => 'drilling', 'name' => 'Drilling', 'minutes_per_thousand' => 35],
        ['code' => 'polishing', 'name' => 'Polishing / Finishing', 'minutes_per_thousand' => 45],
        ['code' => 'final_qc', 'name' => 'Final QC', 'minutes_per_thousand' => 15],
        ['code' => 'packaging', 'name' => 'Packaging', 'minutes_per_thousand' => 12],
    ],

    'shell_stages' => [
        ['code' => 'shell_selection', 'name' => 'Shell selection', 'minutes_per_thousand' => 20],
        ['code' => 'punching', 'name' => 'Punching', 'minutes_per_thousand' => 30],
        ['code' => 'grinding', 'name' => 'Grinding', 'minutes_per_thousand' => 35],
        ['code' => 'grooving', 'name' => 'Grooving', 'minutes_per_thousand' => 25],
        ['code' => 'drilling', 'name' => 'Drilling', 'minutes_per_thousand' => 30],
        ['code' => 'scraping', 'name' => 'Scraping', 'minutes_per_thousand' => 20],
        ['code' => 'polishing_bleaching', 'name' => 'Polishing & bleaching', 'minutes_per_thousand' => 40],
        ['code' => 'final_qc', 'name' => 'Final QC', 'minutes_per_thousand' => 15],
        ['code' => 'packaging', 'name' => 'Packaging', 'minutes_per_thousand' => 12],
    ],

    'defect_codes' => [
        'off_center_holes' => 'Off-center holes',
        'cracked_edges' => 'Cracked edges',
        'thickness_variation' => 'Thickness variation',
        'color_mismatch' => 'Color mismatch',
        'bubbles' => 'Bubbles / voids',
        'poor_polish' => 'Poor polish',
        'chips' => 'Edge chips',
        'wrong_size' => 'Wrong ligne size',
        'contamination' => 'Foreign contamination',
    ],
];
