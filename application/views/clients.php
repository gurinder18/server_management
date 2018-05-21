<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user"></i> Client Management
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>add-client"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Clients List</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th><input type="checkbox" id="delete_all" /></th>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th>City</th>
                      <th>State</th>
                      <th>Zip</th>
                      <th>Status</th>
                      <th class="text-center">Actions</th>
                    </tr>
                    <form role="form" id="deleteServer" action="<?php echo base_url() ?>deleteClient" method="post" role="form">
                    
                    <?php
                    if(!empty($clientRecords))
                    {
                        foreach($clientRecords as $record)
                        { 
                    ?>
                    <tr <?php if($record->status==1){ echo "class='success'"; }else{ echo "class='danger'"; } ?>>
                      <td><input type="checkbox" class="delete_client" value="<?php echo$record->id; ?>" name="delete_clients[]"/></td>
                      <td><?php if(empty($record->name)){ echo "N/A";}else{echo $record->name;} ?></td>
                      <td><?php if(empty($record->phone)){ echo "N/A";}else{echo $record->phone;} ?></td>
                      <td><?php if(empty($record->email)){ echo "N/A";}else{echo $record->email;} ?></td>
                      <td><?php if(empty($record->address)){ echo "N/A";}else{echo $record->address;} ?></td>
                      <td><?php if(empty($record->city)){ echo "N/A";}else{echo $record->city;} ?></td>
                      <td><?php if(empty($record->state)){ echo "N/A";}else{echo $record->state;} ?></td>
                      <td><?php if(empty($record->zip)){ echo "N/A";}else{echo $record->zip;} ?></td>
                      <td><?php if(($record->status)==1){ echo "Active"; }else{ echo "Deactive"; } ?></td>
                      <td class="text-center">
                          <button type="button" class="btn  btn-sm btn-detail" data-toggle="modal" data-target="#exampleModal<?php echo $record->id; ?>"><i class="fa fa-search-plus"></i></button>
                             <!-- Modal -->
                  <div class="modal fade" id="exampleModal<?php echo $record->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"> 
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h2 class="modal-title" id="exampleModalLabel">Other Contacts</h2>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-hover">
                                            <?php
                                                        if(!empty($record->contacts))
                                                            {                 
                                            ?>
                                            <tr>
                                                <td><?php echo $record->contacts ?></td>
                                            </tr>
                                            <?php }else{ ?>
                                            <tr><td>No Contact added</td></tr>
                                            <?php }
                                             ?>
                                         </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                  </div>
                          <a class="btn btn-sm btn-info" href="<?php echo base_url().'edit-client/'.$record->id; ?>"><i class="fa fa-pencil"></i></a>
                          <a class="btn btn-sm btn-danger deleteClient" href="#" data-id="<?php echo $record->id; ?>"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    
                    <?php
                       }
                    ?>
                     <tr>
                        <td colspan="11"><input type="submit" class="btn btn-sm btn-danger" onClick="confirm('Do you want to delete this?');" name="delete_client" value="Delete"/></td>
                    </tr>
                    </form>
                    <?php
                    }
                    else{
                        echo "<tr><td colspan='11' style='color:red'>No Record Found</td></tr>";
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
            jQuery("#searchList").attr("action", baseURL + "clients/" + value);
            jQuery("#searchList").submit();
        });
    });
    $(document).ready(function () {
    $("#delete_all").click(function () {
        $(".delete_client").prop('checked', $(this).prop('checked'));
    });
    
    $(".delete_client").change(function(){
        if (!$(this).prop("checked")){
            $("#delete_all").prop("checked",false);
        }
    });
});
</script>

