<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let i = 0;
$('#add_criterian').on('click', function() {
    $('#add_criterian').parent().parent().parent().before(`
            <tr>
                <td><input type="text" name="kriteria[${i}][kode]" class="border py-2 px-4 rounded-md" id="kode[${i}]" placeholder="Kode"></td>
                <td><input type="text" name="kriteria[${i}][nama]" class="border py-2 px-4 rounded-md" id="kriteria[${i}]" placeholder="Nama Kriteria"></td>
                <td>
                    <select class="border py-2 px-4 rounded-md" name="kriteria[${i}][tipe]"  id="tipe[${i}]" aria-label="Tipe Kriteria">
                        <option value="" selected>Pilih Tipe</option>
                        <option value="cost">Cost</option>
                        <option value="benefit">Benefit</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="kriteria[${i}][bobot]"  class="border py-2 px-4 rounded-md" id="bobot[${i}]" placeholder="Bobot">
                </td>
                <td>
                    <input type="text" class="border py-2 px-4 rounded-md" id="bobot[${i}]" placeholder="Weighted Product" readonly>
                </td>
                <td>
                    <button class="btn btn-danger" id="delete_criterian" data-index="${i}">
                        Hapus
                    </button>
                </td>
            </tr>
        `)
    i++
})



$('table').on('click', '#delete_criterian', function() {
    let index = $(this).data('index')
    $(`#kode\\[${index}\\]`).parent().parent().remove()
})

$('#simpan_criteria').on('click', function() {
    let data = $('input').serialize()
    let select = $('select').serialize()
    data += '&' + select;

    var pass = true
    let kriteria_data = []
    $('input[name^="kriteria"]').each(function() {
        let name = $(this).attr('name')
        let value = $(this).val()
        let split = name.split('[')
        let index = split[1].replace(']', '')
        let tipe = $(`#tipe\\[${index}\\]`).val()
        let key = split[2].replace(']', '')
        if (!kriteria_data[index]) {
            kriteria_data[index] = {}
        }
        kriteria_data[index][key] = value
        kriteria_data[index].tipe = tipe
    })



    let kriteria_master_id = '<?= isset($_GET['kriteria']) ? $_GET['kriteria'] : 0 ?>'
    data += `&kriteria_master_id=${kriteria_master_id}&action=simpan_kriteria`


    // check is any field empty
    kriteria_data.forEach((item, index) => {
        if (Object.values(item).includes('')) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Field tidak boleh kosong!',
            })
            pass = false
            return false
        }
    })

    // check is code exist
    let kode = []
    kriteria_data.forEach((item, index) => {
        if (kode.includes(String(item.kode).toLowerCase())) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terdapat kode yang sama!',
            })
            pass = false
            return false
        }
        kode.push(String(item.kode).toLowerCase())
    })

    // check is name exist
    let nama = []
    kriteria_data.forEach((item, index) => {
        if (nama.includes(String(item.nama).toLowerCase())) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terdapat nama yang sama!',
            })
            pass = false
            return false
        }
        nama.push(String(item.nama).toLowerCase())
    })
    if (pass) {
        $.ajax({
            url: '/routes/api.php',
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.status) {
                    window.location.reload()
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    })
                }
            }
        })
    }
})

let k = 1;

<?php if(isset($data)): ?>
let criteria = <?= json_encode($data) ?>;
<?php else: ?>
let criteria = []
<?php endif; ?> $('#add_criterian2').on('click', function() {
    let kriteriaRow = ''
    criteria.forEach((item, index) => {
        let key = Object.keys(item)
        kriteriaRow += `
            <td><input type="text" class="border py-2 px-4 rounded-md" name="alternative[${k}][${item.kode}]" placeholder="Nilai"></td>
        `
    })
    $('#add_criterian2').parent().parent().parent().before(`
            <tr>
                <td><input type="text" class="border py-2 px-4 rounded-md" name="alternative[${k}][nama]" id="nama[${k}]" placeholder="Nama Pelamar"></td>
                ${kriteriaRow}
            </tr>
        `)
    k++
})

