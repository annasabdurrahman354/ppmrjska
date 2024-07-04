<?php

use App\Models\JurnalKelas;
use App\Models\MateriSurat;
use Illuminate\Support\Str;

if(! function_exists('getMediaFilename')) {
    function getMediaFilename($model, $media){
        switch($media->collection_name){
            case 'asrama_foto':
                return 'asrama_foto' . '_' . Str::slug($model->nama). '_' . $media->id . '.' . $media->extension;
            case 'lokasi_foto':
                return 'lokasi_foto' . '_' . Str::slug($model->nama). '_' . $media->id . '.' . $media->extension;
            case 'universitas_foto':
                return 'universitas_foto' . '_' . Str::slug($model->nama). '_' . $media->id . '.' . $media->extension;
            case 'carousel_cover':
                return 'carousel_cover' . '_' . Str::slug($model->judul). '_' . $media->id . '.' . $media->extension;
            case 'blog_cover':
                return 'blog_cover' . '_' . Str::slug($model->judul). '_' . $media->id . '.' . $media->extension;
            case 'media_cover':
                return 'media_cover' . '_' . Str::slug($model->judul). '_' . $media->id . '.' . $media->extension;
            case 'dewan_guru_avatar':
                return 'dewan_guru_avatar' . '_' . Str::slug($model->nama). '_' . $media->id . '.' . $media->extension;
            case 'user_avatar':
                return 'user_avatar' . '_' . Str::slug($model->nama). '_' . $media->id . '.' . $media->extension;
            default:
                return Str::slug($media->file_name) . '_' . $media->id . '.' . $media->extension;
        }
    }
}


if(!function_exists('isKedisiplinan')) {
    function isKedisiplinan() {
        return auth()->user()->hasRole('dmcp_kedisiplinan');
    }
}

if(!function_exists('isKeilmuan')) {
    function isKeilmuan() {
        return auth()->user()->hasRole('dmcp_keilmuan');
    }
}

if(!function_exists('isDmcPasus')) {
    function isDmcPasus() {
        return auth()->user()->hasRole('dmcp%');
    }
}

if(!function_exists('isSuperAdmin')) {
    function isSuperAdmin() {
        return auth()->user()->hasRole(config('filament-shield.super_admin.name'));
    }
}

if(!function_exists('isNotSuperAdmin')) {
    function isNotSuperAdmin() {
        return !auth()->user()->hasRole(config('filament-shield.super_admin.name'));
    }
}

if(!function_exists('cant')) {
    function cant($abilities) {
        if (isSuperAdmin()){
            return false;
        }
        else return !auth()->user()->can($abilities);
    }
}

if(!function_exists('can')) {
    function can($abilities) {
        if (isSuperAdmin()){
            return true;
        }
        else return auth()->user()->can($abilities);
    }
}

if(!function_exists('getRekamanFilename')) {
    function getRekamanFilename(JurnalKelas $record) {
        $result = '';
        if ($record->materi_awal_type == MateriSurat::class){
            $result = "Al-Quran_" . $record->halaman_awal . ( $record->halaman_akhir !== "" ? "-" . $record->halaman_akhir : "") . "_";
            $result .= "(" . $record->materiAwal->nomor . ") " . $record->materiAwal->nama . "_" . $record->ayat_awal . "-" . ($record->materiAwal->id !== $record->materiAkhir->id ? "(" . $record->materiAkhir->nomor . ") " . $record->materiAkhir->nama . "_" . $record->ayat_akhir : $record->ayat_akhir);
            $result .= "_" . '['.implode(",", $record->kelas).']' . "_" . $record->dewanGuru->nama_panggilan . "_" . $record->tanggal->format('d-m-Y'). "_" . $record->sesi->getLabel();
        }
        else {
            $result = $record->materiAwal->nama . "_" . $record->halaman_awal . "-" . ($record->materiAwal->id !== $record->materiAkhir->id ? $record->materiAkhir->nama . "_" . $record->halaman_akhir : $record->halaman_akhir);
            $result .= "_" . '['.implode(",", $record->kelas).']' . "_" . $record->dewanGuru->nama_panggilan . "_" . $record->tanggal->format('d-m-Y'). "_" . $record->sesi->getLabel();
        }
        return $result;
    }
}

if(!function_exists('getJenjangProgramStudi')) {
    function getJenjangProgramStudi($string) {
        $index = strpos($string, "-");
        return substr($string, 0, $index);
    }
}

if(!function_exists('getProgramStudi')) {
    function getProgramStudi($string) {
        $index = strpos($string, "-");
        return substr($string, $index + 1);
    }
}

