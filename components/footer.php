<script>
let i = 0;
$('#add_criterian').on('click', function() {
    $('#add_criterian').parent().parent().before(`
            <tr>
                <td><input type="text" name="kriteria[${i}][kode]" class="form-control" id="kode[${i}]" placeholder="Kode"></td>
                <td><input type="text" name="kriteria[${i}][nama]" class="form-control" id="kriteria[${i}]" placeholder="Nama Kriteria"></td>
                <td>
                    <select class="form-select" name="kriteria[${i}][tipe]"  id="tipe[${i}]" aria-label="Tipe Kriteria">
                        <option value="" selected>Pilih Tipe</option>
                        <option value="cost">Cost</option>
                        <option value="benefit">Benefit</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="kriteria[${i}][bobot]"  class="form-control" id="bobot[${i}]" placeholder="Bobot">
                </td>
                <td>
                    <input type="text" class="form-control" id="bobot[${i}]" placeholder="Weighted Product" readonly>
                </td>
                <td>
                    <button class="btn btn-danger" id="delete_criterian" data-index="${i}">
                        Delete
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
    let kriteria_master_id = '<?= isset($_GET['kriteria']) ? $_GET['kriteria'] : 0 ?>'
    data += `&kriteria_master_id=${kriteria_master_id}`
    $.ajax({
        url: '/routes/simpan_kriteria.php',
        type: 'POST',
        data: data,
        success: function(response) {
            if (response) {
                window.location.reload()
            }
        }
    })
})

let k = 1;

<?php if(isset($data)): ?>
let criteria = <?= json_encode($data) ?>;
<?php else: ?>
let criteria = []
<?php endif; ?>
$('#add_criterian2').on('click', function() {
    let kriteriaRow = ''
    criteria.forEach((item, index) => {
        let key = Object.keys(item)
        kriteriaRow += `
            <td><input type="text" class="form-control" name="alternative[${k}][${item.kode}]" placeholder="Nilai"></td>
        `
    })
    $('#add_criterian2').parent().parent().before(`
            <tr>
                <td><input type="text" class="form-control" name="alternative[${k}][nama]" id="nama[${k}]" placeholder="Nama Pelamar"></td>
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

let criteria2 = JSON.parse(localStorage.getItem('criteria2'))
if (criteria2) {
    let table = `
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Pelamar</th>
    `
    criteria.forEach((item, index) => {
        table += `
            <th scope="col">${item.kriteria}</th>
        `
    })
    table += `
            </tr>
        </thead>
        <tbody>
    `
    criteria2.forEach((item, index) => {
        table += `
            <tr>
                <th scope="row">${index + 1}</th>
                <td>${item.nama}</td>
        `
        item.nilai.forEach((nilai, index) => {
            table += `
                <td>${nilai}</td>
            `
        })
        table += `
            </tr>
        `
    })
    table += `
        </tbody>
    `
    $('#kriteria2').html(table)

    let table_si = `
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Pelamar</th>
    `
    criteria.forEach((item, index) => {
        table_si += `
            <th scope="col">${item.kriteria}</th>
        `
    })
    table_si += `
            <th scope="col">SI</th>
            </tr>
        </thead>
        <tbody>
    `

    let jumlah_si = 0
    criteria2.forEach((item, index) => {
        let row = index
        let total_si = 0
        table_si += `
            <tr>
                <th scope="row">${index + 1}</th>
                <td>${item.nama}</td>
        `
        item.nilai.forEach((nilai, index) => {
            let pangkat = criteria[index].weighted_product
            table_si += `
                    <td>${nilai**pangkat}</td>
                `
            if (total_si == 0) {
                total_si = nilai ** pangkat
            } else {
                total_si *= nilai ** pangkat
            }
            criteria2[row].si = total_si
        })
        jumlah_si += total_si
        table_si += `
                <td>${total_si}</td>
            </tr>
        `
    })
    console.log(jumlah_si)
    table_si += `
        </tbody>
    `

    $('#table_si').html(table_si)


    let table_ranking = `
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Pelamar</th>
                <th scope="col">VI</th>
                <th scope="col">Ranking</th>
            </tr>
        </thead>
        <tbody>
    `
    criteria2.forEach((item, index) => {
        let vi = item.si / jumlah_si
        criteria2[index].vi = vi
    })

    criteria2.sort((a, b) => b.vi - a.vi)
    criteria2.forEach((item, index) => {
        table_ranking += `
            <tr>
                <th scope="row">${index + 1}</th>
                <td>${item.nama}</td>
                <td>${item.vi}</td>
                <td>${index + 1}</td>
            </tr>
        `
    })
    table_ranking += `
        </tbody>
    `
    $('#table_ranking').html(table_ranking)
}
</script>
</body>

</html>