<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user"></i> Client Management
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
                        <h3 class="box-title">Enter Client Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="addClient" action="<?php echo base_url() ?>add-client" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="name">Name *</label>
                                        <input type="text" class="form-control required" id="name" name="name" value="<?php echo isset($_POST["name"]) ? $_POST["name"] : ''; ?>" maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" class="form-control digits" id="phone"  name="phone" value="<?php echo isset($_POST["phone"]) ? $_POST["phone"] : ''; ?>" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address </label>
                                        <input type="text" class="form-control email" id="email"  name="email" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : ''; ?>" maxlength="100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control " id="address"  name="address" value="<?php echo isset($_POST["address"]) ? $_POST["address"] : ''; ?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City </label>
                                        <input type="text" class="form-control " id="city" name="city" value="<?php echo isset($_POST["city"]) ? $_POST["city"] : ''; ?>" maxlength="100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">State </label>
                                        <input type="text" class="form-control " id="state" name="state" value="<?php echo isset($_POST["state"]) ? $_POST["state"] : ''; ?>" maxlength="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">Country </label>
                                        <input type="text" class="form-control " id="country" name="country" value="<?php echo isset($_POST["country"]) ? $_POST["country"] : ''; ?>" maxlength="100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="zip">Zip </label>
                                        <input type="text" class="form-control " id="zip" name="zip" value="<?php echo isset($_POST["zip"]) ? $_POST["zip"] : ''; ?>" maxlength="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status </label>
                                        <select class="form-control " id="status" name="status">
                                            <option value="">Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Deactive</option>
                                        </select>
                                    </div>
                                </div>   
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="organisation">Organisation </label>
                                        <input type="text" class="form-control " id="organisation" name="organisation" value="<?php echo isset($_POST["organisation"]) ? $_POST["organisation"] : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contacts">Other Contacts </label>
                                        <input type="text" class="form-control " id="contacts" name="contacts" value="<?php echo isset($_POST["contacts"]) ? $_POST["contacts"] : ''; ?>" placeholder="Name-Contact No.-Organisation">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <div> 
                                            <label for="user">Users </label>(Click in box to select)
                                        </div>
                                            <select class="multipleSelect form-control " multiple name="user[]" id="user">
                                                <?php
                                                if(!empty($users))
                                                {
                                                    foreach ($users as $us)
                                                    { 
                                                        ?>
                                                        <option value="<?php echo $us->userId ?>"><?php echo $us->name ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select> 
                                    </div>
                                </div>    
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" name="add_client" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
        </div>    
    </section>
    
</div>
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>

<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://rawgit.com/dbrekalo/attire/master/dist/css/build.min.css">
<script src="https://rawgit.com/dbrekalo/attire/master/dist/js/build.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/fastselect.min.css">
<script src="<?php echo base_url() ?>assets/dist/js/fastselect.standalone.js"></script>
<script>
    $('#user').fastselect();
</script>