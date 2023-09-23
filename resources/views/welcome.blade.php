<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form id="formTambah">
        @csrf
        <input type="hidden" name="total_harga" id="total_harga">
        <input type="hidden" name="id" id="id">
        <div class="form-group">
            <label for="nama_barang">Nama Barang:</label>
            <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
        </div>
        <div class="form-group">
            <label for="qty">Qty:</label>
            <input type="number" class="form-control" id="qty" name="qty" required>
        </div>
        <div class="form-group">
            <label for="harga_satuan">Harga Satuan:</label>
            <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" required>
        </div>

        <button type="submit" id="submit-btn" class="btn btn-primary">Submit</button>
    </form>

    <table id="data-table" class="table">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data dari session akan ditampilkan di sini -->
        </tbody>
    </table>

    <form action="" id="customerForm">
        @csrf
        <input type="text" name="nama_customer" id="nama_customer">
        <button type="submit" id="save-to-database-btn" class="btn btn-success">Save to Database</button>
    </form>
  



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            function getDataFromServer() {
                $.ajax({
                    url: '{{ url('get/session') }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        renderDataInTable(response.data);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(xhr);
                        alert("Terjadi kesalahan: " + textStatus);
                    }
                });
            }

            function renderDataInTable(data) {
                let tbody = $('#data-table tbody');
                let totalHarga = 0;

                tbody.empty();

                $.each(data, function(index, item) {
                    let row = '<tr>' +
                        '<td>' + item.nama_barang + '</td>' +
                        '<td>' + item.qty + '</td>' +
                        '<td>' + item.harga_satuan + '</td>' +
                        '<td>' + item.total_harga + '</td>' +
                        '</tr>';
                    tbody.append(row);

                    totalHarga += parseInt(item.total_harga);
                });

                let totalRow = '<tr>' +
                    '<td colspan="3">Total Keseluruhan</td>' +
                    '<td>' + totalHarga + '</td>' +
                    '</tr>';
                tbody.append(totalRow);
            }

            getDataFromServer();

            $('#formTambah').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: "post",
                    url: "{{ url('/session') }}",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.message === 'check your validation') {
                            console.log(response)
                            let error = response.errors;
                            let errorMessage = '';

                            alert('ada data yang kosong')
                        } else {
                            alert('success tambah menu')
                            window.location.reload();
                        }
                    },
                    error: function(error) {
                        console.log('Error', error);
                    }
                });
            })


            $('#customerForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: "post",
                    url: "{{ url('/session/save') }}",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.message === 'check your validation') {
                            console.log(response)
                            let error = response.errors;
                            let errorMessage = '';

                            alert('ada data yang kosong')
                        } else {
                            alert('success tambah menu')
                            window.location.reload();
                        }
                    },
                    error: function(error) {
                        console.log('Error', error);
                    }
                });
            })
        });
    </script>

</body>

</html>
