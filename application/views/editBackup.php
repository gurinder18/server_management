<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_style.min.css" rel="stylesheet" type="text/css" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-arrow-circle-up"></i> Backups Management
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
            
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Backups Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" id="addBackup" action="<?php echo base_url() ?>addBackup" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="user">Select User *</label>
                                        <select class="form-control required" id="user" name="user" > 
                                            <option value="">Select User</option>
                                            <?php
                                            if(!empty($users))
                                            {
                                                foreach ($users as $us)
                                                { 
                                                    $sele = '';
                                                    if($us->userId == $backupInfo['userId']) {
                                                        $sele = 'selected';
                                                    }   
                                                    ?>
                                                    <option value="<?php echo $us->userId ?>" <?php echo $sele; ?>><?php echo $us->name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client">Client *</label>
                                        <select class="form-control required" id="client" name="client" > 
                                            <option value="">Select Client</option>
                                            <?php
                                            if(!empty($clients))
                                            {
                                                foreach ($clients as $cl)
                                                { 
                                                    ?>
                                                    <option value="<?php echo $cl->id ?>"><?php echo $cl->name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="server">Server *</label>
                                        <select class="form-control required" id="server" name="server" > 
                                            <option value="">Select server</option>
                                            <option value="7">Linux </option>
                                            <?php /*
                                            if(!empty($servers))
                                            {
                                                foreach ($servers as $sv)
                                                { 
                                                    ?>
                                                    <option value="<?php echo $sv->id ?>"><?php echo $sv->name ?></option>
                                                    <?php
                                                }
                                            }*/
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="scheduleType">Schedule type *</label>
                                        <select class="form-control required" id="scheduleType" name="scheduleType" > 
                                            <option value="">Select schedule type</option>
                                            <option value="Daily">Daily</option>
                                            <option value="Weekly">Weekly</option>
                                            <option value="Monthly">Monthly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="scheduleTimings">Schedule timings *</label>
                                        <select class="form-control required" id="scheduleTimings" name="scheduleTimings" > 
                                            <option value="">Select schedule timings</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="information">Additional Information</label>
                                       <textarea class="form-control" id="information" name="information"></textarea>
                                    </div>
                                </div>    
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
    
</div>
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1//js/froala_editor.pkgd.min.js"></script>
<script>
  $(function() {
    $('textarea').froalaEditor()
  });
</script>
<script>
    $(document).ready(function () {
    $("#scheduleType").change(function () {
        var val = $(this).val();
        if (val == "Daily") {
            $("#scheduleTimings").html("<option value='Day'>Day</option><option value='Night'>Night</option>");
        } else if (val == "Weekly") {
            $("#scheduleTimings").html("<option value='Sunday'>Sun</option><option value='Monday'>Mon</option><option value='Tuesday'>Tue</option><option value='Wednesday'>Wed</option><option value='Thursday'>Thur</option><option value='Friday'>Fri</option><option value='Saturday'>Sat</option>");
        } else if (val == "Monthly") {
            for (var i = 0; i <= 31; i++){
            $("#scheduleTimings").html("<option value='"+i+"'>"+i+"</option>");
            }
        } else if (val == "") {
            $("#scheduleTimings").html("<option value=''>select schedule timings</option>");
        }
    });
});


    function getServer(val) {
	$.ajax({
	type: "POST",
	url: baseURL + "getServers",
	data:'clientId='+val,
	success: function(data){
		$("#server").html(data);
	}
	});
}
   
</script>