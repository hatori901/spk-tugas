<?php include('../components/header.php') ?>

<?php

if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
}

$master_kriteria = select('kriteria_master');


if(isset($_GET['kriteria'])){
    $data = select('kriteria', "kriteria_master_id = ".$_GET['kriteria']);
    $kritera_master_id = $_GET['kriteria'];
}

?>
<div class="flex">
    <div class="fixed flex flex-col flex-shrink-0 bg-[#252525] text-white min-h-screen h-full" style="width: 380px;">
        <img src="../assets/logo.png" alt="logo" class="h-16 mx-auto my-5">
        <div class="bg-[#353434] px-3 py-5 text-center font-bold text-xl mx-5 my-3">
            Daftar Perhitungan
        </div>
        <div class="flex flex-col gap-y-3">
            <?php foreach ($master_kriteria as $key => $value) : ?>
            <div
                class="list-group-item cursor-pointer flex items-center justify-between mx-5 rounded-lg font-semibold <?= isset($_GET['kriteria']) && $_GET['kriteria'] == $value['id'] ? 'bg-white text-gray-600' : 'bg-[#605D5D]' ?>">
                <div class="w-full h-full px-3 py-4" id="kriteria-<?= $value['id'] ?>">
                    <?= $value['name'] ?>
                </div>
                <button class="bg-red-500 rounded-md p-2 rounded-md z-10 mr-2" id="delete-<?=$value['id']?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-4 w-4 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
            </div>
            <?php endforeach; ?>
            <a href="#" id="add" class="bg-blue-500 px-3 py-4 rounded-lg mx-5">
                <div class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah
                </div>
            </a>
        </div>
    </div>

    <div class="overflow-hidden ml-[380px] w-full">
        <div class="flex justify-center items-center gap-x-2 text-2xl font-bold text-center mt-5">
            <? if($master_kriteria) : ?>
            <h2><?= isset($_GET['kriteria']) ? $master_kriteria[0]['name'] : 'Perhitungan Baru' ?></h2>
            <? endif; ?>
            <button id="edit-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
            </button>
            <script>
            $('#edit-title').click(function() {
                $(this).prev().attr('contenteditable', 'true').focus()

                $(this).prev().keypress(function(e) {
                    if (e.which == 13) {
                        e.preventDefault()
                        let text = $(this).text()
                        let id = <?= isset($_GET['kriteria']) ? $_GET['kriteria'] : 0 ?>;
                        $.ajax({
                            url: '../routes/api.php',
                            type: 'POST',
                            data: {
                                id: id,
                                name: text,
                                action: 'edit_hitung'
                            },
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message,
                                        willClose: () => {
                                            setTimeout(() => {
                                                window.location.reload()
                                            }, 2000);
                                        }
                                    })
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.message
                                    })
                                }
                            }
                        })
                        $(this).attr('contenteditable', 'false')
                    }
                })
            })
            </script>
        </div>
        <div class="mx-10">
            <div class="overflow-auto shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg box-table">
                <h3 class="text-2xl font-bold">Table Kriteria</h3>
                <table id="kriteria" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Kode Kriteria</th>
                            <th scope="col" class="text-left">Nama Kriteria</th>
                            <th scope="col">Tipe</th>
                            <th scope="col">Bobot</th>
                            <th scope="col" class="text-left">Weighted Product</th>
                            <th scope="col">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($data)): ?>
                        <?php $total_weighted_product = getTotalWeightedProduct($data) ?>

                        <?php foreach ($data as $key => $value) : ?>
                        <?php $weighted_product = $value['tipe'] == 'benefit' ? $value['bobot'] : $value['bobot'] * -1 ?>
                        <?php $value['weighted_product'] = $weighted_product / $total_weighted_product ?>
                        <tr class="even:bg-gray-50" id="kr-<?= $value['id'] ?>">
                            <td class="text-center"><?= $value['kode'] ?></td>
                            <td><?= $value['nama'] ?></td>
                            <td class="text-center"><?= $value['tipe'] ?></td>
                            <td class="text-center"><?= $value['bobot'] ?></td>
                            <td><?= $value['weighted_product'] ?></td>
                            <td class="text-center">
                                <button class="bg-blue-500 text-white p-2 rounded-md" id="edit-kriteria"
                                    data-id="<?= $value['id'] ?>" data-kode="<?= $value['kode'] ?>"
                                    data-nama="<?= $value['nama'] ?>" data-tipe="<?= $value['tipe'] ?>"
                                    data-bobot="<?= $value['bobot'] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button class="bg-red-500 text-white p-2 rounded-md" data-id="<?= $value['id'] ?>"
                                    id="dlt-kriteria">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <tr>
                            <td colspan="7" class="text-right">
                                <div class="flex gap-x-2">
                                    <button class="flex gap-x-3 bg-[#002F77] text-white px-4 py-2 rounded-md"
                                        id="add_criterian">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Tambah Kriteria
                                    </button>
                                    <button class="flex gap-x-3 bg-[#1B8655] text-white px-4 py-2 rounded-md"
                                        id="simpan_criteria">
                                        Simpan
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if(!empty($data)): ?>
            <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg box-table">
                <h3 class="text-2xl font-bold">Table Kriteria & Alternatif</h3>
                <table id="kriteria2" class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="text-left">Nama Pelamar</th>
                            <?php foreach ($data as $key => $value) : ?>
                            <th scope="col"><?= $value['nama'] ?> (<?= $value['kode']?>)</th>
                            <?php endforeach; ?>
                            <th>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $kriteria_id = getKriteriaId($kritera_master_id) ?>
                        <?php $alternatives = select('alternative', "kriteria_id = ".$kriteria_id['id']) ?>
                        <?php foreach ($alternatives as $key => $value) : ?>
                        <tr class="even:bg-gray-50" id="<?= $value['id'] ?>">
                            <td><?= $value['nama'] ?></td>
                            <?php foreach ($data as $key => $kriteria) : ?>
                            <?php $nilai = select('alternative_nilai', "alternative_id = ".$value['id']." AND kriteria_id = ".$kriteria['id']) ?>
                            <td class="text-center">
                                <?php if($nilai): ?>
                                <?= $nilai[0]['nilai'] ?>
                                <?php else: ?>
                                <input type="text" name="alternative[<?= $value['id'] ?>][<?= $kriteria['id'] ?>]"
                                    class="border py-2 px-4 rounded-md"
                                    id="nilai-<?= $kriteria['id'] ?>-<?= $value['id'] ?>" placeholder="Nilai">
                                <?php endif; ?>
                            </td>
                            <?php endforeach; ?>
                            <td class="text-center">
                                <button class="bg-red-500 text-white p-2 rounded-md" data-id="<?= $value['id'] ?>"
                                    id="dlt-nilai">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="flex gap-x-2">
                                    <button class="flex gap-x-3 bg-[#002F77] text-white px-4 py-2 rounded-md"
                                        id="add_criterian2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Tambah Pelamar
                                    </button>
                                    <button class="flex gap-x-3 bg-[#1B8655] text-white px-4 py-2 rounded-md"
                                        id="simpan_criteria2">
                                        Simpan
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <?php if(!empty($data)): ?>
            <div class="table-responsive box-table">
                <h3 class="text-2xl font-bold">Table SI</h3>
                <table id="table_si" class="table table-striped mt-5">
                    <thead>
                        <tr>
                            <th scope="col">Nama Pelamar</th>
                            <?php foreach ($data as $key => $value) : ?>
                            <th scope="col"><?= $value['nama'] ?></th>
                            <?php endforeach; ?>
                            <th>SI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $alternatives = select('alternative', "kriteria_id = ".$kriteria_id['id']) ?>
                        <?php
                    $total_si = 0;
                    $data_si = [];
                    $total_weighted_product = getTotalWeightedProduct($data);
                    foreach ($data as $key => $value) {
                        $data[$key]['weighted_product'] = $value['bobot'] / $total_weighted_product;
                    }
                    ?>
                        <?php foreach ($alternatives as $key => $value) : ?>
                        <tr>
                            <td><?= $value['nama'] ?></td>
                            <?php foreach ($data as $key => $kriteria) : ?>
                            <?php $nilai = select('alternative_nilai', "alternative_id = ".$value['id']." AND kriteria_id = ".$kriteria['id']) ?>
                            <td class="text-center">
                                <?= $nilai[0]['nilai'] ** ($kriteria['tipe'] == 'benefit' ? $kriteria['weighted_product'] : $kriteria['weighted_product'] * -1) ?>
                            </td>
                            <?php endforeach; ?>
                            <td class="text-center">
                                <?php
                            $si = 0;
                            foreach ($data as $key => $kriteria) {
                                $nilai = select('alternative_nilai', "alternative_id = ".$value['id']." AND kriteria_id = ".$kriteria['id']);
                                if($si == 0){
                                    $si = $nilai[0]['nilai'] ** ($kriteria['tipe'] == 'benefit' ? $kriteria['weighted_product'] : $kriteria['weighted_product'] * -1);
                                }else{
                                    $si *= $nilai[0]['nilai'] ** ($kriteria['tipe'] == 'benefit' ? $kriteria['weighted_product'] : $kriteria['weighted_product'] * -1);
                                }
                            }
                            $data_si[] = $si;
                            echo $si;
                            ?>
                            </td>
                        </tr>
                        <?php $total_si += $si; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <?php if(!empty($data)): ?>
            <div class="table-responsive box-table">
                <h3 class="text-2xl font-bold">Table Ranking</h3>
                <table id="table_ranking" class="table table-striped mt-5">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th scope="col" class="text-left">Nama Pelamar</th>
                            <th scope="col">VI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $alternatives = select('alternative', "kriteria_id = ".$kriteria_id['id']) ?>
                        <?php
                    $index = 0;
                    array_column($alternatives, 'nama');
                    array_multisort($data_si, SORT_DESC, $alternatives);
                    ?>
                        <?php foreach ($alternatives as $key => $value) : ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td><?= $value['nama'] ?></td>
                            <td class="text-center"><?= $data_si[$index] / $total_si;?></td>
                        </tr>
                        <?php $index++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
