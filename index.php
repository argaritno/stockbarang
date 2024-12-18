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
    <title>Stock Barang</title>
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
                    <h1 class="mt-4">Stock Barang</h1>



                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#myModal">
                                Tambah Barang
                            </button>
                            <a href="export.php " class="btn btn-info">Export Data</a>
                        </div>
                        <div class="card-body">

                            <?php
                            $ambildatastock = mysqli_query($conn, "select * from stock where stock < 1");

                            while ($fetch = mysqli_fetch_array($ambildatastock)) {
                                $barang = $fetch['namabarang'];


                                ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <strong>Perhatian!</strong> Stock <?= $barang; ?> Telah Habis!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="datatablesSimple" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Gambar</th>
                                            <th>Nama Barang</th>
                                            <th>Deskripsi</th>
                                            <th>Stock</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $ambilsemuadatastock = mysqli_query($conn, "select * from stock ");
                                        $i = 1;
                                        while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                                            $namabarang = $data['namabarang'];
                                            $deskripsi = $data['deskripsi'];
                                            $stock = $data['stock'];
                                            $idb = $data['idbarang'];


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
                                                <td><?= $i++; ?></td>
                                                <td><?= $img; ?></td>
                                                <td><?= $namabarang; ?></td>
                                                <td><?= $deskripsi; ?></td>
                                                <td><?= $stock; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                                        data-bs-target="#edit<?= $idb; ?>">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#delete<?= $idb; ?>">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>


                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit<?= $idb; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Barang</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="close"></button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <center>
                                                                    <img src="images/<?= $gambar; ?>" class="zoomable">
                                                                </center>
                                                                <br>
                                                                <input type="file" name="file" class="form-control">
                                                                <br>
                                                                <input type="text" name="namabarang"
                                                                    value="<?= $namabarang; ?>" class="form-control"
                                                                    required>
                                                                <br>
                                                                <input type="text" name="deskripsi"
                                                                    value="<?= $deskripsi; ?>" class="form-control"
                                                                    required>
                                                                <br>
                                                                <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="updatebarang">update</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete<?= $idb; ?>">
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
                                                                <br>
                                                                <br>
                                                                <button type="submit" class="btn btn-danger"
                                                                    name="hapusbarang">Hapus</button>
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
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>

            <!-- Modal body -->
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="file" class="form-control">
                    <br>
                    <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control" required>
                    <br>
                    <input type="text" name="deskripsi" placeholder="Deskripsi Barang" class="form-control" required>
                    <br>
                    <input type="number" name="stock" class="form-control" placeholder="Stock" required>
                    <br>
                    <button type="submit" class="btn btn-primary" name="addnewbarang">Add Barang</button>
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
                window.location.href = "index.php";
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
                window.location.href = "index.php";
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
                window.location.href = "index.php";
            });
        </script>';
}
?>

</html>