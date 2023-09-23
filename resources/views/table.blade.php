<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <form>
            <div class="mb-3">
                <label for="search" class="form-label">Pencarian</label>
                <input type="text" class="form-control" id="search" placeholder="Cari berdasarkan field yg di inginkan">
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal 1</label>
                <input type="date" class="form-control" id="tanggal">
            </div>
            <div class="mb-3">
                <label for="tanggal2" class="form-label">Tanggal 2</label>
                <input type="date" class="form-control" id="tanggal2">
            </div>
        </form>

        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>2024-09-15</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>2023-09-15</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>2023-09-15</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>2023-09-15</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>2023-09-15</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>2024-09-15</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>Rizki</td>
                    <td>1</td>
                    <td>2024-09-15</td>
                    <td>1</td>
                </tr>

                <!-- Tambahkan data lainnya di sini -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            const $searchInput = $("#search");
            const $tanggalInput = $("#tanggal");
            const $tanggalInput2 = $("#tanggal2");
            const $rows = $("tbody tr");

            $searchInput.on("input", filterData);
            $tanggalInput.on("input", filterData);
            $tanggalInput2.on("input", filterData);

            function filterData() {
                const search = $searchInput.val().toLowerCase();
                const tanggal1 = $tanggalInput.val();
                const tanggal2 = $tanggalInput2.val();

                $rows.each(function() {
                    const $row = $(this);
                    let match = false; // Gunakan variabel untuk melacak apakah ada kecocokan di baris ini

                    // Iterasi melalui semua kolom dalam baris
                    $row.find("td").each(function() {
                        const cellValue = $(this).text().toLowerCase();
                        // Jika kata kunci pencarian cocok dengan nilai kolom, setel match ke true
                        if (cellValue.includes(search)) {
                            match = true;
                            return false; // Hentikan iterasi jika sudah ada kecocokan
                        }
                    });

                    // Setelah selesai iterasi semua kolom
                    if (
                        (match || search === "") &&
                        // Pastikan ada kecocokan atau kata kunci pencarian kosong
                        ((tanggal1 === "" && tanggal2 === "") ||
                            (tanggal1 <= $row.find("td:eq(5)").text() && tanggal2 >= $row.find("td:eq(5)")
                                .text()))
                    ) {
                        $row.show();
                    } else {
                        $row.hide();
                    }
                });
            }
        });
    </script>
</body>

</html>
