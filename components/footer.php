<script>
let i = 1;
$('#add_criterian').on('click', function() {
    $('#add_criterian').parent().parent().before(`
            <tr>
                <th scope="row">${i}</th>
                <td><input type="text" class="form-control" id="kode[${i}]" placeholder="Kode"></td>
                <td><input type="text" class="form-control" id="kriteria[${i}]" placeholder="Nama Kriteria"></td>
                <td>
                    <select class="form-select" id="tipe[${i}]" aria-label="Tipe Kriteria">
                        <option value="" selected>Pilih Tipe</option>
                        <option value="cost">Cost</option>
                        <option value="benefit">Benefit</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" id="bobot[${i}]" placeholder="Bobot">
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
    let data = []
    for (let j = 1; j < i; j++) {
        data.push({
            kode: $(`#kode\\[${j}\\]`).val(),
            kriteria: $(`#kriteria\\[${j}\\]`).val(),
            tipe: $(`#tipe\\[${j}\\]`).val(),
            bobot: $(`#bobot\\[${j}\\]`).val()
        })

    }

    localStorage.setItem('criteria', JSON.stringify(data))
    alert('Data berhasil disimpan')
    window.location.reload()
})


let criteria = JSON.parse(localStorage.getItem('criteria'))
if (criteria) {
    let total_weighted_product = 0
    criteria.forEach(item => {
        total_weighted_product += Number(item.bobot)
    })
    let table = `
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Kode Kriteria</th>
                <th scope="col">Nama Kriteria</th>
                <th scope="col">Tipe</th>
                <th scope="col">Bobot</th>
                <th scope="col">Weighted Product</th>
            </tr>
        </thead>
        <tbody>
    `
    criteria.forEach((item, index) => {
        let weighted_product = item.tipe == 'benefit' ? item.bobot : item.bobot * -1

        item.weighted_product = weighted_product / total_weighted_product
        table += `
            <tr>
                <th scope="row">${index + 1}</th>
                <td>${item.kode}</td>
                <td>${item.kriteria}</td>
                <td>${item.tipe}</td>
                <td>${item.bobot}</td>
                <td>${item.weighted_product}</td>
            </tr>
        `
    })
    table += `
        </tbody>
    `
    $('#kriteria').html(table)

    let table2 = `
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Pelamar</th>
    `
    criteria.forEach((item, index) => {
        table2 += `
            <th scope="col">${item.kriteria}</th>
        `
    })
    table2 += `
            </tr>
        </thead>
        <tbody>
    `
    table2 += `
            <tr>
                <td colspan="${criteria.length + 2}" class="text-center">
                    <button class="btn btn-primary" id="add_criterian2">
                        Add Kriteria
                    </button>
                    <button class="btn btn-success" id="simpan_criteria2">
                        Simpan
                    </button>
                </td>
            </tr>
        </tbody>
    `
    $('#kriteria2').html(table2)
}

let k = 1;
$('#add_criterian2').on('click', function() {
    let kriteriaRow = ''
    criteria.forEach((item, index) => {
        kriteriaRow += `
            <td><input type="text" class="form-control" id="nilai[${k}][${index}]" placeholder="Nilai"></td>
        `
    })
    $('#add_criterian2').parent().parent().before(`
            <tr>
                <th scope="row">${k}</th>
                <td><input type="text" class="form-control" id="nama[${k}]" placeholder="Nama Pelamar"></td>
                ${kriteriaRow}
            </tr>
        `)
    k++
})

$('#simpan_criteria2').on('click', function() {
    let data = []
    for (let j = 1; j < k; j++) {
        let nilai = []
        criteria.forEach((item, index) => {
            nilai.push($(`#nilai\\[${j}\\]\\[${index}\\]`).val())
        })
        console.log(nilai)
        data.push({
            nama: $(`#nama\\[${j}\\]`).val(),
            nilai: nilai
        })
    }

    localStorage.setItem('criteria2', JSON.stringify(data))
    alert('Data berhasil disimpan')
    window.location.reload()
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
            let pangkat = criteria[index].tipe == 'benefit' ? criteria[index].weighted_product :
                criteria[
                    index].weighted_product * -1
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