<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-list-alt"></i> Server Management
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>add-server"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Servers List</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th></th>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Client</th>
                      <th>Server IP</th>
                      <th>Hostname</th>
                      <th>Status</th>
                      <th class="text-center">Actions</th>
                    </tr>
                    <tr>
                    <form role="form" id="searchServer" action="<?php echo base_url() ?>servers" method="get" role="form">
                        <td></td>
                        <td>#</td>
                        <td>
                            <select class="form-control required" id="name" name="name" > 
                                <option value="">Select Server</option>
                                <?php
                                   foreach($serverRecords as $record)
                                    {
                                ?>
                                <option value="<?php echo $record->name ?>"><?php echo $record->name ?></option>
                                <?php
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
                                <option value="<?php echo $cl->id ?>"><?php echo $cl->name ?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control required" id="server" name="server" maxlength="128" placeholder="Search Server IP">
                        </td>
                        <td>
                            <input type="text" class="form-control required" id="hostname" name="hostname" maxlength="128" placeholder="Search Hostname">
                        </td>
                        <td>
                            <select class="form-control required" id="status" name="status" > 
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </td>
                        <td> 
                            <input type="submit" class="btn btn-primary" name='search_server' value="Submit" />
                        </td>
                        </form>
                    </tr>
                    <form role="form" id="deleteServer" action="<?php echo base_url() ?>deleteServer" method="post" role="form">
                    
                    <?php
                    if(!empty($serverRecords))
                    {
						$i = 1;
                        foreach($serverRecords as $record)
                        { 
                    ?>
                    <tr>
                      <td><input type="checkbox" value="<?php echo$record->id; ?>" name="delete_servers[]"/></td>
                      <td><?php echo $i ?></td>
                      <td><?php echo $record->name ?></td>
                      <td><?php echo $record->ClientName ?></td>
                      <td><?php echo $record->server ?></td>
                      <td><?php echo $record->hostname ?></td>
                      <td><?php if($record->status==1){ echo "Active"; }else{ echo "Deactive"; } ?></td>
                      <td class="text-center">
                          <a class="btn btn-sm btn-info" href="<?php echo base_url().'edit-server/'.$record->id; ?>"><i class="fa fa-pencil"></i></a>
                          <a class="btn btn-sm btn-danger deleteServer" href="#" data-id="<?php echo $record->id; ?>"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr> 
                    <?php
                            $i++;
                        }
                    ?>
                    <tr>
                        <td colspan='3'><input type="submit" class="btn btn-sm btn-danger " name="delete_server" value="Delete"/></td>
                    </tr>
                    </form>
                    <?php
                    }
                    else{
                        echo "<tr><td colspan='2' style='color:red'>No Record Found</td></tr>";
                    }
                    ?>
                  </table>
                  
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
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
