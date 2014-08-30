php-request-response
====================

A simple response class for wrapping a response to a request.

## Example Usage
```php
function create_new_user($data)
{
	$errors = array();
	// Validate $data
	if ( empty($data['username']) ) {
		$errors['username'] = array('required');
	}
	if ( strlen($data['username']) > 8 ) {
		$errors['username'] = array('min_length', 8);
	}
	...

	if ( count($errors) > 0 ) {
		return new ErrorResponse('Please check your input', $errors);
	}

	$guid = save_to_db($data);
	if ( $id !== false ) {
		return new SuccessResponse($guid);
	}
	return new ErrorResponse('Failed to save to database', array('db_error' => get_the_database_error() ) );
}
```

The below code is for example purposes and could be greatly reduced 
by writing a few simple helper methods.
```php
$ret = create_new_user($data);
if ( $ret->success() ) {
	$user_guid = $ret->data;
	...

} else {
	// Check for a database error
	if ( isset($ret->state['db_error']) ) {
		// Record our application error
		app_log('ERROR: ' . $ret->state['db_error']);

		die($ret->data . '. Please view this cute kitten picture instead.');
	} else {
		$messages = array(
			'username' => array(
				'min_length' => 'Your username must be at least {0} alphanumeric characters long'
			)
		);

		$errors = array();
		// Map state keys to actual error messages
		foreach ( $ret->state AS $k => $v ) {
			if ( isset($messages[$k]) && isset($messages[$k][ $v[0] ]) ) {
				$err     = $messages[$k][ $v[0] ];
				$replace = array_slice($v, 1);
				foreach ( $replace AS $i => $val ) {
					$err = str_replace('{' . $i . '}', $val, $err);
				}
				$errors[] = $err;
			}
		}
	}
	...
}
```