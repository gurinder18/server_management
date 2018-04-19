<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-calendar-check-o"></i>Schedules Management
      </h1>
    </section>
    <section class="content">
      <div class="row">
         <div class="col-xs-8">
         <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title ">Servers Details</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table class="table table-hover ">
                  <?php if(!empty($scheduleInfo)){ ?>
                    <tr>
                      <th>Server Name</th> <td><?php echo $scheduleInfo['ServerName'] ?></td>
                    </tr>
                    <tr>
                      <th>Server IP</th> <td><?php echo $scheduleInfo['ServerIP'] ?></td>
                      </tr>
                    <tr>
                      <th>Hostname</th><td><?php echo $scheduleInfo['ServerHostname'] ?></td>
                    </tr>
                    <tr>
                      <th>Details</th> 
                      <td>
                        <?php 
                          $str_length = strlen($scheduleInfo['ServerDetails']);
                          if($str_length>30){
                          if(isset($_POST['read_more'])!='Read more')
                          { 
                            $details = $scheduleInfo['ServerDetails'];
                             echo substr("$details",0,30),"..." ;
                        ?>

                        <form method=post>
                           <input type="submit" class="btn btn-xs" name='read_more' value="Read more" />
                        <form>
                        <?php
                            }
                            else
                            { 
                              echo $scheduleInfo['ServerDetails']; 
                            }
                          }
                          else
                          { 
                            echo $scheduleInfo['ServerDetails']; 
                          }
                        ?>
                      </td>
                    </tr>
                    <?php
                         
                       }
                       else{
                    ?>
                    <tr><td>Server does not exist</td></tr>
                    <?php
                    }
                    ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Client Details</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table class="table table-hover">
                  <?php
                    if(!empty($scheduleInfo))
                    {
                      
                    ?>
                    <tr>
                      <th>Name</th> <td><?php echo $scheduleInfo['ClientName'] ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                </div>
              </div><!-- /.box -->
            </div>
        </div>
      
         </div>
         <div class="col-xs-4">
            <div class="box">
                <div class="box-body box-profile text-center">
                <h3 class="box-title">Schedule details</h3>
                <?php
                    if(!empty($scheduleInfo))
                    {
                ?>
                <table class="table table-hover">
                    <tr>
                        <th>Date</th><td><?php echo $scheduleInfo['date'] ?></td>
                    </tr>
                    <tr>
                        <th>Schedule type</th><td><?php echo $scheduleInfo['scheduleType'] ?></td>
                    </tr>
                    <tr>
                        <th>Status</th> <td><?php echo $scheduleInfo['ScheduleStatus'] ?></td>
                    </tr>
                </table>
                <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#scheduleStatusModal">
                        Update Status
                    </button>
                        <!-- Modal -->
                        <div class="modal fade" id="scheduleStatusModal" tabindex="-1" role="dialog" aria-labelledby="scheduleStatusModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"> 
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h2 class="modal-title" id="scheduleStatusModalLabel">Client details</h2>
                                       
                                    </div>
                                    <div class="modal-body">
                                    <form role="form" id="scheduleDetails" action="<?php echo base_url() ?>schedule-update-status/<?php echo $scheduleInfo['id'] ?>" method="post" role="form">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Backup Status</th>
                                            <input type="hidden" id="scheduleId" name="scheduleId" value="<?php echo $scheduleInfo['id'] ?>" />
                                            <td> 
                                                <select class="form-control required" id="backupStatus" name="backupStatus" >
                                                    <?php $selected = "selected";  ?>
                                                    <option value="">Select Status</option>
                                                    <option value="1" <?php if($scheduleInfo['status']==1){ echo $selected;  } ?>>Pending</option>
                                                    <option value="2" <?php if($scheduleInfo['status']==2){ echo $selected;  } ?>>Inprogress</option>
                                                    <option value="3" <?php if($scheduleInfo['status']==3){ echo $selected;  } ?>>Completed</option>
                                                    <option value="4" <?php if($scheduleInfo['status']==4){ echo $selected;  } ?>>Failed</option>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                  </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="backup_status" >Submit</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>   
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
</script>