if(!function_exists('matchPatternProgramStudi')) {
    function matchPatternProgramStudi($string) {
        $pattern = '/^[a-zA-Z]\d-\w+$/';

        if (preg_match($pattern, $string)) {
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists('getProgramStudiList')) {
    function getProgramStudiList(){
        return [
            "Administrasi Publik",
            "Agribisnis",
            "Agronomi",
            "Agroteknologi",
            "Akuntansi",
            "Arsitektur",
            "Bahasa Inggris",
            "Bahasa Mandarin",
            "Bahasa Mandarin dan Kebudayaan Tiongkok",
            "Bimbingan dan Konseling",
            "Biologi",
            "Biosain",
            "Bisnis Digital",
            "Budi Daya Ternak",
            "Demografi dan Pencatatan Sipil",
            "Desain Interior",
            "Desain Komunikasi Visual",
            "Ekonomi Pembangunan",
            "Ekonomi dan Studi Pembangunan",
            "Farmasi",
            "Fisika",
            "Hubungan Internasional",
            "Ilmu Administrasi Negara",
            "Ilmu Ekonomi",
            "Ilmu Fisika",
            "Ilmu Gizi",
            "Ilmu Hukum",
            "Ilmu Kedokteran",
            "Ilmu Keolahragaan",
            "Ilmu Kesehatan Masyarakat",
            "Ilmu Komunikasi",
            "Ilmu Lingkungan",
            "Ilmu Linguistik",
            "Ilmu Pendidikan",
            "Ilmu Pertanian",
            "Ilmu Sejarah",
            "Ilmu Tanah",
            "Ilmu Teknik Mesin",
            "Ilmu Teknik Sipil",
            "Ilmu Teknologi Pangan",
            "Informatika",
            "Kajian Budaya",
            "Kebidanan",
            "Kebidanan Terapan",
            "Kedokteran",
            "Kenotariatan",
            "Keperawatan Anestesiologi",
            "Keselamatan dan Kesehatan Kerja",
            "Keuangan dan Perbankan",
            "Kimia",
            "Komunikasi Terapan",
            "Kriya Seni",
            "Linguistik",
            "Manajemen",
            "Manajemen Administrasi",
            "Manajemen Bisnis",
            "Manajemen Pemasaran",
            "Manajemen Perdagangan",
            "Matematika",
            "Pendidikan Administrasi Perkantoran",
            "Pendidikan Akuntansi",
            "Pendidikan Bahasa dan Sastra Indonesia",
            "Pendidikan Bahasa Indonesia",
            "Pendidikan Bahasa Inggris",
            "Pendidikan Bahasa Jawa",
            "Pendidikan Bahasa dan Sastra Daerah",
            "Pendidikan Biologi",
            "Pendidikan Ekonomi",
            "Pendidikan Fisika",
            "Pendidikan Geografi",
            "Pendidikan Guru Pendidikan Anak Usia Dini",
            "Pendidikan Guru Sekolah Dasar",
            "Pendidikan Guru Sekolah Dasar (Kampus Kabupaten Kebumen)",
            "Pendidikan Guru Vokasi",
            "Pendidikan Ilmu Pengetahuan Alam",
            "Pendidikan Jasmani, Kesehatan dan Rekreasi",
            "Pendidikan Kepelatihan Olahraga",
            "Pendidikan Kimia",
            "Pendidikan Luar Biasa",
            "Pendidikan Matematika",
            "Pendidikan Pancasila dan Kewarganegaraan",
            "Pendidikan Pancasila dan Kewarganegaraan",
            "Pendidikan Profesi Bidan",
            "Pendidikan Profesi Guru",
            "Pendidikan Profesi Guru SD",
            "Pendidikan Sains",
            "Pendidikan Sejarah",
            "Pendidikan Seni",
            "Pendidikan Seni Rupa",
            "Pendidikan Sosiologi Antropologi",
            "Pendidikan Teknik Bangunan",
            "Pendidikan Teknik Informatika & Komputer",
            "Pendidikan Teknik Mesin",
            "Pengelolaan Hutan",
            "Penyuluhan Pembangunan",
            "Penyuluhan Pembangunan/Pemberdayaan Masyarakat",
            "Penyuluhan dan Komunikasi Pertanian",
            "Perencanaan Wilayah dan Kota",
            "Perpajakan",
            "Perpustakaan",
            "Peternakan",
            "Profesi Apoteker",
            "Profesi Dokter",
            "Program Profesi Insinyur",
            "Psikologi",
            "Sains Data",
            "Sastra Arab",
            "Sastra Daerah",
            "Sastra Indonesia",
            "Sastra Inggris",
            "Seni Rupa",
            "Seni Rupa Murni",
            "Sosiologi",
            "Statistika",
            "Teknik Elektro",
            "Teknik Industri",
            "Teknik Informatika",
            "Teknik Kimia",
            "Teknik Mesin",
            "Teknik Sipil",
            "Teknologi Hasil Pertanian",
            "Teknologi Pendidikan",
            "Usaha Perjalanan Wisata"
        ];
    }
}

if(!function_exists('getUniversitasList')) {
    function getUniversitasList() {
        return [
            "Institut Seni Indonesia Surakarta",
            "Universitas Sebelas Maret",
            "Universitas Terbuka Surakarta",
            "UIN Raden Mas Said Surakarta",
            "Poltekkes Kemenkes Surakarta",
            "Akademi Komunitas Industri Tekstil dan Produk Tekstil Surakarta",
            "Akademi Akuntansi dan Perpajakan Bentara Indonesia",
            "Akademi Bahasa Asing Harapan Bangsa",
            "Akademi Bahasa Asing R.A. Kartini",
            "Akademi Bahasa Asing St. Pignatelli",
            "Akademi Desain",
            "Akademi Farmasi Nasional",
            "Akademi Keperawatan Patria Husada Surakarta",
            "Akademi Kebidanan Kusuma Husada",
            "Akademi Keperawatan Kusuma Husada",
            "Akademi Kebidanan Mamba'Ul Ulum",
            "Akademi Keperawatan Mamba'Ul Ulum",
            "Akademi Keperawatan Panti Kosala",
            "Akademi Keperawatan PPNI",
            "Akademi Pariwisata Mandala Bhakti",
            "Akademi Pariwisata Widya Nusantara",
            "Akademi Pelayaran Nasional Surakarta",
            "Akademi Perekam Medik & Info Kes Citra Medika",
            "Akademi Sekretari dan Manajemen Indonesia",
            "Akademi Seni dan Desain Indonesia",
            "Akademi Seni Mangkunegaran",
            "Akademi Teknik Adiyasa",
            "Akademi Teknik Fajar Indonesia",
            "Akademi Teknik Mesin Industri",
            "Akademi Teknik Warga",
            "Akademi Teknologi Adi Unggul Bhirawa (AUB) Surakarta",
            "Akademi Manajemen Informatika dan Komputer Cipta Darma",
            "Akademi Manajemen Informatika dan Komputer Harapan Bangsa",
            "Politeknik ATMI",
            "Politeknik Indonusa",
            "Politeknik Insan Husada Surakarta",
            "Politeknik Pratama Mulia",
            "Politeknik Surakarta",
            "Politeknik Santo Paulus Surakarta",
            "Sekolah Tinggi Teologia 'Intheos' Surakarta",
            "Sekolah Tinggi Teologia El-Shadday Surakarta",
            "Sekolah Tinggi Teologia Gamaliel Surakarta",
            "Sekolah Tinggi Bahasa Asing IEC",
            "Sekolah Tinggi Ilmu Ekonomi Adi Unggul Bhirawa",
            "Sekolah Tinggi Ilmu Ekonomi Atma Bhakti",
            "Sekolah Tinggi Ilmu Ekonomi St. Pignatelli",
            "Sekolah Tinggi Ilmu Ekonomi Surakarta",
            "Sekolah Tinggi Ilmu Ekonomi Swasta Mandiri",
            "Sekolah Tinggi Ilmu Ekonomi Wijaya Mulya",
            "Sekolah Tinggi Ilmu Kesehatan PKU Muhammdiyah Surakarta",
            "Sekolah Tinggi Pariwisata Sahid",
            "Sekolah Tinggi Manajemen Informatika dan Komputer Adi Unggul Bhirawa (STMIK AUB) Surakarta",
            "Sekolah Tinggi Manajemen Informatika dan Komputer Duta Bangsa",
            "Sekolah Tinggi Manajemen Informatika dan Komputer Sinar Nusantara",
            "Universitas Duta Bangsa Surakarta",
            "Universitas Islam Batik",
            "Universitas Kristen Surakarta",
            "Universitas Nahdlatul Ulama",
            "Universitas Sahid Surakarta",
            "Universitas Setia Budi",
            "Universitas Slamet Riyadi",
            "Universitas Surakarta",
            "Universitas Tunas Pembangunan",
            "Universitas Bina Sarana Informatika Kampus Surakarta (UBSI)",
            "Universitas Aisyiyah Surakarta"
        ];
    }
}



