<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-th"></i> Backup Schedule Report
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
                        <?php 
                            if($this->input->get("invalid") != "")
                            {
                                echo '<div class="alert alert-danger alert-dismissable">
                                        '.$this->input->get("invalid").'
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    </div>'; 
                            }
                            if($this->input->get("message") != "")
                            {
                                echo '<div class="alert alert-danger alert-dismissable">
                                        '.$this->input->get("message").'
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    </div>'; 
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                <?php if($role==1){ ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#clearDataModal">
                        <i class="fa fa-minus"></i>  Clear Data
                    </button>
                <?php } ?>
                </div>
            </div>
        </div>
         <!-- Clear Data Modal -->
         <div class="modal fade" id="clearDataModal" tabindex="-1" role="dialog" aria-labelledby="addCommentLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"> 
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                                <h2 class="modal-title" id="clearDataModalLabel">Clear Data</h2>
                        </div>
                        <div class="modal-body">
                            <table class="table table-hover" id="clear_data">
                                <tr>
                                    <th>
                                       <p> Choose date-range to clear files</p>
                                    </th>
                                </tr> 
                                <tr>
                                <form id="clearData" action="<?php echo base_url() ?>clear-data" method="post" enctype="multipart/form-data" role="form">
                                    <th>From Date</th>
                                    <td>   
                                        <input class="form-control required"  type="date" name="fromDate" id="fromDate" required/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>To Date</th>
                                    <td>
                                        <input class="form-control required"  type="date" name="toDate" id="toDate" required/>
                                   </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="clear_data" name="clear_data" >Clear</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- \. Clear data Modal -->
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Report</h3>
                    <div class="pull-right">
                        <a href="<?php echo base_url() ?>export-excel">
                        <i class="glyphicon glyphicon-log-in"></i>&nbsp;&nbsp;Export Excel</a>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover td-align">
                        <tr>
                            <th class="td-align" colspan="2">From Date - To Date</th>
                            <th class="td-align">Client</th>
                            <th class="td-align">Server</th>
                            <th class="td-align">User</th> 
                            <th class="td-align">Status</th>
                            <th class="td-align">Day</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        <tr>
                        <?php 
                            if(current_url() ==  base_url().'backups-report' )
                            { 
                        ?>
                        <form role="form" id="searchBackup" action="<?php echo base_url() ?>backups-report" method="get" role="form">
                        <?php 
                            }else
                            {
                        ?>
                        <form role="form" id="searchBackup" action="<?php echo base_url() ?>backup-report" method="get" role="form">
                        <?php } ?>
                            <td class="date_search">
                                <div class="form-group">
                                    <div class="date">
                                        <input type="text" class="form-control datepicker" id="datepicker" name="fromDate" 
                                        value="<?php
                                            if(isset($_GET['fromDate']))
                                            { 
                                                if(!($_GET['fromDate']) == NULL)
                                                {
                                                        echo $_GET['fromDate'];
                                                }
                                            }
                                        ?>"
                                        >
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </td>
                            <td class="date_search">
                                <div class="form-group">
                                    <div class="date">
                                        <input type="text" class="form-control datepicker" id="datepicker" name="toDate" 
                                        value="<?php
                                            if(isset($_GET['toDate']))
                                            { 
                                                if(!($_GET['toDate']) == NULL)
                                                {
                                                        echo $_GET['toDate'];
                                                }
                                            }
                                        ?>"
                                        >
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </td>
                            <td>
                                <select class="form-control " id="client" name="client" onchange='this.form.submit()'> 
                                    <option value="">Select Client</option>
                                    <?php
                                        if(!empty($clients))
                                        {
                                            foreach ($clients as $cl)
                                            { 
                                    ?>
                                    <option value="<?php echo $cl['id'] ?>"
                                    <?php
                                        if(isset($_GET['client'])){ 
                                            if(!($_GET['client']) == NULL)
                                            {
                                                    if($_GET['client']== $cl['id'])
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
                                <select class="form-control " id="server" name="server" onchange='this.form.submit()'> 
                                    <option value="">Select server</option>
                                    <?php
                                        if(!empty($servers))
                                        {
                                            foreach ($servers as $br)
                                            { 
                                    ?>
                                    <option value="<?php echo $br['id'] ?>"
                                    <?php
                                        if(isset($_GET['server']))
                                        { 
                                            if(!($_GET['server']) == NULL)
                                            {
                                                    if($_GET['server']==$br['id'])
                                                    {
                                                        echo "selected";
                                                    } 
                                            }
                                        }
                                    ?>
                                    ><?php echo $br['name'] ?></option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control " id="user" name="user" onchange='this.form.submit()' > 
                                    <option value="">Select User</option>
                                    <?php
                                        if(!empty($users))
                                        {
                                            foreach ($users as $us)
                                            { 
                                    ?>
                                    <option value="<?php echo $us->userId ?>"
                                    <?php
                                        if(isset($_GET['user']))
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
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control " id="status" name="status" onchange='this.form.submit()' > 
                                    <option value="">Select Status</option>
                                    <option value="1"
                                    <?php
                                    if(isset($_GET['status'])){ 
                                        if($_GET['status'] == 1)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Pending</option>
                                    <option value="2"
                                    <?php
                                    if(isset($_GET['status'])){ 
                                        if($_GET['status'] == 2)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Inprogress</option>
                                    <option value="3"
                                    <?php
                                    if(isset($_GET['status'])){ 
                                        if($_GET['status'] == 3)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Completed</option>
                                    <option value="4"
                                    <?php
                                    if(isset($_GET['status'])){ 
                                        if($_GET['status'] == 4)
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Failed</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control " id="scheduleType" name="scheduleType" onchange='this.form.submit()'> 
                                    <option value="">Select Type</option>
                                    <option value="Daily"
                                    <?php
                                    if(isset($_GET['scheduleType'])){ 
                                        if($_GET['scheduleType'] == "Daily")
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Daily</option>
                                    <option value="Weekly"
                                    <?php
                                    if(isset($_GET['scheduleType'])){ 
                                        if($_GET['scheduleType'] == "Weekly")
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Weekly</option>
                                    <option value="Monthly"
                                    <?php
                                    if(isset($_GET['scheduleType'])){ 
                                        if($_GET['scheduleType'] == "Monthly")
                                        {
                                            echo "selected";
                                        }
                                    }
                                    ?>
                                    >Monthly</option>
                                </select>
                            </td>
                            <td> 
                                <input type="submit" class="btn btn-primary" name='search_BackupSchedule' value="Search" />
                            </td>
                            </form>
                        </tr>
                   
                        <?php
                        if(!empty($scheduleRecords))
                        {
                            foreach($scheduleRecords as $record)
                            { 
                        ?>
                        <tr 
                            <?php
                                if($record->ScheduleStatus == "Pending")
                                { 
                            ?> class="danger"  
                            <?php 
                                }
                                elseif($record->ScheduleStatus == "Inprogress")
                                {
                            ?> class="warning"  
                            <?php 
                                }
                                elseif($record->ScheduleStatus == "Completed")
                                {
                            ?> class="success"  
                            <?php       
                                }
                                elseif($record->ScheduleStatus == "Failed")
                                {
                            ?> class="info"  
                            <?php       
                                }
                            ?>
                        >
                            <td colspan="2" align="center"><?php echo $record->date ?></td>
                            <td><?php echo $record->ClientName ?></td>
                            <td><?php echo $record->ServerName ?></td>
                            <td><?php echo $record->UserName ?></td>
                            <td><?php echo $record->ScheduleStatus ?></td>
                            <td><?php echo $record->ScheduleType." | ".$record->Day ?></td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-detail" href="<?php echo base_url().'schedule-details/'.$record->id; ?>"><i class="fa fa-search-plus"></i></a>
                            </td> 
                        </tr>
                        <?php
                            }
                            if($role_slug=="sys.admin" || $role_slug=="master.admin"){ 
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
                        $cur_url = current_url();
                        $url_all = base_url().'backups-report';

                        if(!($cur_url == $url_all)){
                    ?>
                    <a href="<?php echo base_url(); ?>backups-report" >All</a>
                    <?php 
                        }
                        if($cur_url == $url_all){
                    ?>
                    <a href="<?php echo base_url(); ?>backup-report" >Back</a>
                    <?php }
                         echo $this->pagination->create_links();
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
            $("#scheduleTimings").html("<option value='Sunday'>Sun</option>"+
            "<option value='Monday'>Mon</option>"+
            "<option value='Tuesday'>Tue</option>"+
            "<option value='Wednesday'>Wed</option>"+
            "<option value='Thursday'>Thur</option>"+
            "<option value='Friday'>Fri</option>"+
            "<option value='Saturday'>Sat</option>");
         
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
  $(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() - 3;
    var day = dtToday.getDate() ;
    var year = dtToday.getFullYear();
    
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;
   
    $('#fromDate').attr('max', maxDate);
    $('#toDate').attr('max', maxDate);
    
});
</script>
