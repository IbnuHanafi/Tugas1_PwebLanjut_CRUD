<?php
require_once 'config.php';
// Koneksi database

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk tabel users
$stmt_users = $conn->prepare("SELECT * FROM users");
if (!$stmt_users) {
    die("Prepare gagal: " . $conn->error);
}
$stmt_users->execute();
$result_users = $stmt_users->get_result();

// Query untuk tabel products
$stmt_products = $conn->prepare("SELECT * FROM products");
if (!$stmt_products) {
    die("Prepare gagal: " . $conn->error);
}
$stmt_products->execute();
$result_products = $stmt_products->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style untuk Sidebar */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h4 {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: white;
            padding: 10px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #444;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .sidebar a.active {
            background-color: #007bff;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        /* Style untuk Tombol */
        .btn-success,
        .btn-warning,
        .btn-danger {
            border-radius: 30px;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .btn-warning {
            background-color: #f1c40f;
            /* Kuning */
            border-color: #f1c40f;
        }

        .btn-warning:hover {
            background-color: #f39c12;
            /* Kuning lebih gelap */
            border-color: #f39c12;
        }

        .btn-warning:focus {
            outline: none;
            box-shadow: 0 0 10px #fff;
        }

        /* Tambahkan animasi border */
        .btn-success:hover,
        .btn-warning:hover,
        .btn-danger:hover {
            transition: all 0.3s ease;
            box-shadow: 0 0 10px 2px rgba(255, 255, 255, 0.6);
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            color: white;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Admin Dashboard</h4>
        <a href="#" onclick="loadPage('user')" class="active">Daftar User</a>
        <a href="#" onclick="loadPage('produk')">Daftar Produk</a>

        <!-- NIM Footer -->
        <div class="sidebar-footer">
            NIM: A12.2023.06994
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Daftar User -->
        <div id="user" class="page" style="display: <?= isset($_GET['page']) && $_GET['page'] == 'produk' ? 'none' : 'block'; ?>;">
            <div class="container mt-5">
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="mb-0">Daftar User</h2>
                            </div>
                            <div class="card-body">
                                <a href="create_user.php" class="btn btn-success mb-3">
                                    <i class="bi bi-plus-circle"></i> Tambah User Baru
                                </a>

                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result_users->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['id']) ?></td>
                                                <td><?= htmlspecialchars($row['name']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td>
                                                    <a href="edit_user.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="delete_user.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>

                                        <?php if ($result_users->num_rows === 0): ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada data user</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Produk -->
        <div id="produk" class="page" style="display: <?= isset($_GET['page']) && $_GET['page'] == 'produk' ? 'block' : 'none'; ?>;">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="mb-0">Daftar Produk</h2>
                            </div>
                            <div class="card-body">
                                <a href="create_produk.php" class="btn btn-success mb-3">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk Baru
                                </a>
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result_products->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['id']) ?></td>
                                                <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                                <td>Rp <?= number_format($row['harga'], 2, ',', '.') ?></td>
                                                <td><?= htmlspecialchars($row['stok']) ?></td>
                                                <td>
                                                    <a href="edit_produk.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="delete_produk.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>

                                        <?php if ($result_products->num_rows === 0): ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada data produk</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadPage(page) {
            // Menyembunyikan semua halaman
            let pages = document.querySelectorAll('.page');
            pages.forEach(function(page) {
                page.style.display = 'none';
            });

            // Menampilkan halaman yang dipilih
            let selectedPage = document.getElementById(page);
            if (selectedPage) {
                selectedPage.style.display = 'block';
            }

            // Menambah kelas aktif pada link sidebar
            let links = document.querySelectorAll('.sidebar a');
            links.forEach(function(link) {
                link.classList.remove('active');
            });
            document.querySelector('.sidebar a[href="#' + page + '"]').classList.add('active');
        }

        // Menampilkan halaman pertama yang default
        loadPage('user');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$stmt_users->close();
$stmt_products->close();
$conn->close();
?>