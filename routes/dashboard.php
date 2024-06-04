<?php include('../components/header.php') ?>
<div class="d-flex">
    <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-body-tertiary" style="width: 380px;">
        <div
            class="d-flex align-items-center justify-content-between flex-shrink-0 p-3 link-body-emphasis text-decoration-none border-bottom">
            <span class="fs-5 fw-semibold">Daftar Perhitungan</span>
            <button class="btn btn-danger" id="logout">
                Logout
            </button>
        </div>
        <div class="list-group list-group-flush border-bottom scrollarea">
            <a href="#" id="add" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-center">
                    <strong class="mb-1 text-center">Tambah</strong>
                </div>
            </a>
        </div>
    </div>

    <div class="container w-100 mt-5">
        <div class="table-responsive box-table">
            <h3>Table Kriteria</h3>
            <table id="kriteria" class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kode Kriteria</th>
                        <th scope="col">Nama Kriteria</th>
                        <th scope="col">Tipe</th>
                        <th scope="col">Bobot</th>
                        <th scope="col">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center">
                            <button class="btn btn-primary" id="add_criterian">
                                Add Kriteria
                            </button>
                            <button class="btn btn-success" id="simpan_criteria">
                                Simpan
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive box-table">
            <h3>Table Kriteria & Alternatif</h3>
            <table id="kriteria2" class="table table-striped mt-5">
            </table>
        </div>

        <div class="table-responsive box-table">
            <h3>Table SI</h3>
            <table id="table_si" class="table table-striped mt-5">
            </table>
        </div>

        <div class="table-responsive box-table">
            <h3>Table Ranking</h3>
            <table id="table_ranking" class="table table-striped mt-5">
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    if (!localStorage.getItem('login')) {
        window.location.href = '../index.php'
    }
})
$('#logout').on('click', function() {
    localStorage.removeItem('login')
    window.location.href = '../index.php'
})

let perhitungan = localStorage.getItem('perhitungan') ? JSON.parse(localStorage.getItem('perhitungan')) : []
let length = perhitungan.length
$('#add').on('click', function() {
    $(this).before(`
        <a href="#" data-index="${length + 1}" class="list-group-item list-group-item-action py-3 lh-sm">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <div>
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <strong class="mb-1">Perhitungan</strong>
                        <small class="text-body-secondary">Mon</small>
                    </div>
                    <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
                </div>
                <button class="btn btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
            </div>
        </a>
    `)
    length++
    perhitungan.push({
        nama: 'Perhitungan',
        tanggal: 'Mon',
        deskripsi: 'Some placeholder content in a paragraph below the heading and date.'
    })
    localStorage.setItem('perhitungan', JSON.stringify(perhitungan))
})

if (perhitungan.length > 0) {
    perhitungan.forEach((item, index) => {
        $('#add').before(`
            <a href="#" data-index="${index}" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <div>
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <strong class="mb-1">${item.nama}</strong>
                        </div>
                        <div class="col-10 mb-1 small">${item.deskripsi}</div>
                    </div>
                    <button class="btn btn-danger text-white z-3" id="delete-perhitungan">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </div>
            </a>
        `)
    })
}

$('#delete-perhitungan').on('click', function() {
    let index = $(this).parent().parent().data('index')
    console.log(index)
    perhitungan.splice(index, 1)
    localStorage.setItem('perhitungan', JSON.stringify(perhitungan))
    window.location.reload()
})
</script>
<?php include('../components/footer.php') ?>