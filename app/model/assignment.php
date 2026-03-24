<?php
function addAssignment($info) {
    try {
        $sql = 'INSERT INTO assignments (hardware_id, employee_id, assigned_at) values(:hardware_id, :employee_id, current_timestamp())';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':hardware_id' => $info['hardware_id'],
                        ':employee_id'=> $info['employee_id'],
                        ]);
        echo 'hi';
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}


function updateAssignment($hardware_id){
    try {
        $sql = 'UPDATE assignments SET returned_at = NOW() WHERE hardware_id = :hardware_id AND returned_at IS NULL';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt-> execute([':hardware_id'=> $hardware_id]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    } 
}