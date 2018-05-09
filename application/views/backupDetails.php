<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-arrow-circle-up"></i> Backups Management
      </h1>
    </section>
    <section class="content">
      <div class="row">
         <div class="col-xs-8">
         <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title ">Server Details</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table class="table table-hover ">
                  <?php
                    if(!empty($servers))
                    {
                        foreach($servers as $record)
                        {  
                    ?>
                   
                    <tr>
                      <th>Server Name</th> <td><?php echo $record->name ?></td>
                    </tr>
                    <tr>
                      <th>Server IP</th> <td><?php echo $record->server ?></td>
                      </tr>
                    <tr>
                      <th>Hostname</th><td><?php echo $record->hostname ?></td>
                      </tr>
                    <tr>
                    <?php if($role==1){ ?>
                      <th>Username</th><td><?php if($record->username ==""){ echo "N/A";}else{echo $record->username; } ?></td>
                      </tr>
                    <tr>
                      <th>Password</th><td><?php if($record->password ==""){ echo "N/A";}else{ echo $record->password; } ?></td>
                      </tr>
                    <?php } ?>
                    <tr>
                      <th>Details</th> 
                      <td>
                        <div style="display:block;" id="server_detail_less"> 
                          <?php 
                            $str_length = strlen($record->details);
                            if($str_length>30)
                            {
                              $details = $record->details;
                              echo substr($details,0,30),"..." ; 
                          ?>
                        </div>
                        <div id="server_detail_more" style="display:none;">
                          <?php echo $record->details;   ?>
                        </div>
                          <button id="toggle_server">Read More</button>
                          <?php
                             }
                             else
                             {
                               echo$record->details;
                             }
                          ?>
                      </td>
                    </tr>
                    <?php
                         }
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
                    <h3 class="box-title">Backup Details</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table class="table table-hover">
                  <?php
                    if(!empty($backupInfo))
                    {
                      
                    ?>
                    <tr>
                      <th>User</th> <td><?php echo $backupInfo['UserName'] ?></td>
                    </tr>
                    <tr>
                      <th>Client</th> <td><?php echo $backupInfo['ClientName'] ?></td>
                      </tr>
                    <tr>
                      <th>Server</th> <td><?php echo $backupInfo['ServerName'] ?></td>
                      </tr>
                      <tr>
                      <th>Schedule Type</th> <td><?php echo $backupInfo['scheduleType'] ?></td>
                      </tr>
                    <tr>
                      <th>Schedule Timings</th> <td><?php echo $backupInfo['scheduleTimings'] ?></td>
                    </tr>
                    <tr>
                      <th>Details</th> 
                      <td>
                        <div style="display:block;" id="backup_detail_less"> 
                          <?php 
                          if($backupInfo['information'] != null)
                          {
                            $str_length = strlen($backupInfo['information']);
                            if($str_length>30)
                            {
                              $details = $backupInfo['information'];
                              echo substr($details,0,30),"..." ; 
                          ?>
                        </div>
                        <div id="backup_detail_more" style="display:none;">
                          <?php echo $backupInfo['information'];   ?>
                        </div>
                          <button id="toggle_backup">Read More</button>
                          <?php
                             }
                             else
                             {
                               echo $backupInfo['information'];
                             }
                            }else
                            {
                              echo "No Details";
                            }
                          ?>
                      </td>
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
                <h3 class="box-title">Client</h3>
                  <img class="profile-user-img img-responsive img-circle " src="<?php echo base_url(); ?>/assets/dist/img/no_image.png" alt="User profile picture">
                  <?php
                      if(!empty($clients))
                      {
                        foreach($clients as $cl)
                        { }
                  ?>
                  <p class="text-muted text-center"><h4><?php echo $cl->name ?></h4></p>
                         <!-- Button trigger modal -->
                         <?php if($role_slug=="sys.admin"){ ?>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            View Details
                        </button>
                         <?php } ?>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"> 
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h2 class="modal-title" id="exampleModalLabel">Client details</h2>
                                       
                                    </div>
                                    <div class="modal-body">
                                    <table class="table table-hover">
                                      <tr>
                                         <th>Name</th><td><?php echo $cl->name ?></td>
                                      </tr>
                                      <tr>
                                         <th>Contact No.</th><td><?php echo $cl->phone ?></td>
                                      </tr>
                                      <tr>
                                         <th>Email</th> <td><?php echo $cl->email ?></td>
                                      </tr>
                                      <tr>
                                        <th>Address</th><td><?php echo $cl->address ?></td>
                                      </tr>
                                      <tr>
                                        <th>City</th><td><?php echo $cl->city ?></td>
                                      </tr>
                                      <tr>
                                         <th>State</th><td><?php echo $cl->state ?></td>
                                      </tr>
                                      <tr>
                                        <th>Zip</th><td><?php echo $cl->zip ?></td>
                                      </tr>
                                      <tr>
                                       <th>Status</th> <td><?php if($cl->status==1){ echo "Active"; }else{ echo "Deactive"; } ?></td>
                                      </tr>
                                      <?php }else{ ?>
                                      <tr><td>Client does not exist</td></tr>
                                      <?php } ?>
                                  </table>
                                    </div>
                                    <?php if($role_slug=="sys.admin" && !empty($clients)){  ?>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                    <?php } ?>
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

  $(document).ready(function() {
    $("#toggle_server").click(function() {
      var elem = $("#toggle_server").text();
      if (elem == "Read More") {
        //Stuff to do when btn is in the read more state
        $("#toggle_server").text("Read Less");
        $("#server_detail_less").hide();
        $("#server_detail_more").slideDown();
      } else {
        //Stuff to do when btn is in the read less state
        $("#toggle_server").text("Read More");
        $("#server_detail_less").show();
        $("#server_detail_more").slideUp();
      }
    });
  });
  $(document).ready(function() {
    $("#toggle_backup").click(function() {
      var elem = $("#toggle_backup").text();
      if (elem == "Read More") {
        //Stuff to do when btn is in the read more state
        $("#toggle_backup").text("Read Less");
        $("#backup_detail_less").hide();
        $("#backup_detail_more").slideDown();
      } else {
        //Stuff to do when btn is in the read less state
        $("#toggle_backup").text("Read More");
        $("#backup_detail_less").show();
        $("#backup_detail_more").slideUp();
      }
    });
  });
</script>

