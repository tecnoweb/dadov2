<?php
	$xcrud = Xcrud::get_instance();
    $xcrud->table('consultation');
    $xcrud->relation('office','offices','officeCode','city');
    $xcrud->relation('manager','employees','employeeNumber',array('firstName','lastName'),'','','',' ','','officeCode','office');
    
    $xcrud->relation('country','meta_location','id','local_name','type = \'CO\'');
    $xcrud->relation('region','meta_location','id','local_name','type = \'RE\'','','','','','in_location','country');
    $xcrud->relation('city','meta_location','id','local_name','type = \'CI\'','','','','','in_location','region');
    
    echo $xcrud->render('create');
?>

<link href="http://select2.github.io/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://select2.github.io/dist/js/select2.full.js"></script>
<script type="text/javascript">
jQuery(document).on("xcrudbeforerequest", function(event, container) {
    if (container) {
        jQuery(container).find("select").select2("destroy");
    } else {
        jQuery(".xcrud").find("select").select2("destroy");
    }
});
jQuery(document).on("ready xcrudafterrequest", function(event, container) {
    if (container) {
        jQuery(container).find("select").select2();
    } else {
        jQuery(".xcrud").find("select").select2();
    }
});
jQuery(document).on("xcrudbeforedepend", function(event, container, data) {
    jQuery(container).find('select[name="' + data.name + '"]').select2("destroy");
});
jQuery(document).on("xcrudafterdepend", function(event, container, data) {
    jQuery(container).find('select[name="' + data.name + '"]').select2();
});
</script>