$('#simpan_criteria2').on('click', function() {
    let data = $('input').serialize()
    data += `&kriteria_master_id=<?= isset($_GET['kriteria']) ? $_GET['kriteria'] : 0 ?>`
    $.ajax({
        url: '/routes/simpan_alternative.php',
        type: 'POST',
        data: data,
        success: function(response) {
            if (response) {
                window.location.reload()
            }
        }
    })
})

$('input[id^=nilai]').on('keyup', function() {
    let id = $(this).attr('id')
    let kriteria_id = id.split('-')[1]
    let alternative_id = id.split('-')[2]

    $.ajax({
        url: '/routes/api.php',
        type: 'POST',
        data: {
            action: 'simpan_nilai_kriteria',
            kriteria_master_id: <?= isset($_GET['kriteria']) ? $_GET['kriteria'] : 0 ?>,
            kriteria_id: kriteria_id,
            alternative_id: alternative_id,
            nilai: $(this).val()
        },
        success: function(response) {
            if (response.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil disimpan',
                    willClose: () => {
                        window.location.reload()
                    }
                })
            }
        }
    })
})


$('button[id^=dlt-kriteria]').on('click', function() {
    let id = $(this).data('id')
    Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/routes/api.php',
                type: 'POST',
                data: {
                    action: 'delete_kriteria',
                    id: id
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil dihapus',
                            willClose: () => window.location.reload()
                        })
                    }
                }
            })
        }
    });
})


$('#edit-kriteria').on('click', function() {
    let id = $(this).data('id')
    let kode = $(this).data('kode')
    let nama = $(this).data('nama')
    let tipe = $(this).data('tipe')
    let bobot = $(this).data('bobot')

    $('tr[id=kr-' + id + ']').html(`
        <td><input type="text" name="kode" class="border py-2 px-4 rounded-md" id="kode-${id}" placeholder="Kode" value="${kode}"></td>
        <td><input type="text" name="nama" class="border py-2 px-4 rounded-md" id="kriteria-${id}" placeholder="Nama Kriteria" value="${nama}"></td>
        <td>
            <select class="border py-2 px-4 rounded-md" name="tipe"  id="tipe-${id}" aria-label="Tipe Kriteria">
                <option value="" selected>Pilih Tipe</option>
                <option value="cost" ${ tipe == "cost" ? 'selected' : '' }>Cost</option>
                <option value="benefit" ${ tipe == "benefit" ? 'selected' : '' }>Benefit</option>
            </select>
        </td>
        <td>
            <input type="text" name="bobot"  class="border py-2 px-4 rounded-md" id="bobot-${id}" placeholder="Bobot" value="${bobot}">
        </td>
        <td>
            <input type="text" class="border py-2 px-4 rounded-md" id="wp[${id}]" placeholder="Weighted Product" readonly>
        </td>
        <td>
            <button class="bg-blue-500 p-2 rounded-md " id="simpan_edit_kriteria-${id}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>
        </td>
    `)
})

$('tr[id^=kr-]').on('click', 'button[id^=simpan_edit_kriteria-]', function() {
    let id = $(this).attr('id').split('-')[1]
    let kode = $(`input[id=kode-${id}]`).val()
    let nama = $(`input[id=kriteria-${id}`).val()
    let tipe = $(`select[id=tipe-${id}]`).val()
    let bobot = $(`input[id=bobot-${id}]`).val()
    console.log(id, kode, nama, tipe, bobot)
    $.ajax({
        url: '/routes/api.php',
        type: 'POST',
        data: {
            action: 'edit_kriteria',
            id: id,
            kode: kode,
            nama: nama,
            tipe: tipe,
            bobot: bobot
        },
        success: function(response) {
            if (response.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil diubah',
                    willClose: () => window.location.reload()
                })
            }
        }
    })
})


$('button[id^=dlt-nilai]').on('click', function() {
    let id = $(this).data('id')
    Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/routes/api.php',
                type: 'POST',
                data: {
                    action: 'delete_alternatif',
                    id: id
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil dihapus',
                            willClose: () => window.location.reload()
                        })
                    }
                }
            })
        }
    });
})
</script>
</body>

</html>