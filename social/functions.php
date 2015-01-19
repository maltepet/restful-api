<?php
function validate($req, $return) {
	foreach ( $req as $field ) {
		if ( isset($_POST[$field]) ) {
			if ( $_POST[$field] == -1 || $_POST[$field] == '' ) {
				$_SESSION['error'] = 'Please fill out all required fields';
				header("Location: {$return}");
				exit();
			}
		}
	}		
}

function time_ago($date,$granularity=2) {
	$retval = '';
    $date = strtotime($date);
    $difference = time() - $date;
    $periods = array('decade' => 315360000,
        'year' => 31536000,
        'month' => 2628000,
        'week' => 604800, 
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1);

    foreach ($periods as $key => $value) {
        if ($difference >= $value) {
            $time = floor($difference/$value);
            $difference %= $value;
            $retval .= ($retval ? ' ' : '').$time.' ';
            $retval .= (($time > 1) ? $key.'s' : $key);
            $granularity--;
        }
        if ($granularity == '0') { break; }
    }
    return $retval.' ago';      
}

function is_following($user_id) {
	
	$data = array('user_id' => $user_id, 'session_id' => $_SESSION['user_id']);
	
	$request = new ApiCall('users/checkfollowing');
	$r = $request->post($data);
	
	if ( $r->success ) {
		if ( $r->data ) {
			return true;
		}
		
		else {
			return false;
		}
	}
	
	else {
		return false;
	}
	
}