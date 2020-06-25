<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">

            <div class="error-container">
                <div class="error-code">404</div>
                <div class="error-text">page not found</div>
                <div class="error-subtext">Unfortunately we're having trouble loading the page you are looking for. Please wait a moment and try again or use action below.</div>
                <div class="error-actions">                                
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?= site_url('Dashboard');?>"><button class="btn btn-info btn-block btn-lg">Back to dashboard</button></a>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-block btn-lg" onClick="history.back();">Previous page</button>
                        </div>
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</div> 
<script type="text/javascript">
    var url="";
    var actioncolumn="";
</script>
<?php $this->load->view('common/footer');?>