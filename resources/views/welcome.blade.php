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
            <input type="text" class="form-control" id="nama_barang" name="nama_barang">
        </div>
        <div class="form-group">
            <label for="qty">Qty:</label>
            <input type="number" class="form-control" id="qty" name="qty">
        </div>
        <div class="form-group">
            <label for="harga_satuan">Harga Satuan:</label>
            <input type="number" class="form-control" id="harga_satuan" name="harga_satuan">
        </div>

        <div class="modal-footer">
            <button type="submit" form="formTambah" class="btn btn-outline-primary">Submit Data</button>
        </div>
    </form>

    <table id="data-table" class="table">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data dari session akan ditampilkan di sini -->
        </tbody>
    </table>

    <form action="" id="customerForm">
        @csrf
        <input type="hidden" name="id" id="id">
        <input type="text" name="nama_customer" id="nama_customer">
        <input type="text" name="total_pembayaran" id="total_pembayaran">
        <input type="text" name="jumlah_bayar" id="jumlah_bayar" placeholder="jumlah bayar">
        <input type="text" name="kembalian" id="kembalian" disabled placeholder="kembalian">
        <input type="text" name="diskon" id="diskon" placeholder="diskon">
        <input type="hidden" name="sisa_pelunasan" id="sisa_pelunasan" placeholder="sisa pelunasan">
        <button type="submit" id="save-to-database-btn" class="btn btn-success">Save to Database</button>
    </form>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            let totalHarga;

            getDataFromServer();

            function getDataFromServer() {
                $.ajax({
                    url: '{{ url('get/session') }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        renderDataInTable(response.data);

                        totalHarga = calculateTotalHarga(response.data);
                        $('#total_pembayaran').val(totalHarga);

                        calculatePayment();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(xhr);
                        alert("Terjadi kesalahan: " + textStatus);
                    }
                });
            }

            function calculateTotalPembayaran() {
                let diskon = parseFloat($('#diskon').val());

                if (!isNaN(diskon)) {
                    let totalPembayaran = totalHarga - (totalHarga * (diskon / 100));
                    $('#total_pembayaran').val(totalPembayaran.toFixed(0));
                } else {
                    $('#total_pembayaran').val(totalHarga.toFixed(0));
                }

                console.log('totalHarga:', totalHarga);
                console.log('diskon:', diskon);

                calculatePayment();
            }

            $('#diskon').on('input', calculateTotalPembayaran);

            function calculatePayment() {
                let totalPembayaran = parseFloat($('#total_pembayaran').val());
                let jumlahBayar = parseFloat($('#jumlah_bayar').val());

                if (!isNaN(totalPembayaran) && !isNaN(jumlahBayar)) {
                    let kembalian = jumlahBayar - totalPembayaran;
                    $('#kembalian').val(kembalian.toFixed(2));
                } else {
                    $('#kembalian').val('');
                }
            }
            $('#jumlah_bayar').on('input', calculatePayment);

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
                        '<td>' +
                        "<button type='button' class='btn btn-primary edit-modal' ' " +
                        "data-id='" + item.id + "'>" +
                        "<button type='button' class='btn btn-danger delete-confirm' data-id='" +
                        item.id + "'>delete</button>" +
                        '</td>' +
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

            function calculateTotalHarga(data) {
                let totalHarga = 0;
                $.each(data, function(index, item) {
                    totalHarga += parseInt(item.total_harga);
                });

                localStorage.setItem('totalHarga', totalHarga);

                return totalHarga;
            }


            getDataFromServer();

            $(document).ready(function() {
                var isEditMode = false;

                function showForm(editMode = false, id = '') {
                    isEditMode = editMode;
                    if (isEditMode) {
                        $('.modal-footer button[type="submit"] ').text('Update');
                    } else {
                        $('.modal-footer button[type="submit"] ').text('Submit');
                    }
                    $('#id').val(id);
                }

                console.log('isEditMode:', isEditMode);


                $('#formTambah').submit(function(e) {
                    e.preventDefault();
                    let formData = new FormData(this);

                    if (isEditMode) {
                        let id = $('#id').val();
                        $.ajax({
                            type: "post",
                            url: "{{ url('/session/update') }}/" + id,
                            data: formData,
                            dataType: "JSON",
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                console.log(response)
                                if (response.message === 'Check your validation') {
                                    console.log(response)
                                    let error = response.errors;
                                    let errorMessage = '';

                                    alert('ada data yang kosong')
                                } else {
                                    alert('success update')
                                    window.location.reload();
                                }
                            },
                            error: function(error) {
                                console.log('Error', error);
                            }
                        });
                    } else {
                        $.ajax({
                            type: "post",
                            url: "{{ url('/session') }}",
                            data: formData,
                            dataType: "JSON",
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                console.log(response)
                                if (response.message === 'Check your validation') {
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
                    }
                });

                $(document).on('click', '.edit-modal', function() {
                    const id = $(this).data('id');

                    $.ajax({
                        url: '{{ url('get/session/') }}' + '/' + id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            $('#nama_barang').val(response.data.nama_barang);
                            $('#qty').val(response.data.qty);
                            $('#harga_satuan').val(response.data.harga_satuan);
                            $('#total_harga').val(response.data.total_harga);
                            $('#id').val(response.data.id);

                            showForm(true, response.data.id);
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log(xhr);
                            alert("Terjadi kesalahan: " + textStatus);
                        }
                    });
                });
            });



            $('#customerForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let totalHarga = $('#total_pembayaran').val();
                console.log(totalHarga)

                formData.append('total_harga', totalHarga);

                $.ajax({
                    type: "post",
                    url: "{{ url('/session/save') }}",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response)
                        if (response.message === 'check your validation') {
                            console.log(response)
                            let error = response.errors;
                            let errorMessage = '';

                            alert('ada data yang kosong')
                        } else {
                            localStorage.removeItem('totalHarga');

                            alert('success tambah menu')
                            window.location.reload();
                            $('#sisa_pelunasan').val(response.sisa_pelunasan);
                        }
                    },
                    error: function(error) {
                        console.log('Error', error);
                    }
                });
            });


            $(document).ready(function() {
                function deleteData(id) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('/session/delete') }}/" + id,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        dataType: "json",
                        success: function(response) {
                            alert(response.message);
                            window.location.reload();
                        },
                        error: function(error) {
                            console.log('Error', error);
                            alert('Terjadi kesalahan saat menghapus data');
                        }
                    });
                }

                $(document).on('click', '.delete-confirm', function() {
                    const id = $(this).data('id');
                    const confirmDelete = confirm('Apakah Anda yakin ingin menghapus data ini?');

                    if (confirmDelete) {
                        deleteData(id);
                    }
                });
            });

        });
    </script>

</body>

</html>
