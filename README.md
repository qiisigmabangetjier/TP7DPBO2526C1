PEES5 PICKMOK

Aplikasi web berbasis PHP Native dengan konsep OOP untuk membantu memilih dan mengelola daftar game PlayStation 5 (Grand Theft Auto Jomok, EA Suki Liyar, God of War Ragnamok, dan lainnya). Seluruh query memakai Prepared Statement (PDO).

tema :
daftar game (Grand Theft Auto Jomok, EA Suki Liyar, dll, namanya sengaja diplesetin), daftar genre, sama halaman Pilihanku buat nyimpen game yang dipilih orang beserta alasannya, misalnya mau mabar bola sama temen

cara jalanin :
Nyalain Apache sama MySQL di XAMPP, terus taruh folder project ini di htdocs. Buka phpMyAdmin, import file database/db_ps5_games.sql (databasenya kebuat otomatis). Setelah itu buka http://localhost/pees5-pickmok/ di browser.

struktur :
pees5-pickmok/
├── class/
│   ├── Genre.php
│   ├── Game.php
│   └── Pick.php
├── config/
│   ├── db.php
│   └── helpers.php
├── database/
│   └── db_ps5_games.sql
├── view/
│   ├── header.php
│   ├── footer.php
│   ├── games.php
│   ├── genres.php
│   └── picks.php
├── background.jpg
├── index.php
├── style.css
└── README.md
class isinya satu class buat tiap tabel, tugasnya ngurusin query ke tabel itu. config isinya koneksi database (db.php) sama beberapa fungsi kecil (helpers.php) kayak escape output, redirect, dan pesan notifikasi. view isinya tampilan HTML tiap halaman. index.php jadi pintu masuk semua halaman.

database :
Nama databasenya db_ps5_games, isinya 3 tabel.
genres: id, name, description.
games: id, title, genre_id, developer, release_year, rating, price. genre_id ini foreign key ke genres.
picks: id, game_id, player_name, reason, picked_at. game_id ini foreign key ke games.
Relasinya, satu genre bisa punya banyak game, dan satu game bisa dipilih berkali-kali di picks. Genre nggak bisa dihapus kalau masih dipakai game (ON DELETE RESTRICT), jadi bakal muncul pesan error. Kalau sebuah game dihapus, pilihan yang terkait sama game itu ikut kehapus (ON DELETE CASCADE).
File sql-nya udah lengkap sama struktur dan data contoh.

flow :
Pertama browser buka index.php, misalnya index.php?page=games. Di situ index.php nge-load config sama semua class, terus bikin koneksi PDO lewat class Database. Parameter page dicek dulu pakai whitelist (games, genres, picks), kalau nggak cocok balik ke games. Habis itu index.php nampilin header, file view sesuai page, lalu footer.

Di file view, kalau user klik tombol Tambah, Simpan, atau Hapus, form ngirim request POST. View bikin object dari class yang sesuai (misalnya new Game($db)) terus manggil method-nya: create, update, atau delete. Method itu yang jalanin query pakai prepare dan execute ke database.

Setelah aksi selesai, view nyimpen pesan lewat flash() terus redirect ke halaman yang sama. Ini biar kalau halaman di-refresh, form nggak kekirim dua kali. Di request berikutnya, view manggil getAll() buat ambil data dan nampilin di tabel. Tombol Edit pakai ?edit=id, nanti view manggil getById() buat ngisi form dengan data lama.

Singkatnya alurnya gini: browser ke index.php, lanjut ke view, ke class, ke database, balik lagi ke class, view, dan akhirnya ke browser.

link dokumentasi :
yt : https://youtu.be/L8Dp50JJBwo?si=gEUsqlkrXGSHSpop
