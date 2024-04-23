<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Halaman Contoh
    |--------------------------------------------------------------------------
    */
    // 'page' => [
    //     'title' => 'Judul Halaman',
    //     'heading' => 'Judul Utama Halaman',
    //     'subheading' => 'Sub Judul Halaman',
    //     'navigationLabel' => 'Label Navigasi Halaman',
    //     'section' => [],
    //     'fields' => []
    // ],

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Umum
    |--------------------------------------------------------------------------
    */
    'general_settings' => [
        'title' => 'Pengaturan Umum',
        'heading' => 'Pengaturan Umum',
        'subheading' => 'Mengelola pengaturan umum situs di sini.',
        'navigationLabel' => 'Umum',
        'sections' => [
            "site" => [
                "title" => "Situs",
                "description" => "Mengelola pengaturan dasar."
            ],
            "theme" => [
                "title" => "Tema",
                "description" => "Ubah tema default."
            ],
        ],
        "fields" => [
            "brand_name" => "Nama Brand",
            "site_active" => "Status Situs",
            "brand_logoHeight" => "Tinggi Logo Brand",
            "brand_logo" => "Logo Brand",
            "site_favicon" => "Favicon Situs",
            "primary" => "Utama",
            "secondary" => "Sekunder",
            "gray" => "Abu-abu",
            "success" => "Sukses",
            "danger" => "Bahaya",
            "info" => "Informasi",
            "warning" => "Peringatan",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Email
    |--------------------------------------------------------------------------
    */
    'mail_settings' => [
        'title' => 'Pengaturan Email',
        'heading' => 'Pengaturan Email',
        'subheading' => 'Mengelola konfigurasi email.',
        'navigationLabel' => 'Email',
        'sections' => [
            "config" => [
                "title" => "Konfigurasi",
                "description" => "deskripsi"
            ],
            "sender" => [
                "title" => "Dari (Pengirim)",
                "description" => "deskripsi"
            ],
            "mail_to" => [
                "title" => "Kirim ke",
                "description" => "deskripsi"
            ],
        ],
        "fields" => [
            "placeholder" => [
                "receiver_email" => "Email penerima.."
            ],
            "driver" => "Driver",
            "host" => "Host",
            "port" => "Port",
            "encryption" => "Enkripsi",
            "timeout" => "Waktu Habis",
            "username" => "Nama Pengguna",
            "password" => "Kata Sandi",
            "email" => "Email",
            "name" => "Nama",
            "mail_to" => "Kirim ke",
        ],
        "actions" => [
            "send_test_mail" => "Kirim Email Percobaan"
        ]
    ],

];
