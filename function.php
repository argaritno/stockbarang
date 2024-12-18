<?php
session_start();

//Membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "stockbarang");


//Menambah barang baru
if (isset($_POST['addnewbarang'])) {
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $allowed_extension = array('png', 'jpg', 'jpeg');
    $nama = $_FILES['file']['name']; //ngambil nama gambar
    $dot = explode('.', $nama);
    $ekstensi = strtolower(end($dot)); //ngambil ekstensinya 
    $ukuran = $_FILES['file']['size']; //ngambil sizenya
    $file_tmp = $_FILES['file']['tmp_name']; // ngambil lokasi filenya

    // penamaan file -> enkripsi
    $image = null; // Inisialisasi variabel image
    if (!empty($nama)) {
        $image = md5(uniqid($nama, true) . time()) . '.' . $ekstensi; //menggabungkan nama file yg di enkripsidng ekstensinya
    }

    // proses gambar
    if (empty($nama) || in_array($ekstensi, $allowed_extension) === true) {
        // validasi ukuran filenya jika ada file yang diupload
        if (empty($nama) || $ukuran < 15000000) {
            if (!empty($nama)) {
                move_uploaded_file($file_tmp, 'images/' . $image);
            } else {
                $image = null; // Jika tidak ada file, set image ke null
            }

            // Lakukan query untuk menambahkan data ke tabel
            $addtotable = mysqli_query($conn, "insert into stock(namabarang, deskripsi, stock, image) values('$namabarang','$deskripsi','$stock','$image')");
            if ($addtotable) {
                header('location:index.php');
            } else {
                echo 'Gagal';
                header('location:index.php');
            }
        } else {
            // kalau filenya lebih dari 15mb
            echo '
            <script>
                alert("Ukuran terlalu besar");
                window.location.href="index.php";
            </script>
            ';
        }
    } else {
        // kalau filenya tidak png,jpg,jpeg
        echo '
        <script>
            alert("File harus png,jpg,jpeg");
            window.location.href="index.php";
        </script>
        ';
    }



    if ($addtotable) {
        header('location: index.php?process=success');
        exit();
    } else {

    }

}
;


