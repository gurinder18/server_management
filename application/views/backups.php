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
                <?php if($role==1){ ?>
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>add-backup"><i class="fa fa-plus"></i> Add New</a>
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>schedule-backups"><i class="fa fa-plus"></i> Add Schedule</a>
                <?php } ?>
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
                  <table class="table table-hover td-align">
                    <tr>
                      <th><?php if($role_slug=="sys.admin"){ ?><input type="checkbox" id="delete_all" /><?php } ?></th>
                      <th class="td-align">User</th>
                      <th class="td-align">Client</th>
                      <th class="td-align">Server</th>
                      <th class="td-align">Schedule Type</th>
                      <th class="td-align">Schedule Timings</th>
                      <th class="td-align" class="text-center">Actions</th>
                    </tr>
                    <tr>
                    <td></td> 
                    <form role="form" id="searchBackup" action="<?php echo base_url() ?>backups" method="get" role="form">
                    
                        <td><?php if($role_slug=="sys.admin"){ ?>
                            <select class="form-control required" id="user" name="user" > 
                                <option value="">Select User</option>
                                <?php
                                    if(!empty($users))
                                    {
                                        foreach ($users as $us)
                                        { 
                                ?>
                                <option value="<?php echo $us->userId ?>"
                                <?php
                                    if(isset($_GET['search_backup'])=='Search')
                                    { 
                                        if(!($_GET['user']) == NULL)
                                        {
                                            if($_GET['user']==$us->userId)
                                            {
                                                echo "selected";
                                            } 
                                        }
                                    } 
                                ?>
                                ><?php echo $us->name ?></option>
                                <?php
                                        }
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
                                <option value="<?php echo $cl->id ?>"
                                <?php
                                if(isset($_GET['search_backup'])=='Search')
                                { 
                                    if(!($_GET['client']) == NULL)
                                    {
                                            if($_GET['client']== $cl->id)
                                            {
                                                echo "selected";
                                            } 
                                    }
                                }
                                ?>
                                ><?php echo $cl->name ?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control required" id="server" name="server" > 
                                <option value="">Select server</option>
                                <?php
                                    if(!empty($backupRecords))
                                    {
                                        foreach ($backupRecords as $br)
                                        { 
                                ?>
                                <option value="<?php echo $br->serverId ?>"
                                <?php
                                       if(isset($_GET['search_backup'])=='Search')
                                       { 
                                           if(!($_GET['server']) == NULL)
                                           {
                                                   if($_GET['server']==$br->serverId)
                                                   {
                                                       echo "selected";
                                                   } 
                                           }
                                       }
                                   ?>
                                   ><?php echo $br->ServerName ?></option>
                                   <?php
                                           }
                                       }
                                   ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control required" id="scheduleType" name="scheduleType" > 
                                <option value="">Select type</option>
                                <option value="Daily" 
                                    <?php
                                    if(isset($_GET['search_backup'])=='Search'){ 
                                        if($_GET['scheduleType'] =="Daily" )
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                >Daily</option>
                                <option value="Weekly" 
                                    <?php
                                    if(isset($_GET['search_backup'])=='Search'){ 
                                        if($_GET['scheduleType'] =="Weekly" )
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                >Weekly</option>
                                <option value="Monthly" 
                                    <?php
                                    if(isset($_GET['search_backup'])=='Search'){ 
                                        if($_GET['scheduleType'] =="Monthly" )
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                >Monthly</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control required" id="scheduleTimings" name="scheduleTimings" > 
                                <?php
                                        if(isset($_GET['search_backup'])=='Search')
                                        { 
                                            if(!empty($_GET['scheduleTimings'] ))
                                            {
                                                $timing = $_GET['scheduleTimings'];
                                                foreach($backupRecords as $record)
                                                {
                                                }
                                                    echo "<option value='$record->scheduleTimings'>$timing</option>";
                                            }
                                        }
                                        else{ 
                                            echo "<option value=''>Select schedule timings</option>";
                                        }
                                ?>
                            </select>
                        </td>
                        <td> 
                            <input type="submit" class="btn btn-primary" name='search_backup' value="Search" />
                        </td>
                        </form>
                    </tr>
                    <form role="form" id="deleteBackup" action="<?php echo base_url() ?>deleteBackup" method="post" role="form">
                    
                    <?php
                    if(!empty($backupRecords))
                    {
                        foreach($backupRecords as $record)
                        { 
                    ?>
                    <tr>
                      <td><?php if($role_slug=="sys.admin"){ ?><input type="checkbox" class="delete_backup" value="<?php echo $record->id; ?>" name="delete_backups[]"/><?php } ?></td>
                      <td><?php echo $record->UserName ?></td>
                      <td><?php echo $record->ClientName ?></td>
                      <td><?php echo $record->ServerName ?></td>
                      <td><?php echo $record->scheduleType ?></td>
                      <td><?php echo $record->scheduleTimings ?></td>
                      <td class="text-center">
                          <a class="btn btn-sm btn-detail" href="<?php echo base_url().'backup-details/'.$record->id; ?>"><i class="fa fa-search-plus"></i></a>
                          <?php if($role_slug=="sys.admin"){ ?>
                             <a class="btn btn-sm btn-info" href="<?php echo base_url().'edit-backup/'.$record->id; ?>"><i class="fa fa-pencil"></i></a>
                              <a class="btn btn-sm btn-danger deleteBackup" href="#" data-id="<?php echo $record->id; ?>"><i class="fa fa-trash"></i></a>
                          <?php } ?>
                      </td> 
                    </tr>
                    <?php
                        }
                       if($role_slug=="sys.admin"){ 
                    ?>
                    <tr>
                        <th colspan='8'><input type="submit" class="btn btn-sm btn-danger " name="delete_backup" value="Delete"/></th>
                    </tr>
                    </form>
                    <?php
                    }}
                    else{
                        echo "<tr><td colspan='8' style='color:red'>No Record Found</td></tr>";
                    }
                    
                    ?>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); 
                   
                    ?>
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
            $("#scheduleTimings").html("<option value=''>Select timings</option>"+
            "<option value='Day'>Day</option><option value='Night'>Night</option>");
        } else if (val == "Weekly") {
            $("#scheduleTimings").html("<option value=''>Select timings</option>"+
                "<option value='Sunday'>Sunday</option>"+
                "<option value='Monday'>Monday</option>"+
                "<option value='Tuesday'>Tuesday</option>"+
                "<option value='Wednesday'>Wednesday</option>"+
                "<option value='Thursday'>Thursday</option>"+
                "<option value='Friday'>Friday</option>"+
                "<option value='Saturday'>Saturday</option>");
        } else if (val == "Monthly") {
           var date =  "<option value=''>Select timings</option>"; 
            for (var i = 1; i <= 31; i++){
                date += "<option value='"+i+"'>"+i+"</option>";
            }
            $("#scheduleTimings").html(date);
        } else if (val == "") {
            $("#scheduleTimings").html("<option value=''>select timings</option>");
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
        var server_text = '<option value="">Select server</option>';
        $.each(servers, function(i, item) 
        {
            server_text+='<option value="'+servers[i].id+'">'+servers[i].name+'</option>';      
        })
        $("#server").html(server_text);
    }
    });
});
$(document).ready(function () {
    $("#delete_all").click(function () {
        $(".delete_backup").prop('checked', $(this).prop('checked'));
    });
    
    $(".delete_backup").change(function(){
        if (!$(this).prop("checked")){
            $("#delete_all").prop("checked",false);
        }
    });
});

</script>
