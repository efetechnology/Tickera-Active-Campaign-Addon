<?php
  function _api_check_list($tc_camp_setting, $id_list){
    $url = $tc_camp_setting['url_account'];
    $params = array(
      'api_key'      => $tc_camp_setting['api_key'],
      'api_action'   => 'list_view',
      'api_output'   => 'json',
      'id'           => $id_list,
    );

    // This section takes the input fields and converts them to the proper format
    $query = "";
    foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
    $query = rtrim($query, '& ');

    // clean up the url
    $url = rtrim($url, '/ ');

    // This sample code uses the CURL library for php to establish a connection,
    // submit your request, and show (print out) the response.
    if ( !function_exists('curl_init') ) die('CURL not supported. (introduced in PHP 4.0.2)');

    // If JSON is used, check if json_decode is present (PHP 5.2.0+)
    if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
      die('JSON not supported. (introduced in PHP 5.2.0)');
    }

    // define a final API request - GET
    $api = $url . '/admin/api.php?' . $query;

    $request = curl_init($api); // initiate curl object
    curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
    //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

    $response = (string)curl_exec($request); // execute curl fetch and store results in $response

    // additional options may be required depending upon your server configuration
    // you can find documentation on curl options at http://www.php.net/curl_setopt
    curl_close($request); // close curl object

    if ( !$response ) {
      die('Nothing was returned. Do you have a connection to Email Marketing server?');
    }
    echo $response;
  }
?>
