<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-calendar-check-o"></i>Schedules Management
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Today's Schedules List</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th><?php if($role_slug=="sys.admin"){ ?><input type="checkbox" id="delete_all" /><?php } ?></th>
                      <th>Server Name</th>
                      <th>Server IP</th>
                      <th>Hostname</th>
                      <th>Client</th>
                      <th>Status</th>
                      <th class="text-center">Actions</th>
                    </tr>
                    <tr>
                    <td></td> 
                    <form role="form" id="searchBackup" action="<?php echo base_url() ?>schedules" method="get" role="form">
                        <td>
                            <select class="form-control required" id="server" name="server" > 
                                <option value="">Select server</option>
                                <?php
                                   foreach($servers as $ser)
                                    {
                                ?>
                                <option value="<?php echo $ser['id'] ?>" 
                                <?php
                                if(isset($_GET['search_backup'])=='Search')
                                { 
                                    if(!($_GET['server']) == NULL)
                                    {
                                            if($_GET['server']==$ser['id'])
                                            {
                                                echo "selected";
                                            } 
                                    }
                                }
                                ?>><?php echo $ser['name']; ?></option>
                                <?php
                                    }
                                ?>

                               
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control required" id="serverIP" name="serverIP" maxlength="128" placeholder="Search Server IP"
                            value="<?php
                                if(isset($_GET['search_backup'])=='Search'){ 
                                    if(!($_GET['serverIP']) == NULL)
                                    {
                                            echo $_GET['serverIP'];
                                    }
                                }
                                ?>"
                            >
                        </td>
                        <td>
                            <input type="text" class="form-control required" id="hostname" name="hostname" maxlength="128" placeholder="Search Hostname"
                            value="<?php
                                if(isset($_GET['search_backup'])=='Search'){ 
                                    if(!($_GET['hostname']) == NULL)
                                    {
                                            echo $_GET['hostname'];
                                    }
                                }
                                ?>"
                            >
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
                                <option value="<?php echo $cl['id'] ?>"
                                <?php
                                    if(isset($_GET['search_backup'])=='Search'){ 
                                        if(!($_GET['client']) == NULL)
                                        {
                                                if($_GET['client']==$cl['id'])
                                                {
                                                    echo "selected";
                                                } 
                                        }
                                    }
                                ?>
                                 ><?php echo $cl['name'] ?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control required" id="status" name="status" > 
                                <option value="">Select Status</option>
                                <option value="1"
                                    <?php
                                    if(isset($_GET['search_backup'])=='Submit'){ 
                                        if($_GET['status'] == 1)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Pending</option>
                                    <option value="2"
                                    <?php
                                    if(isset($_GET['search_backup'])=='Submit'){ 
                                        if($_GET['status'] == 2)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Inprogress</option>
                                    <option value="3"
                                    <?php
                                    if(isset($_GET['search_backup'])=='Submit'){ 
                                        if($_GET['status'] == 3)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Completed</option>
                                    <option value="4"
                                    <?php
                                    if(isset($_GET['search_backup'])=='Submit'){ 
                                        if($_GET['status'] == 4)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Failed</option>
                                </select>
                        </td>
                        <?php if($role_slug=="sys.admin"){ ?>
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
                        <?php } ?>
                        <td> 
                            <input type="submit" class="btn btn-primary" name='search_backup' value="Search" />
                        </td>
                        </form>
                    </tr>
                    <form role="form" id="deleteBackup" action="<?php echo base_url() ?>deleteBackup" method="post" role="form">
                    
                    <?php
                    if(!empty($scheduleRecords))
                    {
                        foreach($scheduleRecords as $record)
                        { 
                    ?>
                    <tr>
                      <td><?php if($role_slug=="sys.admin"){ ?><input type="checkbox" class="delete_backup" value="<?php echo $record->id; ?>" name="delete_backups[]"/><?php } ?></td>
                      <td><?php echo $record->ServerName ?></td>
                      <td><?php echo $record->ServerIP ?></td>
                      <td><?php echo $record->ServerHostname ?></td>
                      <td><?php echo $record->ClientName ?></td>
                      <td><?php echo $record->ScheduleStatus ?></td>
                      <td class="text-center">
                          <a class="btn btn-sm btn-detail" href="<?php echo base_url().'schedule-details/'.$record->id; ?>"><i class="fa fa-search-plus"></i></a>
                      </td> 
                    </tr>
                    <?php
                        }
                       if($role_slug=="sys.admin"){ 
                    ?>
                    <tr>
                        <td colspan='8'><input type="submit" class="btn btn-sm btn-danger " name="delete_backup" value="Delete"/></td>
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
                   //if (isset($links)) {  echo $links;}
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