//Menambah barang masuk
if (isset($_POST['barangmasuk'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang= '$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "insert into masuk (idbarang, keterangan, qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang= '$barangnya'");
    if ($addtomasuk && $updatestockmasuk) {
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }

    if ($addtomasuk) {
        header('Location: masuk.php?process=success');
        exit();
    } else {
        echo "<script>alert('Gagal menambah barang masuk.');</script>";
    }

}
;


//Menambah barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang= '$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];

    if ($stocksekarang >= $qty) {
        // Kalau barangnya cukup
        $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;

        $addtokeluar = mysqli_query($conn, "insert into keluar (idbarang, penerima, qty) values('$barangnya','$penerima','$qty')");
        $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang= '$barangnya'");
        if ($addtokeluark && $updatestockmasuk) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        // Kalau barangnya gak cukup
        echo '
        <script>
            alert("stock saat ini tidak mencukupi");
            window.location.href="keluar.php";
        </script>
        ';
    }
    if ($addtokeluar) {
        header('Location: keluar.php?process=success');
        exit();
    } else {
        echo "<script>alert('Gagal menambah barang.');</script>";
    }

}
;


// Update info barang
if (isset($_POST['updatebarang'])) {
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    // Ambil informasi gambar lama dari database
    $result = mysqli_query($conn, "SELECT image FROM stock WHERE idbarang='$idb'");
    $row = mysqli_fetch_assoc($result);
    $old_image = $row['image'];

    // Soal gambar
    $allowed_extension = array('png', 'jpg', 'jpeg');
    $nama = $_FILES['file']['name']; // Mengambil nama gambar
    $dot = explode('.', $nama);
    $ekstensi = strtolower(end($dot)); // Mengambil ekstensi
    $ukuran = $_FILES['file']['size']; // Mengambil ukuran
    $file_tmp = $_FILES['file']['tmp_name']; // Mengambil lokasi file

    // Penamaan file -> enkripsi
    $image = md5(uniqid($nama, true) . time()) . '.' . $ekstensi; // Menggabungkan nama file yang dienkripsi dengan ekstensi

    if ($ukuran == 0) {
        // Jika tidak ingin upload, hanya update nama dan deskripsi
        $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', deskripsi='$deskripsi' WHERE idbarang='$idb'");
        if ($update) {
            header('Location: index.php?process=update_success');
            exit();
        } else {
            echo 'Gagal';
            header('Location: index.php');
            exit();
        }
    } else {
        // Jika ingin upload gambar baru
        move_uploaded_file($file_tmp, 'images/' . $image);

        // Hapus gambar lama jika ada
        if (!empty($old_image) && file_exists('images/' . $old_image)) {
            unlink('images/' . $old_image);
        }

        // Update database dengan gambar baru
        $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', deskripsi='$deskripsi', image='$image' WHERE idbarang='$idb'");
        if ($update) {
            header('Location: index.php?process=update_success');
            exit();
        } else {
            echo 'Gagal';
            header('Location: index.php');
            exit();
        }
    }

    if ($update) {
        header('Location: index.php?process=update_success');
        exit();
    } else {
        echo "<script>alert('Gagal memperbarui barang.');</script>";
    }
}
;


// Menghapus barang dari stock 
if (isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb']; //idbarang

    $gambar = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $get = mysqli_fetch_array($gambar);
    $img = 'images/' . $get['image'];
    unlink($img);

    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if ($hapus) {
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }

    if ($hapus) {
        header('Location: index.php?process=delete_success');
        exit();
    } else {
        echo "<script>alert('Gagal menghapus barang.');</script>";
    }


}
;




//Mengubah data barang masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrng = $stocknya['stock'];

    $qtyskrng = mysqli_query($conn, "select * from masuk where idmasuk= '$idm'");
    $qtynya = mysqli_fetch_array($qtyskrng);
    $qtyskrng = $qtynya['qty'];

    if ($qty > $qtyskrng) {
        $selisih = $qty - $qtyskrng;
        $kurangin = $stockskrng + $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
        if ($kurangistocknya && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
        if ($updatenya) {
            header('location: masuk.php?process=update_success');
            exit();
        } else {
            echo "<script>alert('Gagal mengubah barang.');</script>";
        }
    } else {
        $selisih = $qtyskrng - $qty;
        $kurangin = $stockskrng - $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
        if ($kurangistocknya && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
        if ($updatenya) {
            header('location: masuk.php?process=update_success');
            exit();
        } else {
            echo "<script>alert('Gagal mengubah barang.');</script>";
        }
    }
}
;



//Menghapus barang masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock - $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from masuk where idmasuk='$idm'");

    if ($update && $hapusdata) {
        header('location:masuk.php');
    } else {
        header('location:masuk.php');
    }

    if ($hapusdata) {
        header('Location: masuk.php?process=delete_success');
        exit();
    } else {
        echo "<script>alert('Gagal menghapus barang.');</script>";
    }
}
;




//Mengubah data barang keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrng = $stocknya['stock'];

    $qtyskrng = mysqli_query($conn, "select * from keluar where idkeluar= '$idk'");
    $qtynya = mysqli_fetch_array($qtyskrng);
    $qtyskrng = $qtynya['qty'];

    if ($qty > $qtyskrng) {
        $selisih = $qty - $qtyskrng;
        $kurangin = $stockskrng - $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty',penerima='$penerima' where idkeluar='$idk'");
        if ($kurangistocknya && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
        if ($updatenya) {
            header('Location: keluar.php?process=update_success');
            exit();
        } else {
            echo "<script>alert('Gagal mengubah barang keluar.');</script>";
        }

    } else {
        $selisih = $qtyskrng - $qty;
        $kurangin = $stockskrng + $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
        if ($kurangistocknya && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
        if ($updatenya) {
            header('Location: keluar.php?process=update_success');
            exit();
        } else {
            echo "<script>alert('Gagal mengubah barang keluar.');</script>";
        }

    }
}
;



//Menghapus barang keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock + $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from keluar where idkeluar='$idk'");

    if ($update && $hapusdata) {
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }
    if ($hapusdata) {
        header('Location: keluar.php?process=delete_success');
        exit();
    } else {
        echo "<script>alert('Gagal menghapus barang keluar.');</script>";
    }

}
;

?>