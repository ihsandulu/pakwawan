<?php echo $this->include("template/header_v"); ?>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <?php if (!isset($_GET['user_id']) && !isset($_POST['new']) && !isset($_POST['edit'])) {
                            $coltitle = "col-md-10";
                        } else {
                            $coltitle = "col-md-8";
                        } ?>
                        <div class="<?= $coltitle; ?>">
                            <h4 class="card-title"></h4>
                            <!-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6> -->
                        </div>
                    </div>

                    <?php
                    if (isset($_GET["from"]) && $_GET["from"] != "") {
                        $from = $_GET["from"];
                    } else {
                        $from = date("Y-m-d");
                    }

                    if (isset($_GET["to"]) && $_GET["to"] != "") {
                        $to = $_GET["to"];
                    } else {
                        $to = date("Y-m-d");
                    }

                    ?>
                    <form class="form-inline">
                        <label for="from">Dari:</label>&nbsp;
                        <input type="date" id="from" name="from" class="form-control" value="<?= $from; ?>">&nbsp;
                        <label for="to">Ke:</label>&nbsp;
                        <input type="date" id="to" name="to" class="form-control" value="<?= $to; ?>">&nbsp;
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                    <?php if ($message != "") { ?>
                        <div class="alert alert-info alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong><?= $message; ?></strong>
                        </div>
                    <?php } ?>

                    <div class="table-responsive m-t-40">
                        <table id="example231" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                            <thead class="">
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Harga Jual</th>
                                    <th>Harga Beli</th>
                                    <th>Hasil</th>
                                    <th>60%</th>
                                    <th>40%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $product = $this->db->table("product")->get();
                                $arpbeli = array();
                                foreach ($product->getResult() as $row) {
                                    $arpbeli[$row->product_id] = $row->product_buy;
                                }
                                // dd($arpbeli);
                                $builder = $this->db
                                    ->table("transaction")
                                    ->select("
                                        transaction.transaction_date,
                                        transactiond.product_id,
                                        product.product_name,
                                        SUM(transactiond.transactiond_qty) AS jumlah,
                                        SUM(transactiond.transactiond_price) AS terjual,
                                        SUM(transactiond.transactiond_beli) AS terbeli
                                    ")
                                    ->join("transactiond", "transaction.transaction_id = transactiond.transaction_id", "left")
                                    ->join("product", "product.product_id = transactiond.product_id", "left")
                                    ->where("transaction.store_id", session()->get("store_id"))
                                    ->where("transaction.transaction_status", "0");

                                if (!empty($_GET["from"])) {
                                    $builder->where("transaction.transaction_date >=", $this->request->getGet("from"));
                                } else {
                                    $builder->where("transaction.transaction_date", date("Y-m-d"));
                                }

                                if (!empty($_GET["to"])) {
                                    $builder->where("transaction.transaction_date <=", $this->request->getGet("to"));
                                } else {
                                    $builder->where("transaction.transaction_date", date("Y-m-d"));
                                }

                                $usr = $builder
                                    ->groupBy("transactiond.product_id")
                                    ->orderBy("product.product_name", "ASC")
                                    ->get();
                                //echo $this->db->getLastquery();
                                $no = 1;
                                $tbill = 0;
                                $tpay = 0;
                                $tchange = 0;
                                $tterjual=0;
                                $tterbeli=0;
                                $tselisih=0;
                                $t60=0;
                                $t40=0;
                                foreach ($usr->getResult() as $usr) {
                                    if ($usr->terbeli == 0) {
                                        $usr->terbeli = $arpbeli[$usr->product_id] * $usr->jumlah;
                                    }
                                    $selisih = $usr->terjual - $usr->terbeli;
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $usr->transaction_date; ?></td>
                                        <td><?= $usr->product_name; ?></td>
                                        <td><?= $usr->jumlah; ?></td>
                                        <td><?= number_format($usr->terjual, 0, ".", ",");
                                            $tterjual += $usr->terjual; ?></td>
                                        <td><?= number_format($usr->terbeli, 0, ".", ",");
                                            $tterbeli += $usr->terbeli; ?></td>
                                        <td><?= number_format($selisih, 0, ".", ",");
                                            $tselisih += $selisih; ?></td>
                                        <td><?php $npuluh = 60 / 100 * $selisih;
                                            echo number_format($npuluh, 0, ".", ",");
                                            $t60 += $npuluh; ?></td>
                                        <td><?php $epuluh = 40 / 100 * $selisih;
                                            echo  number_format($epuluh, 0, ".", ",");
                                            $t40 += $epuluh; ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">Total</td>
                                    <td class="text-right"><?= number_format($tterjual, 0, ".", ","); ?></td>
                                    <td class="text-right"><?= number_format($tterbeli, 0, ".", ","); ?></td>
                                    <td class="text-right"><?= number_format($tselisih, 0, ".", ","); ?></td>
                                    <td class="text-right"><?= number_format($t60, 0, ".", ","); ?></td>
                                    <td class="text-right"><?= number_format($t40, 0, ".", ","); ?></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "<?= $title; ?>";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>