<h1 align="center">Praktikum 8: PHP dan Database MySQL</h1>

<table align="center">
  <tr>
    <th colspan="2">DATA MAHASISWA</th>
  </tr>
  <tr>
    <td>Nama</td>
    <td>Alif Nur Fathlii Amarta</td>
  </tr>
  <tr>
    <td>NIM</td>
    <td>312210326</td>
  </tr>
  <tr>
    <td>Kelas</td>
    <td>TI.22.A3</td>
  </tr>
</table>

## Menjalankan MySQL Server
Jalankan MySQL Server dari menu XAMPP Control.

![Screenshot 2023-11-23 205409](https://github.com/alifamarta/Lab8Web/assets/115516820/e6df66e7-19c0-4045-8404-d09076597ac8)

## Mengakses MySQL dari client
Pastikan MySQL dan Apache sudah dijalankan. Setelah itu buka <a href="http://localhost/phpmyadmin/">```http://localhost/phpmyadmin/```</a>

## Database
### Membuat Database
    CREATE DATABASE latihan1;

### Membuat Tabel
    CREATE TABLE data_barang (
    id_barang int(10) auto_increment Primary Key,
    kategori varchar(30),
    nama varchar(30),
    gambar varchar(100),
    harga_beli decimal(10,0),
    harga_jual decimal(10,0),
    stok int(4)
    );

Tampilan PHPMyAdmin

![Screenshot 2023-11-24 231210](https://github.com/alifamarta/Lab8Web/assets/115516820/fb84bc82-42b5-4bc5-b0c2-ab90f4c11939)

### Menambahkan Data 
    INSERT INTO data_barang (kategori, nama, gambar, harga_beli, harga_jual, stok) VALUES 
    ('Elektronik', 'HP Samsung Android', 'hp_samsung.jpg', 2000000, 2400000, 5),
    ('Elektronik', 'HP Xiaomi Android', 'hp_xiaomi.jpg', 1000000, 1400000, 5),
    ('Elektronik', 'HP OPPO Android', 'hp_oppo.jpg', 1800000, 2300000, 5);
    
Tampilan Data Barang

![Screenshot 2023-11-23 211631](https://github.com/alifamarta/Lab8Web/assets/115516820/37dde9ff-f478-4b2a-8171-d8bf0ab386ac)

## Membuat program CRUD
Buat folder lab8_php_database pada directory ```c:\xampp\htdocs```

![Screenshot 2023-11-23 211651](https://github.com/alifamarta/Lab8Web/assets/115516820/6a228fdf-73ee-4069-af44-4c546c074a9b)

akses directory tersebut pada web server dengan URL: <a href="http://localhost/lab8_php_database/">```http://localhost/lab8_php_database/```</a>

![Web capture_23-11-2023_211723_localhost](https://github.com/alifamarta/Lab8Web/assets/115516820/5bb1bc49-2612-467a-a8db-be3c5cf968f3)


### Membuat file koneksi databse
buat file dengan nama koneksi.php

        <?php
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $db = 'latihan1';
        
        $conn = mysqli_connect($host, $user, $pass, $db);
        
        if ($conn = false) {
            echo 'Koneksi ke server gagal';
            die();
        } else echo "Koneksi berhasil";
        
        ?>

![Screenshot 2023-11-24 232441](https://github.com/alifamarta/Lab8Web/assets/115516820/d0fd633e-c2e6-479d-b4b0-f88d51e5c06a)

### Membuat file index untuk menampilkan data (Read)
Buat file bernama index.php

    <?php 
    include("koneksi.php");
    
    $sql = 'SELECT * FROM data_barang';
    $conn = mysqli_connect($host, $user, $pass, $db);
    $result = mysqli_query($conn, $sql);
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css" type="text/css">
        <title>Data Barang</title>
    </head>
    <body>
        <div class="container">
            <h1>Data Barang</h1>
            <div class="main">
                <a href="tambah.php">Tambah Barang</a>
                <table>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga Jual</th>
                        <th>Harga Beli</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                    <?php if ($result): ?>
                        <?php while ($row = mysqli_fetch_array($result)): ?>
                            <tr>
                                <td><img src="gambar/<?=$row['gambar'];?>" alt="<?= $row['nama'];?>"></td>
                                <td><?= $row['nama']; ?></td>
                                <td><?= $row['kategori']; ?></td>
                                <td><?= $row['harga_beli']; ?></td>
                                <td><?= $row['harga_jual']; ?></td>
                                <td><?= $row['stok']; ?></td>
                                <td>
                                    <a href="ubah.php?id=<?= $row['id_barang']; ?>">Ubah</a>
                                    <a href="hapus.php?id=<?= $row['id_barang']; ?>">Hapus</a> 
                                </td>
                            </tr>
                    <?php endwhile; else: ?>
                        <tr>
                            <td colspan="7">Belum ada data</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </body>
    </html>

![Web capture_24-11-2023_232915_localhost](https://github.com/alifamarta/Lab8Web/assets/115516820/2ae72369-31a6-48df-a4fd-535be088c690)

### Menambah Data (Create)
Buat file dengan nama tambah.php

    <?php
    error_reporting(E_ALL);
    include_once 'koneksi.php';
    
    if (isset($_POST['submit'])) {
        $nama = $_POST['nama'];
        $kategori = $_POST['kategori'];
        $harga_jual = $_POST['harga_jual'];
        $harga_beli = $_POST['harga_beli'];
        $stok = $_POST['stok'];
        $file_gambar = $_FILES['file_gambar'];
        $gambar = null;
        if ($file_gambar['error'] == 0) {
            $filename = str_replace(' ', '_', $file_gambar['name']);
            $destination = dirname(__FILE__) . '/gambar/' . $filename;
            if (move_uploaded_file($file_gambar['tmp_name'], $destination)) {
                $gambar = 'gambar/' . $filename;
            }
        }
        $sql = 'INSERT INTO data_barang (nama, kategori,harga_jual, harga_beli,
        stok, gambar) ';
        $sql .= "VALUE ('{$nama}', '{$kategori}','{$harga_jual}',
        '{$harga_beli}', '{$stok}', '{$gambar}')";
        $conn = mysqli_connect($host, $user, $pass, $db);
        $result = mysqli_query($conn, $sql);
        header('location: index.php');
    }
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title>Tambah Barang</title>
    </head>
    
    <body>
        <div class="container">
            <h1>Tambah Barang</h1>
            <div class="main">
                <form action="tambah.php" method="post" enctype="multipart/form-data">
                    <div class="input">
                        <label>Nama Barang</label>
                        <input type="text" name="nama" />
                    </div>
                    <div class="input">
                        <label>Kategori</label>
                        <select name="kategori">
                            <option value="Komputer">Komputer</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Handphone">Handphone</option>
                        </select>
                    </div>
                    <div class="input">
                        <label>Harga Jual</label>
                        <input type="text" name="harga_jual" />
                    </div>
                    <div class="input">
                        <label>Harga Beli</label>
                        <input type="text" name="harga_beli" />
                    </div>
                    <div class="input">
                        <label>Stok</label>
                        <input type="text" name="stok" />
                    </div>
                    <div class="input">
                        <label>File Gambar</label>
                        <input type="file" name="file_gambar" />
                    </div>
                    <div class="submit">
                        <input type="submit" name="submit" value="Simpan">
                    </div>
                </form>
            </div>
        </div>
    </body>
    </html>

![Screenshot 2023-11-24 222708](https://github.com/alifamarta/Lab8Web/assets/115516820/1895c506-a365-479f-bba7-9bef73dea5b0)

### Mngubah Data (Update)
Buat file dengan nama ubah.php

    <?php
    error_reporting(E_ALL);
    include_once 'koneksi.php';
    
    if (isset($_POST['submit']))
    {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $kategori = $_POST['kategori'];
        $harga_jual = $_POST['harga_jual'];
        $harga_beli = $_POST['harga_beli'];
        $stok = $_POST['stok'];
        $file_gambar = $_FILES['file_gambar'];
        $gambar = null;
    
        if ($file_gambar['error'] == 0)
        {
            $filename = str_replace(' ', '_', $file_gambar['name']);
            $destination = dirname(__FILE__) . '/gambar/' . $filename;
            if (move_uploaded_file($file_gambar['tmp_name'], $destination))
            {
                $gambar = 'gambar/' . $filename;
            }
        }
    
        $sql = 'UPDATE data_barang SET ';
        $sql .= "nama = '{$nama}', kategori = '{$kategori}', ";
        $sql .= "harga_jual = '{$harga_jual}', harga_beli = '{$harga_beli}', stok = '{$stok}' ";
        if (!empty($gambar))
            $sql .= ", gambar = '{$gambar}' ";
        $sql .= "WHERE id_barang = '{$id}'";
        $conn = mysqli_connect($host, $user, $pass, $db);
        $result = mysqli_query($conn, $sql);
    
        header('location: index.php');
        }
    
        $id = $_GET['id'];
        $conn = mysqli_connect($host, $user, $pass, $db);
        $sql = "SELECT * FROM data_barang WHERE id_barang = '{$id}'";
        $result = mysqli_query($conn, $sql);
        if (!$result) die('Error: Data tidak tersedia');
        $data = mysqli_fetch_array($result);
    
        function is_select($var, $val) {
            if ($var == $val) return 'selected="selected"';
            return false;
    }
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link href="style.css" rel="stylesheet" type="text/css" />
        <title>Ubah Barang</title>
    </head>
    <body>
    <div class="container">
        <h1>Ubah Barang</h1>
        <div class="main">
            <form method="post" action="ubah.php" enctype="multipart/form-data">
                <div class="input">
                    <label>Nama Barang</label>
                    <input type="text" name="nama" value="<?php echo $data['nama'];?>" />
                </div>
                <div class="input">
                    <label>Kategori</label>
                    <select name="kategori">
                        <option <?php echo is_select ('Komputer', $data['kategori']);?> value="Komputer">Komputer</option>
                        <option <?php echo is_select ('Komputer', $data['kategori']);?> value="Elektronik">Elektronik</option>
                        <option <?php echo is_select ('Komputer', $data['kategori']);?> value="Hand Phone">Hand Phone</option>
                    </select>
                </div>
                <div class="input">
                    <label>Harga Jual</label>
                    <input type="text" name="harga_jual" value="<?php echo $data['harga_jual'];?>" />
                </div>
                <div class="input">
                    <label>Harga Beli</label>
                    <input type="text" name="harga_beli" value="<?php echo $data['harga_beli'];?>" />
                </div>
                <div class="input">
                    <label>Stok</label>
                    <input type="text" name="stok" value="<?php echo $data['stok'];?>" />
                </div>
                <div class="input">
                    <label>File Gambar</label>
                    <input type="file" name="file_gambar" />
                </div>
                <div class="submit">
                    <input type="hidden" name="id" value="<?php echo $data['id_barang'];?>" />
                        <input type="submit" name="submit" value="Simpan" />
                </div>
            </form>
        </div>
    </div>
    </body>
    </html>

![Screenshot 2023-11-24 222722](https://github.com/alifamarta/Lab8Web/assets/115516820/41716cc2-bb3b-4550-9836-1454500220f2)

### Menghapus Data (Delete)
Buat file baru dengan nama hapus.php

    <?php
    include_once 'koneksi.php';
    $id = $_GET['id'];
    $sql = "DELETE FROM data_barang WHERE id_barang = '{$id}'";
    $conn = mysqli_connect($host, $user, $pass, $db);
    $result = mysqli_query($conn, $sql);
    header("location: index.php");
    ?>
