<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_style.min.css" rel="stylesheet" type="text/css" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-server"></i> Server Management
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
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
            
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Server Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                     
                    <form role="form" id="addServer" action="<?php echo base_url() ?>add-server" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="name">Name *</label>
                                        <input type="text" class="form-control required" id="name" name="name" maxlength="50" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client">Client *</label>
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
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="server">Server IP *</label>
                                        <input type="text" class="form-control required" id="server" name="server" maxlength="128">
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="hostname">Hostname *</label>
                                        <input type="text" class="form-control required" id="hostname" name="hostname" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control " id="username" name="username" maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control " id="password" name="password" maxlength="50" minlength="4">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status *</label>
                                        <select class="form-control required" id="status" name="status" > 
                                            <option value="">Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Deactive</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="row"> 
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="details">Other Details</label>
                                       <textarea class="form-control" id="details" name="details"></textarea>
                                    </div>
                                </div>    
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" name="add_server" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
        </div> 
        <div class="row">	
            <div class="col-md-12">
              <!-- general form elements -->
            
                <div class="box box-primary">								
                    <?php echo form_open_multipart('server/ExcelDataAdd');?>                      
                        <label>Excel File:</label>                        
                        <input type="file" name="userfile" />				                   
                        <input type="submit" value="upload" name="upload" />
                    </form>	
                </div>
            </div>
        </div>       
    </section>
    
</div>
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/froala_editor.pkgd.min.js" type="text/javascript"></script>
<script>
  $(function() {
    $('textarea').froalaEditor()
  });
</script>