<link href="<?php echo base_url(); ?>assets/dist/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/dist/css/froala_style.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/photo_slider.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-calendar-check-o"></i>Schedules Management
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-8">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title ">Servers Details</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-hover ">
                            <?php 
                                if(!empty($scheduleInfo))
                                { 
                            ?>
                            <tr>
                                <th>Client Name</th> <td><?php echo $scheduleInfo['ClientName'] ?></td>
                            </tr>
                            <tr>
                                <th>Server Name</th> <td><?php echo $scheduleInfo['ServerName'] ?></td>
                            </tr>
                            <tr>
                                <th>Server IP</th> <td><?php echo $scheduleInfo['ServerIP'] ?></td>
                            </tr>
                            <tr>
                                <th>Hostname</th><td><?php echo $scheduleInfo['ServerHostname'] ?></td>
                            </tr>
                            <tr>
                                <th>Details</th> 
                                <td>
                                <div style="display:block;" id="server_detail_less"> 
                                    <?php 
                                        $str_length = strlen($scheduleInfo['ServerDetails']);
                                        if($str_length>30)
                                        {
                                        $details = $scheduleInfo['ServerDetails'];
                                        echo substr($details,0,30),"..." ; 
                                    ?>
                                    </div>
                                    <div id="server_detail_more" style="display:none;">
                                    <?php echo $scheduleInfo['ServerDetails'];   ?>
                                    </div>
                                    <button id="toggle_server">Read More</button>
                                    <?php
                                        }
                                        else
                                        {
                                        echo $scheduleInfo['ServerDetails'];
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php
                                }
                                else{
                            ?>
                            <tr>
                                <td>Server does not exist</td>
                            </tr>
                            <?php
                                }
                            ?>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <div class="col-xs-4">
                <div class="box">
                    <div class="box-body box-profile text-center">
                        <h3 class="box-title">Schedule details</h3>
                        <?php
                            if(!empty($scheduleInfo))
                            {
                        ?>
                        <table class="table table-hover">
                            <tr>
                                <th>Date</th><td><?php echo $scheduleInfo['date'] ?></td>
                            </tr>
                            <tr>
                                <th>Schedule type</th><td><?php echo $scheduleInfo['scheduleType'] ?></td>
                            </tr>
                            <tr>
                                <th>Status</th> <td><?php echo $scheduleInfo['ScheduleStatus'] ?></td>
                            </tr>
                        </table>
                        <!-- Button trigger modal -->
                        <?php
                            if($role_slug=="member"  && $scheduleInfo['date']==date('d-m-Y') )
                            { 
                        ?>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCommentModal">
                             Update Status
                        </button>
                            <?php } ?>
                            <!-- Modal -->
                            <div class="modal fade" id="scheduleStatusModal" tabindex="-1" role="dialog" aria-labelledby="scheduleStatusModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header"> 
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h2 class="modal-title" id="scheduleStatusModalLabel">Update Backup Status</h2>
                                        
                                        </div>     
                                           <div class="modal-body">
                                           <form role="form" id="scheduleDetails" action="<?php echo base_url() ?>schedule-update-status/<?php echo $scheduleInfo['id']?>" method="post" role="form">
                                                <table class="table table-hover">
                                                    <tr>
                                                        <th>Backup Status</th>
                                                            <input type="hidden" id="status_scheduleId" name="status_scheduleId" value="<?php echo $scheduleInfo['id'] ?>" />
                                                        <td> 
                                                            <select class="form-control required" id="backupStatus" name="backupStatus" required>
                                                            <?php $selected = "selected";  ?>
                                                            <option value="">Select Status</option>
                                                            <option value="1" <?php if($scheduleInfo['status']==1){ echo $selected;  } ?>>Pending</option>
                                                            <option value="2" <?php if($scheduleInfo['status']==2){ echo $selected;  } ?>>Inprogress</option>
                                                            <option value="3" <?php if($scheduleInfo['status']==3){ echo $selected;  } ?>>Completed</option>
                                                            <option value="4" <?php if($scheduleInfo['status']==4){ echo $selected;  } ?>>Failed</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                               <input type="submit" class="btn btn-primary" id="backup_status" name="backup_status" value="Submit">
                                                </form> <?php } ?>
                                            </div> 
                                    </div>
                                </div>
                            </div>   
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
       
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                <?php
                    if($role_slug=="member" && $scheduleInfo['date']==date('d-m-Y'))
                    { 
                ?>
                
                <?php } ?>
                </div>
            </div>
        </div>
            <!-- Add Comment Modal -->
            <div class="modal fade" id="addCommentModal" tabindex="-1" role="dialog" aria-labelledby="addCommentLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"> 
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                                <h2 class="modal-title" id="addCommentModalLabel">Add comment</h2>
                        </div>
                        <div class="modal-body">
                            <table class="table table-hover" id="add_comment"> 
                                <tr>
                                    <form id="addComment" action="<?php echo base_url() ?>add-comment" method="post" enctype="multipart/form-data" role="form">
                                    <th>Backup Status</th>
                                    <input type="hidden" id="scheduleId" name="scheduleId" value="<?php echo $scheduleInfo['id'] ?>" />
                                    <input type="hidden" id="userId" name="userId" value="<?php echo $scheduleInfo['userId'] ?>" />
                                    
                                    <td> 
                                        <select class="form-control required" id="statusId" name="statusId" >
                                            <?php $selected = "selected";  ?>
                                            <option value="">Select Status</option>
                                            <option value="1" <?php if($scheduleInfo['status']==1){ echo $selected;  } ?>>Pending</option>
                                            <option value="2" <?php if($scheduleInfo['status']==2){ echo $selected;  } ?>>Inprogress</option>
                                            <option value="3" <?php if($scheduleInfo['status']==3){ echo $selected;  } ?>>Completed</option>
                                            <option value="4" <?php if($scheduleInfo['status']==4){ echo $selected;  } ?>>Failed</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Attachment</th>
                                    <td>
                                        <input type="file" class="form-control" id="attachment"  name="attachment[]" multiple="multiple">
                                   </td>
                                </tr>
                                <tr>
                                    <th>Comment</th>
                                    <td>
                                        <textarea class="form-control required" id="eg-basic" name="comment" required></textarea>
                                   </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="add_comment" name="add_comment" >Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- \. Add Comment Modal -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Comments</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Attachment</th>
                                <th>Comment</th>
                            </tr>
                            <?php
                                $oldCommentId = "";
                                if(!empty($commentInfo))
                                {  
                                    foreach($commentInfo AS $comments)
                                    {
                                        if($oldCommentId != $comments->id)
                                        {
                            ?>
                            <tr 
                                <?php if($comments->CommentStatus==1)
                                        { echo 'class=""';
                                        }
                                        elseif($comments->CommentStatus==2)
                                        {
                                            echo 'class="warning"';
                                        }
                                        elseif($comments->CommentStatus==3)
                                        {
                                            echo 'class="success"';
                                        }
                                        elseif($comments->CommentStatus==4)
                                        {
                                            echo 'class="danger"';
                                        }
                                ?>
                            > <?php  
                                    $dateTime = explode(" ",$comments->createdTime);
                                    $Date = $dateTime[0]; 
                                    $date_array = explode("-",$Date);
                                    $year = $date_array[0]; 
                                    $month = $date_array[1];
                                    $date = $date_array[2];
                                    $createdDate = $date."-".$month."-".$year;
                                ?>
                               <td><?php echo $createdDate; ?></td>
                                <td>
                                    <?php 
                                        if($comments->CommentStatus==1)
                                        {
                                            echo "Pending";
                                        }
                                        if($comments->CommentStatus==2)
                                        {
                                            echo "Inprogress";
                                        } 
                                        if($comments->CommentStatus==3)
                                        {
                                            echo "Completed";
                                        } 
                                        if($comments->CommentStatus==4)
                                        {
                                            echo "Failed";
                                        } 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        if($comments->file=='')
                                        {
                                            echo "No attachment";
                                        } 
                                        else
                                        {
                                            $id = $comments->id; 
                                    ?>
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#attachmentModal<?php echo $id; ?>">
                                            View
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="attachmentModal<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="attachmentModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header"> 
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h2 class="modal-title" id="attachmentModalLabel">Attachments</h2>
                                                    
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-hover">
                                                            <tr>
                                                               <td>
                                                                <?php
                                                                if(!empty($attachment))
                                                                {  
                                                                    foreach($attachment AS $attach)
                                                                    {
                                                                        foreach($attach AS $att)
                                                                        {  
                                                                            if($id == $att->commentId)
                                                                            {
                                                                ?>
                                                                <div class="w3-content w3-display-container">
                                                                    <img class="mySlides" src="<?php echo base_url() ?>/assets/files/<?php echo $att->file; ?>" style="width:100%">
                                                                </div>
                                                                <?php      
                                                                            }
                                    
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                    <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
                                                                    <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
                                                                
                                                               </td>
                                                            </tr>
                                                        </table>
                                                
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>  
                                </td>
                                <td>
                                <?php 
                                    $str_length = strlen($comments->userComment);
                                    if($str_length>30)
                                    {
                                        echo substr("$comments->userComment",0,30),"..." ;
                                ?>
                                    <button type="button" class="btn btn-xs" data-toggle="modal" data-target="#comment_detail<?php echo $comments->id; ?>">
                                            Read More
                                    </button>
                                    <?php
                                        }
                                        else
                                        { 
                                            echo $comments->userComment; 
                                        }
                                    ?>
                                            <!-- Modal -->
                                            <div class="modal fade" id="comment_detail<?php echo $comments->id; ?>" tabindex="-1" role="dialog" aria-labelledby="comment_detailLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header"> 
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h2 class="modal-title" id="comment_detailLabel">Comment Detail</h2>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-hover">
                                                                <tr>
                                                                    <td >
                                                                        <?php echo $comments->userComment ?>
                                                                    </td> 
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                </td>
                            </tr>
                            <?php
                                    $oldCommentId = $id; 
                                    }
                                    }
                                }
                                else
                                {
                            ?>
                            <tr class="danger">
                                    <td colspan="4">No comments</td>
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
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ajaxfileupload.js" charset="utf-8"></script>
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
    // $(function() {
    //     $('#addComment').submit(function(e) {
    //         e.preventDefault();
    //         var hitUrl = "<?php echo base_url(); ?>add-comment";
    //     //alert($('#comment_statusId').val());
    //         $.ajax({
    //             type            : "POST",
    //             url 			: hitUrl, 
    //             secureuri		:false,
    //             fileElementId	:'attachment',
    //             dataType		: 'json',
    //             data			: {
    //                 'scheduleId'			: $('#scheduleId').val(),
    //                 'userId'				: $('#userId').val(),
    //                 'statusId'				: $('#statusId').val(),
    //                 'comment'				: $('#comment').val()
    //             },
    //             success	: function (data,status)
    //             {
    //                 if(data.status != 'error')
    //                 {
    //                     $('#attachfiles').html('<p>Reloading files...</p>');
    //                     refresh_files();
    //                     $('#comment').val('');
    //                 }
    //                return false;
    //             }
    //         });
    //         return false;
    //     });
    // });
    // function refresh_files()
    // {
    //     $.get(realpath(APPPATH .'../assets/files'))
    //     .success(function (data){
    //         $('#attachfiles').html(data);
    //     });
    // }
</script>
<script src="<?php echo base_url(); ?>assets/js/froala_editor.pkgd.min.js" type="text/javascript"></script>
<script>
  $(document).ready(function() {
    $("#toggle_server").click(function() {
      var elem = $("#toggle_server").text();
      if (elem == "Read More") {
        //Stuff to do when btn is in the read more state
        $("#toggle_server").text("Read Less");
        $("#server_detail_less").hide();
        $("#server_detail_more").slideDown();
      } else {
        //Stuff to do when btn is in the read less state
        $("#toggle_server").text("Read More");
        $("#server_detail_less").show();
        $("#server_detail_more").slideUp();
      }
    });
  });
</script>
<script>
var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("mySlides");
  if (n > x.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
     x[i].style.display = "none";  
  }
  x[slideIndex-1].style.display = "block";  
}
</script>

