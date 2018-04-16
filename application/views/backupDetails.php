<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-arrow-circle-up"></i> Backups Management
      </h1>
    </section>
    <section class="content">
        
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Server Details</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
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
                      <th>Username</th><td><?php echo $record->username ?></td>
                      </tr>
                    <tr>
                      <th>Password</th><td><?php echo $record->password ?></td>
                      </tr>
                    <tr>
                      <th>Details</th> <td><?php echo $record->details ?></td>
                    </tr>
                   
                    <?php
                        }
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
                <div class="box-body table-responsive no-padding">
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
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Client Details</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                  <?php
                    if(!empty($clients))
                    {
                        foreach($clients as $cl)
                        { 
                        }
                    }
                    ?>
                    <tr>
                    <th>Client Name</th> <td><?php echo $cl->name ?></td>
                    </tr>
                      <tr>
                    <th>Phone Number</th><td><?php echo $cl->phone ?></td>
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
                  </table>
                  
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
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
