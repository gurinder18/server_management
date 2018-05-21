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
    </section>
</div> 