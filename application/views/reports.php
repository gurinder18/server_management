<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-th"></i>Backup Schedule Report
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Schedules List</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>Date From</th>
                      <th>Date To</th>
                      <th>Client</th>
                      <th>Server</th>
                      <th>User</th>
                      <th>Status</th>
                      <th>Day</th>
                      <th class="text-center">Actions</th>
                    </tr>
                    <tr>
                    <form role="form" id="searchBackup" action="<?php echo base_url() ?>backup-report" method="get" role="form">
                        <td class="date_search">
                            <div class="form-group">
                                <div class="date">
                                    <input type="text" class="form-control datepicker" id="datepicker" name="fromDate">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <div class="date">
                                    <input type="text" class="form-control datepicker" id="datepicker" name="toDate">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </td>
                        <td>
                            <select class="form-control " id="client" name="client" > 
                                <option value="">Select Client</option>
                                <?php
                                    if(!empty($clients))
                                    {
                                        foreach ($clients as $cl)
                                        { 
                                ?>
                                <option value="<?php echo $cl['id'] ?>"><?php echo $cl['name'] ?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control " id="server" name="server" > 
                                <option value="">Select server</option>
                                <?php
                                    if(!empty($servers))
                                    {
                                        foreach ($servers as $br)
                                        { 
                                ?>
                                <option value="<?php echo $br['id'] ?>"><?php echo $br['name'] ?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control " id="user" name="user" > 
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
                            <select class="form-control " id="status" name="status" > 
                                <option value="">Select Status</option>
                                <option value="1">Pending</option>
                                <option value="2">Inprogress</option>
                                <option value="3">Completed</option>
                                <option value="4">Failed</option>
                            </select>
                        </td>
                        <?php if($role_slug=="sys.admin"){ ?>
                        <td>
                            
                        </td>
                        <?php } ?>
                        <td> 
                            <input type="submit" class="btn btn-primary" name='search_BackupSchedule' value="Submit" />
                        </td>
                        </form>
                    </tr>
                    <?php
                    if(!empty($scheduleRecords))
                    {
                        foreach($scheduleRecords as $record)
                        { 
                    ?>
                    <tr>
                      <td colspan="2" align="center"><?php echo $record->date ?></td>
                      <td><?php echo $record->ClientName ?></td>
                      <td><?php echo $record->ServerName ?></td>
                      <td><?php echo $record->UserName ?></td>
                      <td><?php echo $record->ScheduleStatus ?></td>
                      <td><?php echo $record->Day ?></td>
                      <td class="text-center">
                          <a class="btn btn-sm btn-detail" href="<?php echo base_url().'schedule-details/'.$record->id; ?>"><i class="fa fa-search-plus"></i></a>
                      </td> 
                    </tr>
                    <?php
                        }
                       if($role_slug=="sys.admin"){ 
                    ?>
                    <tr>
                        
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
                    <?php 
                       // if(!current_url() ==  (base_url().'backups-report/')){
                    ?>
                    <a href="<?php echo base_url(); ?>backups-report" >All</a>
                    <?php 
                        //}
                        //else{
                           
                            echo $this->pagination->create_links();
                        //}
                    
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap-datepicker.min.js" charset="utf-8"></script>

<script>
  $(function () {
    
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true
    });
  });
</script>
