<?php
require 'function.php';
require 'cek.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Barang Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .zoomable {
            width: 100px;
        }

        .zoomable:hover {
            transform: scale(2.5);
            transition: 0, 3 ease;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">Argaritno Store</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>

    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Stock Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang Masuk
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang Keluar
                        </a>
                        <a class="nav-link" href="logout.php">
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Barang Masuk</h1>



                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#myModal">
                                Tambah Barang
                            </button>
                        </div>
                        <div class="card-body">
                        <table class="table table-bordered" id="datatablesSimple" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Gambar</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $ambilsemuadatastock = mysqli_query($conn, "select * from masuk m, stock s where s.idbarang = m.idbarang");
                                    while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                                        $idb = $data['idbarang'];
                                        $idm = $data['idmasuk'];
                                        $tanggal = $data['tanggal'];
                                        $namabarang = $data['namabarang'];
                                        $qty = $data['qty'];
                                        $keterangan = $data['keterangan'];

                                        //cek ada gambar atau tidak
                                        $gambar = $data['image'];//ambil gambar
                                        if ($gambar == null) {
                                            //jika tidak ada gambar
                                            $img = 'No Photo';
                                        } else {
                                            // jika ada gambar
                                            $img = '<img src="images/' . $gambar . '" class="zoomable">';
                                        }

                                        ?>
                                        <tr>
                                            <td><?= $tanggal; ?></td>
                                            <td><?= $img; ?></td>
                                            <td><?= $namabarang; ?></td>
                                            <td><?= $qty; ?></td>
                                            <td><?= $keterangan; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                                    data-bs-target="#edit<?= $idm; ?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#delete<?= $idm; ?>">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="edit<?= $idm; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="close"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <center>
                                                                <img src="images/<?= $gambar ?>" class="zoomable">
                                                            </center>
                                                            <br>
                                                            <input type="text" name="keterangan" value="<?= $keterangan; ?>"
                                                                class="form-control" required>
                                                            <br>
                                                            <input type="number" name="qty" value="<?= $qty; ?>"
                                                                class="form-control" required>
                                                            <br>
                                                            <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            <input type="hidden" name="idm" value="<?= $idm; ?>">
                                                            <button type="submit" class="btn btn-primary"
                                                                name="updatebarangmasuk">Update</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="delete<?= $idm; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Barang?</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="close"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            Apakah Anda Yakin Ingin Menghapus <?= $namabarang; ?>?
                                                            <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            <input type="hidden" name="kty" value="<?= $qty; ?>">
                                                            <input type="hidden" name="idm" value="<?= $idm; ?>">
                                                            <br>
                                                            <br>
                                                            <button type="submit" class="btn btn-danger"
                                                                name="hapusbarangmasuk">Hapus</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ;

                                    ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang Masuk</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>

            <!-- Modal body -->
            <form method="post">
                <div class="modal-body">

                    <select name="barangnya" class="form-control mb-3 ">
                        <?php
                        $ambilsemuadatanya = mysqli_query($conn, "select * from stock");
                        while ($fetcharray = mysqli_fetch_array($ambilsemuadatanya)) {
                            $namabarangnya = $fetcharray['namabarang'];
                            $idbarangnya = $fetcharray['idbarang'];
                            ?>
                            <option value="<?= $idbarangnya; ?>"><?= $namabarangnya; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="number" name="qty" class="form-control" placeholder="Quantity" required>
                    <br>
                    <input type="text" name="penerima" class="form-control " placeholder="Penerima" required>
                    <br>
                    <button type="submit" class="btn btn-primary" name="barangmasuk">Add Barang</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="dist/sweetalert2.all.min.js"></script>


<?php $process = isset($_GET['process']) ? ($_GET['process']) : false; ?>
<?php
if ($process == 'success') {
    echo '
        <script>
            Swal.fire({
                icon: "success",
                title: "Berhasil menambah data",
                showConfirmButton: true,
            }).then(function() {
                window.location.href = "masuk.php";
            });
        </script>';
}
?>


<?php $process = isset($_GET['process']) ? ($_GET['process']) : false; ?>

<?php
if ($process == 'update_success') {
    echo '
        <script>
            Swal.fire({
                icon: "success",
                title: "Berhasil mengubah data",
                showConfirmButton: true,
            }).then(function() {
                window.location.href = "masuk.php";
            });
        </script>';
}
?>
<?php $process = isset($_GET['process']) ? ($_GET['process']) : false; ?>
<?php
if ($process == 'delete_success') {
    echo '
        <script>
            Swal.fire({
                icon: "success",
                title: "Berhasil menghapus data",
                showConfirmButton: true,
            }).then(function() {
                window.location.href = "masuk.php";
            });
        </script>';
}
?>

</html>