$('div[id^="kriteria-"]').click(function() {
    let id = $(this).attr('id').split('-')[1]
    location = `dashboard.php?kriteria=${id}`
})

$('#add').click(function() {
    let id;
    $.ajax({
        url: '../routes/api.php',
        type: 'POST',
        data: {
            name: "Perhitungan Baru",
            action: 'add_hitung'
        },
        success: function(response) {
            if (response.status) {
                $('#add').before(`
                    <div id="kriteria-${response.id}"
                        class="list-group-item cursor-pointer flex items-center justify-between mx-5 rounded-lg font-semibold bg-[#605D5D]">
                            <div class="w-full h-full px-3 py-4">
                                Perhitungan Baru
                            </div>
                        <button class="bg-red-500 rounded-md p-2 rounded-md mr-2" id="delete-${response.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-4 w-4 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>
                `)
                window.location = `dashboard.php?kriteria=${response.id}`
            }
        }
    })

});


$('button[id^="delete-"]').click(function() {
    let id = $(this).attr('id').split('-')[1]
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
                url: '../routes/api.php',
                type: 'POST',
                data: {
                    id: id,
                    action: 'delete_hitung'
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            title: "Deleted!",
                            text: response.message,
                            icon: "success"
                        });
                        $(`#kriteria-${id}`).parent().remove()
                    }
                }
            })
        }
    });
})
</script>
<?php include('../components/footer.php') ?>