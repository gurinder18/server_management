<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-arrow-circle-up"></i> Backups Management
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>add-backup"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Backups List</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>Id</th>
                      <th>User</th>
                      <th>Client</th>
                      <th>Server</th>
                      <th>Schedule Type</th>
                      <th>Schedule Timings</th>
                      <th class="text-center">Actions</th>
                    </tr>
                    <tr>
                    <td>#</td>
                    <form role="form" id="searchBackup" action="<?php echo base_url() ?>backups" method="get" role="form">
                    
                        <td>
                            <select class="form-control required" id="user" name="user" > 
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
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <select class="form-control required" id="server" name="server" > 
                                <option value="">Select server</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control required" id="scheduleType" name="scheduleType" > 
                                <option value="">Select schedule type</option>
                                <option value="Daily">Daily</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control required" id="scheduleTimings" name="scheduleTimings" > 
                                <option value="">Select schedule timings</option>
                            </select>
                        </td>
                        <td> 
                            <input type="submit" class="btn btn-primary" name='search_backup' value="Submit" />
                        </td>
                        </form>
                    </tr>
                    <?php
                    if(!empty($backupRecords))
                    {
						$i = 1;
                        foreach($backupRecords as $record)
                        { 
                    ?>
                    <tr>
                      <td><?php echo $i ?></td>
                      <td><?php echo $record->UserName ?></td>
                      <td><?php echo $record->ClientName ?></td>
                      <td><?php echo $record->ServerName ?></td>
                      <td><?php echo $record->scheduleType ?></td>
                      <td><?php echo $record->scheduleTimings ?></td>
                      <td class="text-center">
                          <a class="btn btn-sm btn-detail" href="<?php echo base_url().'backup-details/'.$record->id; ?>"><i class="fa fa-search-plus"></i></a>
                          <a class="btn btn-sm btn-info" href="<?php echo base_url().'edit-backup/'.$record->id; ?>"><i class="fa fa-pencil"></i></a>
                          <a class="btn btn-sm btn-danger deleteBackup" href="#" data-id="<?php echo $record->id; ?>"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php
						$i++;
                        }
                    }
                    else{
                        echo "<tr><td style='color:red'>No Record Found</td></tr>";
                    }
                    
                    ?>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "serverListing/" + value);
            jQuery("#searchList").submit();
        });
    });

    $(document).on("change","#scheduleType",function() {
        var val = $(this).val();
        if (val == "Daily") {
            $("#scheduleTimings").html("<option value='Day'>Day</option><option value='Night'>Night</option>");
        } else if (val == "Weekly") {
            $("#scheduleTimings").html("<option value='Sunday'>Sun</option><option value='Monday'>Mon</option><option value='Tuesday'>Tue</option><option value='Wednesday'>Wed</option><option value='Thursday'>Thur</option><option value='Friday'>Fri</option><option value='Saturday'>Sat</option>");
        } else if (val == "Monthly") {
           var date =  ''; 
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
     
    }
    
	});
});

</script>
