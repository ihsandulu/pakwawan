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
                       
                        <?php if (!isset($_POST['new']) && !isset($_POST['edit']) && !isset($_GET['report'])) { ?>
                            <?php if (isset($_GET["user_id"])) { ?>
                                <form action="<?= site_url("user"); ?>" method="get" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button class="btn btn-warning btn-block btn-lg" value="OK" style="">Back</button>
                                    </h1>
                                </form>
                            <?php } ?>
                            <?php 
                            if (
                                (
                                    isset(session()->get("position_administrator")[0][0]) 
                                    && (
                                        session()->get("position_administrator") == "1" 
                                        || session()->get("position_administrator") == "2"
                                    )
                                ) ||
                                (
                                    isset(session()->get("halaman")['18']['act_create']) 
                                    && session()->get("halaman")['18']['act_create'] == "1"
                                )
                            ) { ?>
                            <form method="post" class="col-md-2">
                                <h1 class="page-header col-md-12">
                                    <button name="new" class="btn btn-info btn-block btn-lg" value="OK" style="">New</button>
                                    <input type="hidden" name="purchase_id" />
                                </h1>
                            </form>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $namabutton = 'name="change"';
                                $judul = "Update Pembelian";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Tambah Pembelian";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">     
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="purchase_date">Tgl Pembelian:</label>
                                    <div class="col-sm-10">
                                        <input required type="date" autofocus class="form-control" id="purchase_date" name="purchase_date" placeholder="" value="<?= $purchase_date; ?>">
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="purchase_no">Nomor Pembelian/Faktur:</label>
                                    <div class="col-sm-10">
                                        <input type="text" autofocus class="form-control" id="purchase_no" name="purchase_no" placeholder="Penomoran akan otomatis jika dikosongkan" value="<?= $purchase_no; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="supplier_id">Supplier:</label>
                                    <div class="col-sm-10">
                                        <?php
                                        $supplier = $this->db->table("supplier")
                                            ->where("store_id",session()->get("store_id"))
                                            ->orderBy("supplier_name", "ASC")
                                            ->get();
                                        //echo $this->db->getLastQuery();
                                        ?>
                                        <select required class="form-control select" id="supplier_id" name="supplier_id">
                                            <option value="" <?= ($supplier_id == "") ? "selected" : ""; ?>>Pilih Supplier</option>
                                            <?php
                                            foreach ($supplier->getResult() as $supplier) { ?>
                                                <option value="<?= $supplier->supplier_id; ?>" <?= ($supplier_id == $supplier->supplier_id) ? "selected" : ""; ?>><?= $supplier->supplier_name; ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>      
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="purchase_ppn">PPN(%):</label>
                                    <div class="col-sm-12">
                                        <input onkeyup="tagihan()" type="number" autofocus class="form-control" id="purchase_ppn" name="purchase_ppn" placeholder="" value="<?= $purchase_ppn; ?>">
                                    </div>
                                </div>                                
                                

                                <input type="hidden" name="purchase_id" value="<?= $purchase_id; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <button type="button"  class="btn btn-warning col-md-offset-1 col-md-5" onClick="location.href='<?= base_url("purchase"); ?>'">Back</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
                        <?php 
                        if(isset($_GET["from"])&&$_GET["from"]!=""){
                            $from=$_GET["from"];
                        }else{
                            $from=date("Y-m-d");
                        }

                        if(isset($_GET["to"])&&$_GET["to"]!=""){
                            $to=$_GET["to"];
                        }else{
                            $to=date("Y-m-d");
                        }

                        ?>
                        <form class="form-inline" >
                            <label for="from">Dari:</label>&nbsp;
                            <input type="date" id="from" name="from" class="form-control" value="<?=$from;?>">&nbsp;
                            <label for="to">Ke:</label>&nbsp;
                            <input type="date" id="to" name="to" class="form-control" value="<?=$to;?>">&nbsp;
                            <?php if(isset($_GET["report"])){?>                                
                            <input type="hidden" id="report" name="report" class="form-control" value="<?=$this->request->getGet("report");?>">&nbsp;
                            <?php }?>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                        <?php if ($message != "") { ?>
                            <div class="alert alert-info alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong><?= $message; ?></strong>
                            </div>
                        <?php } ?>

                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                                <thead class="">
                                    <tr>
                                        <?php //if (!isset($_GET["report"])) { ?>
                                        <th>Aksi.</th>
                                        <?php //}?>
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>Toko</th>
                                        <th>Supplier</th>
                                        <th>No. Pembelian</th>
                                        <th>Kasir</th>
                                        <th>Produk</th>
                                        <th>Nominal</th>
                                        <th>PPN</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $currentURL = current_url();
                                    $currentURL = str_replace('/index.php', '', $currentURL);
                                    $params   = $_SERVER['QUERY_STRING'];
                                    $fullURL = urlencode($currentURL . '?' . $params);
                                    $builder = $this->db
                                        ->table("purchase")
                                        ->join("(SELECT purchase_id AS purchaseid,SUM(purchased_price)AS nominal,SUM(purchased_bill)AS stlhppn FROM purchased GROUP BY purchase_id)purchased", "purchased.purchaseid=purchase.purchase_id", "left")
                                        ->join("supplier", "supplier.supplier_id=purchase.supplier_id", "left")
                                        ->join("store", "store.store_id=purchase.store_id", "left")
                                        ->join("user", "user.user_id=purchase.cashier_id", "left")
                                        ->where("purchase.store_id",session()->get("store_id"));
                                    if(isset($_GET["from"])&&$_GET["from"]!=""){
                                        $builder->where("purchase.purchase_date >=",$this->request->getGet("from"));
                                    }else{
                                        $builder->where("purchase.purchase_date",date("Y-m-d"));
                                    }

                                    if(isset($_GET["to"])&&$_GET["to"]!=""){
                                        $builder->where("purchase.purchase_date <=",$this->request->getGet("to"));
                                    }else{
                                        $builder->where("purchase.purchase_date",date("Y-m-d"));
                                    }
                                    $usr= $builder
                                        ->orderBy("purchase.purchase_id", "ASC")
                                        ->get();
                                    // echo $this->db->getLastquery();die;
                                    $no = 1;
                                    $thargasetelahppn=0;
                                    $tnominal=0;
                                    foreach ($usr->getResult() as $usr) { 
                                        if($usr->nominal>0){$usr->nominal=$usr->nominal;}else{$usr->nominal=0;}
                                        if($usr->purchase_ppn==0){
                                            $hargasetelahppn=$usr->stlhppn;
                                            $usr->nominal=$hargasetelahppn;
                                        }else{
                                            $hargasetelahppn=$usr->nominal+($usr->nominal*$usr->purchase_ppn/100);
                                        }
                                        $payment=$this->db
                                        ->table("payment")
                                        ->select("SUM(payment_nominal)AS bayar")
                                        ->where("purchase_id",$usr->purchase_id)
                                        ->get();
                                        $bayar=0;
                                        foreach ($payment->getResult() as $payment) {
                                            $bayar=$payment->bayar;
                                        }
                                        $sisa=$hargasetelahppn-$bayar;
                                        ?>
                                        <tr>    
                                            <td style="padding-left:0px; padding-right:0px;">                                                    
                                                <?php 
                                                if (
                                                    (
                                                        isset(session()->get("position_administrator")[0][0]) 
                                                        && (
                                                            session()->get("position_administrator") == "1" 
                                                            || session()->get("position_administrator") == "2"
                                                        )
                                                    ) ||
                                                    (
                                                        isset(session()->get("halaman")['18']['act_read']) 
                                                        && session()->get("halaman")['18']['act_read'] == "1"
                                                    )
                                                ) { ?>
                                                
                                                <?php if (isset($_GET["report"])) {$report="&report=OK";}else{$report="";} ?>
                                                <a href="<?=base_url("purchased?supplier_id=".$usr->supplier_id."&purchase_id=".$usr->purchase_id."&purchase_no=".$usr->purchase_no."&purchase_ppn=".$usr->purchase_ppn.$report);?>" class="btn btn-xs btn-info"><span class="fa fa-cubes"></span></a>
                                                <?php }?>
                                                
                                                <?php if (!isset($_GET["report"])) { ?>
                                                <?php 
                                                if (
                                                    (
                                                        isset(session()->get("position_administrator")[0][0]) 
                                                        && (
                                                            session()->get("position_administrator") == "1" 
                                                            || session()->get("position_administrator") == "2"
                                                        )
                                                    ) ||
                                                    (
                                                        isset(session()->get("halaman")['18']['act_create']) 
                                                        && session()->get("halaman")['18']['act_create'] == "1"
                                                    )
                                                ) { ?>
                                                <a href="<?=base_url("payment?purchase_id=".$usr->purchase_id."&purchase_no=".$usr->purchase_no."&kas_nominal=".$hargasetelahppn."&supplier_id=".$usr->supplier_id."&url=".$fullURL);?>" class="btn btn-xs btn-success"><span class="fa fa-money"></span></a>
                                                <?php }?>
                                                <?php }?>
                                                
                                                <?php if (!isset($_GET["report"])) { ?>
                                                <?php 
                                                if (
                                                    (
                                                        isset(session()->get("position_administrator")[0][0]) 
                                                        && (
                                                            session()->get("position_administrator") == "1" 
                                                            || session()->get("position_administrator") == "2"
                                                        )
                                                    ) ||
                                                    (
                                                        isset(session()->get("halaman")['18']['act_update']) 
                                                        && session()->get("halaman")['18']['act_update'] == "1"
                                                    )
                                                ) { ?>
                                                <form method="post" class="btn-action" style="">
                                                    <button class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                    <input type="hidden" name="purchase_id" value="<?= $usr->purchase_id; ?>" />
                                                </form>
                                                <?php }?>
                                                <?php }?>
                                                
                                                <?php if (!isset($_GET["report"])) { ?>                                                    
                                                <?php 
                                                if (
                                                    (
                                                        isset(session()->get("position_administrator")[0][0]) 
                                                        && (
                                                            session()->get("position_administrator") == "1" 
                                                            || session()->get("position_administrator") == "2"
                                                        )
                                                    ) ||
                                                    (
                                                        isset(session()->get("halaman")['18']['act_delete']) 
                                                        && session()->get("halaman")['18']['act_delete'] == "1"
                                                    )
                                                ) { ?>
                                                <form method="post" class="btn-action" style="">
                                                    <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                    <input type="hidden" name="purchase_id" value="<?= $usr->purchase_id; ?>" />
                                                </form>
                                                <?php }?>
                                                <?php } ?>
                                            </td>
                                            <td><?= $no++; ?></td>                                                  
                                            <td><?= $usr->purchase_date; ?></td>
                                            <td><?= $usr->store_name; ?></td>
                                            <td><?= $usr->supplier_name; ?></td>
                                            <td><?= $usr->purchase_no; ?></td>
                                            <td><?= $usr->user_name; ?></td>
                                            <td>
                                                <?php $purchased=$this->db
                                                ->table("purchased")
                                                ->join("product","product.product_id=purchased.product_id","left")
                                                ->where("purchase_id",$usr->purchase_id)
                                                ->get();
                                                $bayar=0;
                                                foreach ($purchased->getResult() as $purchased) {
                                                    echo $purchased->product_name." (".$purchased->purchased_qty."), ";
                                                }
                                                ?>
                                            </td>
                                            <td><?= number_format(floatval($usr->nominal),0,".",",");$tnominal+=$usr->nominal; ?></td>
                                            <td><?= $usr->purchase_ppn; ?> %</td>
                                            <td>
                                                <?= number_format(floatval($hargasetelahppn),0,".",",");$thargasetelahppn+=$hargasetelahppn; ?>
                                                <?php if($bayar>0){?>
                                                <a href="<?=base_url("payment?purchase_id=".$usr->purchase_id."&purchase_no=".$usr->purchase_no."&kas_nominal=".$hargasetelahppn."&supplier_id=".$usr->supplier_id."&url=".$fullURL);?>">
                                                <br/><small>(Bayar:<?=number_format($bayar,0,".",","); ?>)</small> 
                                                <br/><small>(Sisa:<?=number_format($sisa,0,".",","); ?>)</small> 
                                                </a> 
                                                <?php }?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    
                                    <tr>
                                        <?php //if (!isset($_GET["report"])) { ?>
                                        <td></td>
                                        <?php //}?>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">Total&nbsp;</td>
                                        <td><?= number_format($tnominal,0,".",","); ?></td>
                                        <td></td>
                                        <td><?= number_format($thargasetelahppn,0,".",","); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>                        
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "<?=(isset($_GET["report"]))?"Laporan":"";?> Pembelian";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>