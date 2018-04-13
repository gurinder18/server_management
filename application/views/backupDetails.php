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
                    <tr>
                      <th>Name</th>
                      <th>Server IP</th>
                      <th>Hostname</th>
                      <th>Username</th>
                      <th>Password</th>
                      <th>Details</th>
                    </tr>
                    <?php
                    if(!empty($servers))
                    {
                        foreach($servers as $record)
                        { 
                    ?>
                    <tr>
                      <td><?php echo $record->name ?></td>
                      <td><?php echo $record->server ?></td>
                      <td><?php echo $record->hostname ?></td>
                      <td><?php echo $record->username ?></td>
                      <td><?php echo $record->password ?></td>
                      <td><?php echo $record->details ?></td>
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
                    <tr>
                      <th>User</th>
                      <th>Client</th>
                      <th>Server</th>
                      <th>Schedule Type</th>
                      <th>Schedule Timings</th>
                    </tr>
                    <?php
                    if(!empty($backupInfo))
                    {
                        foreach($backupInfo as $backup)
                        { 
                    ?>
                    <tr>
                      <td><?php echo $backup->UserName ?></td>
                      <td><?php echo $backup->ClientName ?></td>
                      <td><?php echo $backup->ServerName ?></td>
                      <td><?php echo $backup->scheduleType ?></td>
                      <td><?php echo $backup->scheduleTimings ?></td>
                    </tr>
                    <?php
                        }
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
                    <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Status</th>
                    </tr>
                    <?php
                    if(!empty($clients))
                    {
                        foreach($clients as $cl)
                        { 
                    ?>
                    <tr>
                    <td><?php echo $cl->name ?></td>
                    <td><?php echo $cl->phone ?></td>
                    <td><?php echo $cl->email ?></td>
                    <td><?php echo $cl->address ?></td>
                    <td><?php echo $cl->city ?></td>
                    <td><?php echo $cl->state ?></td>
                    <td><?php echo $cl->zip ?></td>
                    <td><?php if($cl->status==1){ echo "Active"; }else{ echo "Deactive"; } ?></td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
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
