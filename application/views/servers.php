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
                <?php if($role_slug=="sys.admin"){ ?>
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>add-server"><i class="fa fa-plus"></i> Add New</a>
                <?php } ?>
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
                      <th> <?php if($role_slug=="sys.admin"){ ?><input type="checkbox" id="delete_all" /><?php } ?></th>
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
                        <td>
                            <select class="form-control required" id="name" name="name" > 
                                <option value="">Select Server</option>
                                <?php
                                   foreach($servers as $ser)
                                    {
                                ?>
                                <option value="<?php echo $ser->name ?>" 
                                <?php
                                if(isset($_GET['search_server'])=='Submit')
                                { 
                                    if(!($_GET['name']) == NULL)
                                    {
                                            if($_GET['name']==$ser->name)
                                            {
                                                echo "selected";
                                            } 
                                    }
                                }
                                ?>><?php echo $ser->name; ?></option>
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
                                <option value="<?php echo $cl->id ?>"
                                <?php
                                    if(isset($_GET['search_server'])=='Submit'){ 
                                        if(!($_GET['client']) == NULL)
                                        {
                                                if($_GET['client']==$cl->id)
                                                {
                                                    echo "selected";
                                                } 
                                        }
                                    }
                                ?>
                                 ><?php echo $cl->name ?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control required" id="server" name="server" maxlength="128" placeholder="Search Server IP"
                            value="<?php
                                if(isset($_GET['search_server'])=='Submit'){ 
                                    if(!($_GET['server']) == NULL)
                                    {
                                            echo $_GET['server'];
                                    }
                                }
                                ?>"
                            >
                        </td>
                        <td>
                            <input type="text" class="form-control required" id="hostname" name="hostname" maxlength="128" placeholder="Search Hostname"
                            value="<?php
                                if(isset($_GET['search_server'])=='Submit'){ 
                                    if(!($_GET['hostname']) == NULL)
                                    {
                                            echo $_GET['hostname'];
                                    }
                                }
                                ?>"
                            >
                        </td>
                        <td>
                            <select class="form-control required" id="status" name="status" > 
                                <option value="">Select Status</option>
                                <option value="1" 
                                <?php
                                if(isset($_GET['search_server'])=='Submit'){ 
                                    if($_GET['status'] == '1')
                                    {
                                        echo "selected";
                                    }
                                }
                                ?>
                                >Active</option>
                                <option value="0" 
                                <?php
                                if(isset($_GET['search_server'])=='Submit'){ 
                                    if($_GET['status'] == '0')
                                    {
                                        echo "selected";
                                    }
                                }
                                ?>
                                 >Deactive</option>
                            </select>
                        </td>
                        <?php  ?>
                        <td> 
                            <input type="submit" class="btn btn-primary" name='search_server' value="Submit" />
                        </td>
                        </form>
                    </tr>
                    <form role="form" id="deleteServer" action="<?php echo base_url() ?>deleteServer" method="post" role="form">
                    <?php
                    if(!empty($serverRecords))
                    {
                        foreach($serverRecords as $record)
                        { 
                    ?>
                    <tr>
                      <td><?php if($role_slug=="sys.admin"){ ?><input class="delete_server" type="checkbox" value="<?php echo $record->id; ?>" name="delete_servers[]"/><?php } ?></td>
                      <td><?php echo $record->name ?></td>
                      <td><?php echo $record->ClientName ?></td>
                      <td><?php echo $record->server ?></td>
                      <td><?php echo $record->hostname ?></td>
                      <td><?php if($record->status==1){ echo "Active"; }else{ echo "Deactive"; } ?></td>
                      <td class="text-center">
                        <?php if($role_slug=="sys.admin"){ ?>
                          <a class="btn btn-sm btn-info" href="<?php echo base_url().'edit-server/'.$record->id; ?>"><i class="fa fa-pencil"></i></a>
                          <a class="btn btn-sm btn-danger deleteServer" href="#" data-id="<?php echo $record->id; ?>"><i class="fa fa-trash"></i></a>
                        <?php } ?>
                      </td>
                    </tr> 
                    <?php } ?>
                    <tr>
                    <?php if($role_slug=="sys.admin"){ ?>
                        <td colspan='8'><input type="submit" class="btn btn-sm btn-danger " name="delete_server" value="Delete"/></td>
                    <?php } ?>
                    </tr>
                    </form>
                    <?php
                    }
                    else{
                        echo "<tr><td colspan='8' style='color:red'>No Record Found</td></tr>";
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
    $(document).ready(function () {
    $("#delete_all").click(function () {
        $(".delete_server").prop('checked', $(this).prop('checked'));
    });
    
    $(".delete_server").change(function(){
        if (!$(this).prop("checked")){
            $("#delete_all").prop("checked",false);
        }
    });
});
</script>
