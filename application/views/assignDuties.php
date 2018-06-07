<link href="<?php echo base_url(); ?>assets/dist/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/dist/css/froala_style.min.css" rel="stylesheet" type="text/css" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-arrow-circle-up"></i> Assign Duties
      </h1>
    </section>
    
    <section class="content">
        <div class="row">
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
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
            
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <div class="box-body">
                        <form role="form" id="addBackup" action="<?php echo base_url() ?>request-user" method="post" role="form">
                            <div class="row">
                                 <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="user">Select User *</label>
                                        <select class="form-control required" id="user" name="user" required> 
                                            <option value="">Select User</option>
                                            <?php
                                            if(!empty($users))
                                            {
                                                foreach ($users as $us)
                                                { 
                                                    ?>
                                                    <option value="<?php echo $us->userId ?>"><?php echo $us->name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client">Start Date *</label>
                                        <input class="form-control required"  type="date" name="startDate" id="startDate" />
                                        
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="client">End Date *</label>
                                        <input class="form-control required"  type="date" name="endDate" id="endDate" />
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <input type="submit" class="btn btn-primary" name='request_user' value="Submit" />
                                <input type="reset" class="btn btn-default" value="Reset" />
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                    <?php
                        if(!empty($assigned_duties))
                        {
                    ?>
                    <div class="box-body">
                        <div class="box-header">
                            <h3 class="box-title">Duties Assigned By Me</h3>
                        </div><!-- /.box-header -->
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="box-body table-responsive">
                                    <table class="table table-hover td-align">
                                        <tr>
                                            <th class="td-align">Start date</th>
                                            <th class="td-align">End date</th>
                                            <th class="td-align">Total days</th>
                                            <th class="td-align">Assigned to</th>
                                            <th class="td-align">Status</th>
                                        </tr>
                                        <?php
                                            foreach ($assigned_duties as $duties)
                                            {
                                                if($duties->endDate >= date("Y-m-d")) 
                                                {
                                                    $start = $duties->startDate;
                                                    $st = explode(" ",$start);
                                                    $StartDate = $st[0];
                                                    $end = $duties->endDate;
                                                    $en = explode(" ",$end);
                                                    $EndDate = $en[0];
                                        ?>
                                        <tr>
                                            <td><?php echo $StartDate; ?></td>
                                            <td><?php echo $EndDate; ?></td>
                                            <td><?php echo $duties->numDays; ?></td>
                                            <td><?php echo $duties->UserName; ?></td>
                                            <td style=
                                                <?php if($duties->status == 0){echo "color:red"; }
                                                      elseif($duties->status == 1){echo "color:green";}
                                                      elseif($duties->status == 2){echo "color:brown";}       
                                                ?>
                                            >
                                                <?php if($duties->status == 0){echo "Pending"; }
                                                      elseif($duties->status == 1){echo "Accepted";}
                                                      elseif($duties->status == 2){echo "Rejected";}       
                                                ?>
                                            </td>
                                        </tr>
                                        <?php }
                                        } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.box-body -->    
                    <?php } ?>
                    <?php
                        if(!empty($myAssigned_duties))
                        {
                    ?>
                    <div class="box-body">
                        <div class="box-header">
                            <h3 class="box-title">Duties Assigned To Me</h3>
                        </div><!-- /.box-header -->
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="box-body table-responsive">
                                    <table class="table table-hover td-align">
                                        <tr>
                                            <th class="td-align">Start date</th>
                                            <th class="td-align">End date</th>
                                            <th class="td-align">Total days</th>
                                            <th class="td-align">Assigned by</th>
                                            <th class="td-align">Status</th>
                                        </tr>
                                        <?php
                                            foreach ($myAssigned_duties as $myDuties)
                                            {
                                                if($myDuties->endDate >= date("Y-m-d")) 
                                                {
                                                    $start = $myDuties->startDate;
                                                    $st = explode(" ",$start);
                                                    $StartDate = $st[0];

                                                    $end = $myDuties->endDate;
                                                    $en = explode(" ",$end);
                                                    $EndDate = $en[0];
                                        ?>
                                        <tr>
                                            <td><?php echo $StartDate; ?></td>
                                            <td><?php echo $EndDate; ?></td>
                                            <td><?php echo $myDuties->numDays; ?></td>
                                            <td><?php echo $myDuties->UserName; ?></td>
                                            <td style=
                                                <?php if($myDuties->status == 0){echo "color:red"; }
                                                      elseif($myDuties->status == 1){echo "color:green";}
                                                      elseif($myDuties->status == 2){echo "color:brown";}       
                                                ?>
                                            >
                                                <?php if($myDuties->status == 0){echo "Pending"; }
                                                      elseif($myDuties->status == 1){echo "Accepted";}
                                                      elseif($myDuties->status == 2){echo "Rejected";}       
                                                ?>
                                            </td>
                                        </tr>
                                        <?php }
                                        } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.box-body -->    
                    <?php } ?>
                </div> 
            </div> 
        </div>
    </section>
    
</div>
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/froala_editor.pkgd.min.js" type="text/javascript"></script>

<link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap-multiselect.js" type="text/javascript"></script>
<script>
  $(function() {
    $('textarea').froalaEditor()
  });
</script>
<script>
    $(document).on("change","#scheduleType",function() {
        var val = $(this).val();
        if (val == "Daily") {
            $("#scheduleTimings").html("<option value=''>Select schedule timings</option>"+
            "<option value='Day'>Day</option>"+
            "<option value='Night' >Night</option>");
        } else if (val == "Weekly") {
            $("#scheduleTimings").html("<option value=''>Select schedule timings</option>"+
            "<option value='Sunday'>Sun</option>"+
            "<option value='Monday' >Mon</option>"+
            "<option value='Tuesday'>Tue</option>"+
            "<option value='Wednesday'>Wed</option>"+
            "<option value='Thursday' >Thur</option>"+
            "<option value='Friday'>Fri</option>"+
            "<option value='Saturday'>Sat</option>");
           var a =  $( "#scheduleTimings option:selected" ).val();
           
        } else if (val == "Monthly") {
           var date =  "<option value=''>Select schedule timings</option>"; 
            for (var i = 1; i <= 31; i++){
                date += "<option value='"+i+"'>"+i+"</option>";
            }
            $("#scheduleTimings").html(date);
        } else if (val == "") {
            $("#scheduleTimings").html("<option value=''>select schedule timings</option>");
        }
    });

$(document).on("change","#client",function(){
 
    var val = $(this).val();
    $.ajax({
	type: "POST",
	url: baseURL + "getServers/"+val,
	data:'clientId='+val,
	success: function(data){
        var obj = JSON.parse(data);
        var servers = obj.servers;
       
        var server_text = '';
        $.each(servers, function(i, item) {
            server_text+='<option value="'+servers[i].id+'">'+servers[i].name+'</option>';
           
        }) 
      
        $("#server").html(server_text);
        $("#server").select2({
             multiple: true,
             data : server_text
             });
        //$('#server').multiselect('dataprovider', server_text);
        //$('#server').multiselect('refresh');
    }
	});
    
});
$(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;
   
    $('#startDate').attr('min', maxDate);

    $('#startDate').change(function()
    {
       
        var inputDate = new Date(this.value);

        var incrementedMaxDate = inputDate.setDate(inputDate.getDate() + 7);
        var newMaxDate = new Date(incrementedMaxDate);
        var newMaxMonth = newMaxDate.getMonth() + 1;
        var newMaxDay = newMaxDate.getDate();
        var newMaxYear = newMaxDate.getFullYear();
        if(newMaxMonth < 10)
            var newMaxMonth = '0' + newMaxMonth.toString();
        if(newMaxDay < 10)
            var newMaxDay = '0' + newMaxDay.toString();
        
        var endMaxDate = newMaxYear + '-' + newMaxMonth + '-' + newMaxDay;
        $('#endDate').attr('min', this.value);
        $('#endDate').attr('max', endMaxDate);
    });
});
</script>
