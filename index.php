<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "akademik";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //untuk mengecek koneksi antra codingan dengan database mysql
  die("database tidak terhubung");
}

// untuk menampilkan variabel pada Create / Edit Data
$nim      = "";
$nama     = "";
$alamat   = "";
$fakultas = "";
$error    = "";
$sukses   = "";
if (isset($_GET['op'])) {
  $op = $_GET['op'];
} else {
  $op = "";
}

//konfigurasi untuk melakukan hapus pada table 
if($op == 'delete'){
  $id         = $_GET['id'];
  $sql1       = "delete from mahasiswa where id = '$id'";
  $q1         = mysqli_query($koneksi,$sql1);
  if($q1){
      $sukses = "Berhasil hapus data";
  }else{
      $error  = "Gagal melakukan delete data";
  }
}



// kanfigurasi untuk mengedit pada table
if ($op == 'edit') {
  $id       = $_GET['id'];
  $sql1     = "select * from mahasiswa where id = '$id'";
  $q1       = mysqli_query($koneksi, $sql1);
  $r1       = mysqli_fetch_array($q1);
  $nim      = $r1['nim'];
  $nama     = $r1['nama'];
  $alamat   = $r1['alamat'];
  $fakultas = $r1['fakultas'];

  if ($nim == '') {
    $error = "data tidak di temukan";
  }
}


if (isset($_POST['simpan'])) { //untuk memasukan data ke dalam form
  $nim      = $_POST['nim'];
  $nama     = $_POST['nama'];
  $alamat   = $_POST['alamat'];
  $fakultas = $_POST['fakultas'];

  if ($nim && $nama && $alamat && $fakultas) { //fungsi nya untuk memastikan variabel diatas sudah ada isi nya
    if ($op == 'edit') { //konfigurasi untuk update
      $sql1  = "update mahasiswa set nim = '$nim',nama='$nama',alamat = '$alamat', fakultas = '$fakultas' where id = '$id' ";
      $q1 = mysqli_query($koneksi, $sql1);
      if ($q1) {
        $sukses = "Data berhasil diupdate";
      } else {
        $error = "Data gagal diupdate";
      }
    } else { //untuk melakukan insert
      $sql1 = "insert into mahasiswa(nim,nama,alamat,fakultas) values ('$nim','$nama','$alamat','$fakultas')"; //untuk memasukan data dengan menggunakan query
      $q1 =  mysqli_query($koneksi, $sql1);
      if ($q1) { //membuat suatu kondisi 
        $sukses = "Berhasil memasukan data baru";
      } else {
        $error  = "Gagal memasukan data";
      }
    }
  } else {
    $error = "silahkan masukan semua data";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <title>Data Mahasiswa</title>
</head>

<body>
  <!-- untuk memasukan data -->
  <div class="mx-auto">
    <div class="card">
      <div class="card-header">
        Create / Edit Data
      </div>
      <div class="card-body">
        <!-- untuk menampilkan pesan sukeses dan gagal -->
        <?php
        if ($error) {
        ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $error ?>
          </div>
        <?php
            header("refresh:4;url=index.php"); //fungsi nya, jika kita habis delete table maka tulisan form nya akan hilang "4"itu detik
        }
        ?>

        <?php
        if ($sukses) {
        ?>
          <div class="alert alert-success" role="alert">
            <?php echo $sukses ?>
          </div>
        <?php
            header("refresh:4;url=index.php");
        }
        ?>
        <!-- akhir pesan sukses dan gagal -->

        <form action="" method="POST">
          <div class="mb-3 row">
            <label for="nim" class="col-sm-2 col-form-label">NIM</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="nim" id="nim" value="<?php echo $nim ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $nama ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="alamat" id="alamat" value="<?php echo $alamat ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="fakultas" class="col-sm-2 col-form-label">fakultas</label>
            <div class="col-sm-10">
              <select class="form-control" name="fakultas" id="fakultas">
                <option value="#">- pilih fakultas</option>
                <option value="saintek" <?php if ($fakultas == "saintek") echo "selected" ?>>saintek</option>
                <option value="skom" <?php if ($fakultas == "skom") echo "selected" ?>>skom</option>
              </select>
            </div>
          </div>
          <div class="col-12">
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
          </div>
        </form>
      </div>
    </div>

    <!-- untuk mengeluarkan data -->
    <div class="card">
      <div class="card-header text-white bg-secondary">
        Data Mahasiswa
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">NIM</th>
              <th scope="col">Nama</th>
              <th scope="col">Alamat</th>
              <th scope="col">Fakultas</th>
              <th scope="col">Aksi</th>
            </tr>
          <tbody>
            <?php
            $sql2 = "select * from mahasiswa order by id desc"; //fungsinya sql2 menagambil database mahasiswa "desc" guna-nya supaa datanya menurun kebawah(biar tidak kesamping).
            $q2   = mysqli_query($koneksi, $sql2);
            $urut = 1;
            while ($r2 = mysqli_fetch_array($q2)) {
              $id       = $r2['id'];
              $nim      = $r2['nim'];
              $nama     = $r2['nama'];
              $alamat   = $r2['alamat'];
              $fakultas = $r2['fakultas'];

            ?>
              <tr>
                <th scope="row"><?php echo $urut++ ?></th>
                <td scope="row"><?php echo $nim ?></td>
                <td scope="row"><?php echo $nama ?></td>
                <td scope="row"><?php echo $alamat ?></td>
                <td scope="row"><?php echo $fakultas ?></td>
                <td scope="row">
                  <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                  <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('kamu yakin delete ini ?')"><button type="button" class="btn btn-danger" >Delete</button></a>
                </td>
              </tr>
            <?php
            }
            ?>
          </tbody>
          </thead>
        </table>
      </div>
    </div>

  </div>
</body>

</html>