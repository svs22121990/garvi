<?php $this->load->view('common/header'); ?>
<style type="text/css">
    .card {
    margin-bottom: 1.875rem;
    border: none;
    border-radius: 0;
    box-shadow: 0 10px 40px 0 rgba(62, 57, 107, 0.07), 0 2px 9px 0 rgba(62, 57, 107, 0.06);
}
.card {

    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, 0.06);
    border-radius: 0.25rem;

}
.align-items-stretch {
    align-items: stretch !important;
}
.media {
    display: flex;
    align-items: flex-start;
}
.bg-primary.bg-darken-2 {

    background-color: #00A5A8 !important;

}
.bg-primary {

    background-color: #00B5B8 !important;

}
.text-center {

    text-align: center !important;

}
.p-2 {

    padding: 1.5rem !important;

}
.bg-primary {

    background-color: #00B5B8 !important;

}
.font-large-2 {

    font-size: 3rem !important;

}
.bg-gradient-x-primary {

    background-image: linear-gradient(to right, #00A5A8 0%, #4DCBCD 100%);
    background-repeat: repeat-x;

}
.bg-gradient-x-primary {

    background-image: linear-gradient(to right, #00A5A8 0%, #4DCBCD 100%);
    background-repeat: repeat-x;

}
.white {

    color: #FFFFFF !important;

}
.p-2 {

    padding: 1.5rem !important;

}
.media-body {

    flex: 1;

}
</style>
<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>                    

<!-- START BREADCRUMB -->
<?= $breadcrumbs; ?>
<!-- END BREADCRUMB -->                       

<!-- PAGE CONTENT WRAPPER -->
<?php 
if(isset($_SESSION['ASSETSTRACKING'])) {
	if($_SESSION['ASSETSTRACKING']['type']!='User') {
?>
<div class="page-content-wrap">
    <div class="row">
       
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <?= form_open('dashboard/search',['id'=>'serch_date']); ?>
                  <div class="form-group row" style="padding-top: 20px;" >
                    <label class="col-md-2"> select Date<span style="color: red">*</span> <span  id="purchase_date_error" style="color: red"></span></label>
                    <div class="col-md-3">
                      <!--<input type="text" name="purchase_date" id="purchase_date" class="form-control datepicker" placeholder="Purchase Date" required>-->
            <input type="text" class="form-control datepicker" name="daterange" value="" />
                    </div>
                    <div class="col-md-2">
                      <button type="submit" class="btn btn-success">Search</button>
                    </div>
          <div  class="col-md-4">
          


          </div>
                  </div>
                  <?= form_close(); ?>

                        <div class="panel-heading ui-draggable-handle">
                            <div class="panel-title-box">
                                <h3>Sales</h3>
                            </div>                                    
                                                               
                        </div>                                
                        <div class="panel-body ">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions example_datatable">
                        <thead>
                             <tr>
                                <th class="text-center">Sr No</th>
                                <th class="text-center">User</th>
                                <th class="text-center">Today's Sales</th>
                                <th class="text-center">Current Month Sales</th>
                                <th class="text-center">Current Year Sales</th>
                                <th class="text-center">Total Sales</th>
                            </tr>
                        </thead>
                        <tbody>                           
                        </tbody>
                    </table>
                </div> 
                </div>                                   
                    </div>
                </div>
    </div>

    <!--<div class="row">
		<div class="col-md-12"></div>
		
		<div class="col-md-12">
			<div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                    <div class="panel-title-box">
                        <h3>Total Sales</h3>
                    </div>                                    
                    <ul style="margin-top: 2px;" class="panel-controls">
                        <li><a class="panel-fullscreen" href="#"><span class="fa fa-expand"></span></a></li>
                    </ul>                                    
                </div>                                
                <div class="panel-body padding-0" style="">
                    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div>                                    
            </div>
		</div>
    </div>-->
</div>
                
<?php 
	} else {
?>
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-content">
                    
                    <div class="media align-items-stretch">
                        
                        <div class="p-2 text-center bg-primary bg-darken-2">
                            
                        </div>
                        <div class="p-2 bg-gradient-x-primary white media-body">
                            <h4 class="white" style="font-weight: bold;padding-top: 10px;">Yearly Sale</h4>
                            
                        </div>
                    </div>
                    <div class="media align-items-stretch" style="margin-top:0px;">
                        
                        <div class="p-2 text-center bg-primary bg-darken-2">

                            <i class="fa fa-shopping-cart font-large-2"></i>
                        </div>
                        <div class="p-2 bg-gradient-x-primary white media-body">
                            <h5 class="white" style="font-weight: bold"><?= $yearSale; ?></h5>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-content">
                    
                    <div class="media align-items-stretch">
                        
                        <div class="p-2 text-center bg-primary bg-darken-2">
                            
                        </div>
                        <div class="p-2 bg-gradient-x-primary white media-body">
                            <h4 class="white" style="font-weight: bold;padding-top: 10px;">Monthly Sale</h4>
                            
                        </div>
                    </div>
                    <div class="media align-items-stretch" style="margin-top:0px;">
                        
                        <div class="p-2 text-center bg-primary bg-darken-2">
                            <i class="fa fa-calendar font-large-2"></i>
                        </div>
                        <div class="p-2 bg-gradient-x-primary white media-body">
                            <h5 class="white" style="font-weight: bold"><?= $monthSale; ?></h5>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card">
                <div class="card-content">
                    
                    <div class="media align-items-stretch">
                        
                        <div class="p-2 text-center bg-primary bg-darken-2">
                            
                        </div>
                        <div class="p-2 bg-gradient-x-primary white media-body">
                            <h4 class="white" style="font-weight: bold;padding-top: 10px;">Today Sale</h4>
                            
                        </div>
                    </div>
                    <div class="media align-items-stretch" style="margin-top:0px;">
                        
                        <div class="p-2 text-center bg-primary bg-darken-2">
                            <!--<i class="fa fa-user font-large-2"></i>-->
                            <i class="fa fa-clock font-large-2"></i>
                            <i class="fa fa-clock-o font-large-2" aria-hidden="true"></i>
                        </div>
                        <div class="p-2 bg-gradient-x-primary white media-body">
                            <h5 class="white" style="font-weight: bold"> <?= $todaySale; ?></h5>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
		<div class="col-md-12">
            <div class="panel-body ">
                <a href="<?= base_url(); ?>index.php/Invoice/create" class="btn btn-success" style="margin-bottom: 10px;">Create Invoice</a>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-action">
                        <thead>
                             <tr>
                                <th class="text-center">Sr No</th>
                                <th class="text-center">Invoice No</th>
                                <th class="text-center">GST Number</th>
                                <th class="text-center">Customer Name</th>
                                <th class="text-center">Date of Invoice</th>
                                <!--<th class="text-center">Serial Number of Invoice</th>-->
                                <th class="text-center">Invoice Amount</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i=1; foreach($inventory as $ii)
                          {
                               ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $ii->invoice_no; ?></td>
                                        <td><?= $ii->gst_number; ?></td>
                                        <td><?= $ii->receiver_bill_name; ?></td>
                                        <td><?= $ii->date_of_invoice; ?></td>
                                        <!--<td><?= $ii->serial_no_of_invoice ?></td>-->
                                        <td><?= "Rs. ".number_format($ii->sumAmount,2); ?></td>

                                    </tr>
                               <?php 
                                
                          } ?>      
                          
                         
                        </tbody>       
                    </table>
                </div>
                </div>
        </div>
	</div>
</div>
<?php
	}
}
?>

<!-- END DEFAULT DATATABLE -->
<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>


<?php $this->load->view('common/footer'); ?>
<?php 
if(isset($_SESSION['ASSETSTRACKING'])) {
	if($_SESSION['ASSETSTRACKING']['type']!='User') {
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
// Create the chart
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total sales'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.2f}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b>'
    },

    "series": [
        {
            "name": "User",
            "data": <?php echo $response; ?> 
        }
    ]
});
</script>
<?php 
	}
}
?>