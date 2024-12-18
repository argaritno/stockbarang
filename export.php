<?php
require 'function.php';
require 'cek.php';
?>
<html>
<head>
  <title>Stock Barang</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
  <style>
        .zoomable{
            width: 100px;
        }
        .zoomable:hover{
            transform: scale(2.5);
            transition: 0,3 ease;
        }
    </style>
</head>

<body>
<div class="container">
			<h2>Stock Barang</h2>
			<h4>(Inventory)</h4>
				<div class="data-tables datatable-dark">
					

                
                <table class="table table-bordered " id="mauexport" width="100%" cellspacing="0%">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Gambar</th>
                                        <th>Nama Barang</th>
                                        <th>Deskripsi</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $ambilsemuadatastock = mysqli_query($conn, "select * from stock ");
                                    $i = 1;
                                    while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                                        $idbarang = $data['idbarang'];
                                        $namabarang = $data['namabarang'];
                                        $deskripsi = $data['deskripsi'];
                                        $stock = $data['stock'];

                                          //cek ada gambar atau tidak
                                          $gambar = $data['image'];//ambil gambar
                                          if($gambar==null){
                                              //jika tidak ada gambar
                                              $img = 'No Photo';
                                          } else {
                                              // jika ada gambar
                                              $img = '<img src="images/'.$gambar.'" class="zoomable">';
                                          }
                                      

                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $img; ?></td>
                                            <td><?php echo $namabarang; ?></td>
                                            <td><?php echo $deskripsi; ?></td>
                                            <td><?php echo $stock; ?></td>
                                     
                                        </tr>


                                      

                                        <?php
                                    };


                                    ?>


                                </tbody>
                            </table>
					
				</div>
</div>
	
<script>
$(document).ready(function() {
    $('#mauexport').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            if (column === 1) {
                                var img = $(node).find('img').attr('src');
                                return img ? '<img src="' + img + '" style="width:100px;height:auto;">' : 'Tidak Ada Foto';
                            }
                            return data;
                        }
                    }
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            if (column === 1) {
                                var img = $(node).find('img').attr('src');
                                return img ? '<img src="' + img + '" style="width:100px;height:auto;">' : 'Tidak Ada Foto';
                            }
                            return data;
                        }
                    }
                }
            }
        ]
    });
});
</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

	

</body>

</html>
