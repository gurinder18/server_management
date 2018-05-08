<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-list-alt"></i> Check IP Blacklist
      </h1>
    </section>
    <section class="content">
        
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <form action="<?php echo base_url(); ?>blacklist" method="get">
                        <input type="text" value="" name="ip"/>
                        <input type="submit" value="LOOKUP"/>
                    </form>
                  
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
