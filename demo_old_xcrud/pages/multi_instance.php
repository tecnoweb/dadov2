<?php
	echo '<div id="orders-wrapper" style="margin-bottom: 30px;">';
	echo '<h3>Orders Table</h3>';
	$xcrud1 = Xcrud::get_instance('orders_table');
    $xcrud1->table('orders');
    echo $xcrud1->render();
    echo '</div>';
    
    echo '<div id="payments-wrapper" style="margin-bottom: 30px;">';
    echo '<h3>Payments Table</h3>';
    $xcrud2 = Xcrud::get_instance('payments_table');
    $xcrud2->table('payments');
    echo $xcrud2->render();
    echo '</div>';
?>