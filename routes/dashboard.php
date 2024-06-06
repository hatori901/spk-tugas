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
            <?php foreach ($master_kriteria as $key => $value) : ?>
            <a href="#" id="kriteria-<?= $value['id'] ?>" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1"><?= $value['name'] ?></strong>
                </div>
            </a>
            <?php endforeach; ?>
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
                        <th scope="col">Kode Kriteria</th>
                        <th scope="col">Nama Kriteria</th>
                        <th scope="col">Tipe</th>
                        <th scope="col">Bobot</th>
                        <th scope="col">Weighted Product</th>
                        <th scope="col">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($data)): ?>
                    <?php $total_weighted_product = getTotalWeightedProduct($data) ?>

                    <?php foreach ($data as $key => $value) : ?>
                    <?php $weighted_product = $value['tipe'] == 'benefit' ? $value['bobot'] : $value['bobot'] * -1 ?>
                    <?php $value['weighted_product'] = $weighted_product / $total_weighted_product ?>
                    <tr>
                        <td><?= $value['kode'] ?></td>
                        <td><?= $value['nama'] ?></td>
                        <td><?= $value['tipe'] ?></td>
                        <td><?= $value['bobot'] ?></td>
                        <td><?= $value['weighted_product'] ?></td>
                        <td>
                            <button class="btn btn-danger delete" data-id="<?= $value['id'] ?>">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <tr>
                        <td colspan="7" class="text-center">
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

        <?php if(!empty($data)): ?>
        <div class="table-responsive box-table">
            <h3>Table Kriteria & Alternatif</h3>
            <table id="kriteria2" class="table table-striped mt-5">
                <thead>
                    <tr>
                        <th scope="col">Nama Pelamar</th>
                        <?php foreach ($data as $key => $value) : ?>
                        <th scope="col"><?= $value['nama'] ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $kriteria_id = getKriteriaId($kritera_master_id) ?>
                    <?php $alternatives = select('alternative', "kriteria_id = ".$kriteria_id['id']) ?>
                    <?php foreach ($alternatives as $key => $value) : ?>
                    <tr>
                        <td><?= $value['nama'] ?></td>
                        <?php foreach ($data as $key => $kriteria) : ?>
                        <?php $nilai = select('alternative_nilai', "alternative_id = ".$value['id']." AND kriteria_id = ".$kriteria['id']) ?>
                        <td><?= $nilai[0]['nilai'] ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            <button class="btn btn-primary" id="add_criterian2">
                                Add Kriteria
                            </button>
                            <button class="btn btn-success" id="simpan_criteria2">
                                Simpan
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php if(!empty($data)): ?>
        <div class="table-responsive box-table">
            <h3>Table SI</h3>
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
                        <td><?= $nilai[0]['nilai'] ** ($kriteria['tipe'] == 'benefit' ? $kriteria['weighted_product'] : $kriteria['weighted_product'] * -1) ?>
                        </td>
                        <?php endforeach; ?>
                        <td>
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
            <h3>Table Ranking</h3>
            <table id="table_ranking" class="table table-striped mt-5">
                <thead>
                    <tr>
                        <th scope="col">Nama Pelamar</th>
                        <th scope="col">VI</th>
                        <th>Ranking</th>
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
                        <td><?= $value['nama'] ?></td>
                        <td><?= $data_si[$index] / $total_si;?></td>
                        <td><?= $index + 1 ?></td>
                    </tr>
                    <?php $index++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<script>
$('a[id^="kriteria-"]').click(function() {
    let id = $(this).attr('id').split('-')[1]
    location = `dashboard.php?kriteria=${id}`
})

$('#add').click(function() {
    $(this).before(`
        <div class="list-group-item list-group-item-action py-3 lh-sm">
            <form method="post" action="simpan_hitung.php">
                <div class="d-flex">
                    <input type="text" class="form-control" name="nama" placeholder="Nama">
                    <button type="submit" class="btn btn-primary" id="simpan-hitung">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    `)
});

$('#simpan-hitung').click(function() {
    let nama = $(this).parent().find('input').val()
    let data = `nama=${nama}`
    $.ajax({
        url: '/routes/simpan_hitung.php',
        type: 'POST',
        data: data,
        success: function(response) {
            if (response) {
                window.location.reload()
            }
        }
    })
})
</script>
<?php include('../components/footer.php') ?>