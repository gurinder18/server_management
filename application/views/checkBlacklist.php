<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-list-alt"></i> Check IP Blacklist
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
                        if($this->input->get("status") != "")
                        {
                            echo '<div class="alert alert-danger alert-dismissable">
                                      '.$this->input->get("status").'
                                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    </div>'; 
                        }
                        if($this->input->get("listed") != "")
                        {
                            echo '<div class="alert alert-success alert-dismissable">
                                      '.$this->input->get("listed").'
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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-plus"></i>Add new
                    </button>
                </div>
            </div>
                     <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"> 
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h2 class="modal-title" id="exampleModalLabel">Enter IP</h2>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?php echo base_url(); ?>add-ip" method="post">
                                            <input type="text" id="ip" name="ip" placeholder="Enter IP" required/>
                                            <input type="submit" name="add_ip" value="Submit"/>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Check IP</h3>
                </div><!-- /.box-header -->
                    <div class="col-xs-8 box-body table-responsive">
                        <form action="<?php echo base_url(); ?>blacklist" method="get">
                            <input type="text" value="" name="ip" placeholder="Enter IP"/>
                            <input type="submit" value="LOOKUP"/>
                        </form>
                    </div>
                
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                </div>
              </div><!-- /.box -->
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">IP List</h3>
                    
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>IP Address</th>
                      <th>Is Listed?</th>
                      <th>Action</th>
                    </tr>
                    <?php
                        if(!empty($status))
                        {
                            foreach($status as $list)
                            { 
                    ?>
                   <tr  <?php if($list['isListed'] == "Listed"){ echo "class='danger'"; }else{ echo "class='success'";} ?>>
                   
                      <td><?php echo $list['ip']; ?></td>
                      <td><?php echo $list['isListed']; ?></td>
                      <td>
                        
                        <button type="button" class="btn btn-sm btn-detail" data-toggle="modal" data-target="#detailsModal<?php echo $list['id']; ?>">
                            <i class="fa fa-search-plus"></i>
                        </button>

                         <!-- Details Modal -->
                         <div class="modal fade" id="detailsModal<?php echo $list['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"> 
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h2 class="modal-title" id="exampleModalLabel">IP: <?php echo $list['ip']; ?></h2>
                                    </div>
                                    <div class="modal-body">
                                    <table class="table table-hover">
                                      <tr>
                                         <th>Host</th>
                                         <th>isListed?</th>
                                      </tr>
                                      <?php
                                            if(!empty($blacklist))
                                            { 
                                                foreach($blacklist as  $lists => $li)
                                                {
                                                    if($list['ip'] == $li->ip) 
                                                    {
                                        ?>
                                      <tr  <?php if($li->isListed == 1){ echo "class='danger'"; }else{ echo "class='success'";} ?>>
                                         <td><?php echo $li->host; ?></td>
                                         <td><?php if($li->isListed == 1){ echo "Listed"; }else{ echo "Not Listed";} ?></td>
                                      </tr>
                                      <?php 
                                                    }
                                                }
                                            }
                                            else{
                                      ?>
                                      <tr><td>No Record Found</td></tr>
                                      <?php } ?>
                                  </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                       <?php if($list['serverName'] == NULL){ ?>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal<?php echo $list['id']; ?>">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <a class="btn btn-sm btn-danger deleteIP" href="#" data-id="<?php echo  $list['id']; ?>"><i class="fa fa-trash"></i></a>
                       <?php } ?>
                       
                      </td>
                       <!-- Edit IP Modal -->
                       <div class="modal fade" id="editModal<?php echo $list['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"> 
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h2 class="modal-title" id="exampleModalLabel">Edit IP</h2>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?php echo base_url(); ?>edit-ip/<?php echo $list['id']; ?>" method="post">
                                            <input type="hidden" id="id" name="id" value="<?php echo $list['id']; ?>" required/>
                                            <input type="text" id="ip" name="ip" placeholder="Enter IP" value="<?php echo $list['ip']; ?>" required/>
                                            <input type="submit" name="edit_ip" value="Edit"/>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                       </tr>
                    <?php
                    }}
                    else{
                        echo "<tr><td colspan='11' style='color:red'>No Record Found</td></tr>";
                    }
                    ?>
                    
                  </table>
                 
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php //echo $this->pagination->create_links(); ?>
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
