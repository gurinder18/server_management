<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
        <small>Control panel</small>
      </h1>
    </section>
    
    <section class="content">
        <div class="row">
        <?php if($role_slug=="sys.admin" || $role_slug=="master.admin"){ ?>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo $todayBackupCount; ?></h3>
                  <p>Todays Backups</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <?php 
                    $fromDate =  date('m/d/Y');
                    $toDate =  date('m/d/Y',strtotime("+1 days"));
                ?>
                <a href="<?php echo base_url() ?>backups-report?backups=today&fromDate=<?php echo $fromDate ?>&toDate=<?php echo $toDate ?>&client=&server=&user=&status=&scheduleType=&search_BackupSchedule=Search" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo $pendingBackupCount; ?></h3>
                  <p>Today's Pending Backups</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="<?php echo base_url(); ?>backups-report?backups=today&fromDate=<?php echo $fromDate ?>&toDate=<?php echo $toDate ?>&client=&server=&user=&status=1&scheduleType=&search_BackupSchedule=Search" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
           
         <?php } ?>
            <?php if($role_slug=="member"){ ?>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">

                <div class="inner">
                  <h3><?php echo $pendingBackupCount; ?></h3>
                  <p>Pending backups</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="<?php echo base_url(); ?>schedules?server=&serverIP=&hostname=&client=&status=1&search_backup=Search" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><?php } ?><!-- ./col -->
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Monthly User's Backup-Status</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive ">
                  <form  method="get">
                    <div class="box-body">
                      <div class="row">
                      <?php if($role_slug=="sys.admin" || $role_slug == "master.admin"){ ?>
                        <div class="col-md-3">                                
                          <div class="form-group">
                            <label for="name">User</label>
                            <select class="form-control" id="user" name="user" onchange="drawChart()" > 
                              <option value="">Select User</option>
                              <?php
                                if(!empty($users))
                                {
                                  foreach ($users as $user)
                                  { 
                              ?>
                              <option value="<?php echo $user->userId ?>"><?php  echo $user->name ?></option>
                              <?php
                                  }
                                }
                              ?>
                              </select>
                          </div>
                        </div>
                        <?php } ?>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="status">Server</label>
                            <select class="form-control" id="server" name="server" onchange="drawChart()" > 
                              <option value="">Select Server</option>
                              <?php
                                if(!empty($servers))
                                {
                                  foreach ($servers as $server)
                                  { 
                              ?>
                              <option value="<?php echo $server->id ?>"><?php  echo $server->name ?></option>
                              <?php
                                  }
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="status">Month</label>
                            <select class="form-control" id="month" name="month" onchange="drawChart()" > 
                              <option value="">Select month</option>
                              <?php
                                for($i = 01;$i <= 12; $i++)
                                {
                              ?>
                              <option value="<?php echo $i; ?>"><?php  echo $i ?></option>
                              <?php
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  <div id="chart_div" style="width: 900px; height: 500px;"></div> 
                  <table class="table table-hover">
                  </table>
                </div>
              </div><!-- /.box -->
            </div>
          </div>
    </section>
</div> 
  <!--Load the AJAX API--> 
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/charts/loader.js"></script> 
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
 
    <script type="text/javascript"> 
    
    // Load the Visualization API and the piechart package. 
    google.charts.load('current', {'packages':['corechart']}); 
       
    // Set a callback to run when the Google Visualization API is loaded. 
    google.charts.setOnLoadCallback(drawChart); 
       
    function drawChart() { 
     
      var jsonData = $.ajax({ 
          url: "<?php echo base_url() ?>dashboard/getdata", 
          dataType: "json", 
          async: false 
          }).responseText; 
           
      // Create our data table out of JSON data loaded from server. 
      var data = new google.visualization.DataTable(jsonData); 
 
      var options = {
          title: "Monthly User's Backup-Status",
          slices: {
            0: { color: 'red' },
            1: { color: 'yellow' },
            2: { color: 'green' },
            3: { color: 'brown' }
          }
        };
      // Instantiate and draw our chart, passing in some options. 
      var chart = new google.visualization.PieChart(document.getElementById('chart_div')); 
      chart.draw(data,options ); 
    } 
 
    </script> 
    
    <script type="text/javascript"> 
      function drawChart() { 
        var userId = $("#user option:selected").val();
        var UserName = $("#user option:selected").text();
        var serverId = $("#server option:selected").val();
        var ServerName = $("#server option:selected").text();
        var months = $("#month ").val();
        if(userId == "")
        {
          userId = null;
        }
        if(serverId == "" )
        {
          serverId = null;
        }
        if( months == "")
        {
          months = null;
        } 
       var jsonData = $.ajax({ 
           type: "Get",
           url: "<?php echo base_url() ?>dashboard/getdata2",
           data:  { user: userId, server: serverId, month: months },
           dataType: "json", 
           async: false 
           }).responseText; 
          
       // Create our data table out of JSON data loaded from server. 
       var data = new google.visualization.DataTable(jsonData);
      
       <?php
       if($role_slug != "member")
       { 
        ?>
       if(userId != null)
        {
          if(ServerName == "Select Server")
          {
            ServerName = "All";
          }
          if(months == null)
          {
            months = "Current";
          }
          var options = {
           title: "Backup-Status of User: '"+UserName + "' for Server: '"+ServerName+"' of "+months+" month",
           slices: {
             0: { color: 'red' },
             1: { color: 'yellow' },
             2: { color: 'green' },
             3: { color: 'brown' }
           }
         };
        }
        else if(serverId != null )
        {
          if(UserName == "Select User")
          {
            UserName = "All";
          }
          if(months == null)
          {
            months = "Current";
          }
          var options = {
           title: " Backup-Status of User: '"+UserName + "' for Server: '"+ServerName+"' of "+months+" month",
           slices: {
             0: { color: 'red' },
             1: { color: 'yellow' },
             2: { color: 'green' },
             3: { color: 'brown' }
           }
         };
        }
        else if( months != null)
        {
          if(UserName == "Select User")
          {
            UserName = "All";
          }
          if(ServerName == null)
          {
            ServerName = "All";
          }
          var options = {
           title: " Backup-Status of User: '"+UserName + "' for Server: '"+ServerName+"' of "+months+" month",
           slices: {
             0: { color: 'red' },
             1: { color: 'yellow' },
             2: { color: 'green' },
             3: { color: 'brown' }
           }
         };
        }
        else if(userId != null || userId != "" && serverId != null || serverId != "" && months != null|| months != "" )
        {
          UserName = "All";
          ServerName = "All";
          var options = {
           title: " Backup-Status of User: '"+UserName + "' for Server: '"+ServerName+"' of Current month",
           slices: {
             0: { color: 'red' },
             1: { color: 'yellow' },
             2: { color: 'green' },
             3: { color: 'brown' }
           }
         };
        }
      <?php 
       }
       else
       {
       ?>
        if(serverId != null )
        {
          if(months == null)
          {
            months = "Current";
          }
          var options = {
           title: " Backup-Status for Server: '"+ServerName+"' of "+months+" month",
           slices: {
             0: { color: 'red' },
             1: { color: 'yellow' },
             2: { color: 'green' },
             3: { color: 'brown' }
           }
         };
        }
        else if( months != null)
        {
          if(ServerName == null)
          {
            ServerName = "All";
          }
          var options = {
           title: " Backup-Status for Server: '"+ServerName+"' of "+months+" month",
           slices: {
             0: { color: 'red' },
             1: { color: 'yellow' },
             2: { color: 'green' },
             3: { color: 'brown' }
           }
          };
        }
        else if(serverId != null || serverId != "" && months != null|| months != "" )
        {
          ServerName = "All";
          var options = {
           title: " Backup-Status  for Server: '"+ServerName+"' of Current month",
           slices: {
             0: { color: 'red' },
             1: { color: 'yellow' },
             2: { color: 'green' },
             3: { color: 'brown' }
           }
         };
        }
      <?php } ?>
      
       // Instantiate and draw our chart, passing in some options. 
       var chart = new google.visualization.PieChart(document.getElementById('chart_div')); 
       chart.draw(data,options ); 
     }
     </script> 
     <?php 
      
    ?>
     