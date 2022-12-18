<?php
$stripe = [];
$salesforce = [];
$is_sandbox = isset($_ENV) && isset($_ENV['ENVIRONMENT']) && 'sandbox' == $_ENV['ENVIRONMENT'];
//in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']); //false;
$magazine = [
    'number_of_issues' => 4,
    'base_price' => 69.95, // for new
    'base_price_legacy' => 59.95, // for past subs
    'shipping_cost' => 0.00,
];
if ($is_sandbox) {
    // Stripe
    $stripe['publishable_key'] = 'pk_test_bX98JZECbYwuCDuHqqRUTktS00uuBIZnlO';
    $stripe['secret_key'] = 'sk_test_vgPAGgiC8CzE5PCeMfNXhMbB002o1zu6Q7';
    $stripe['plan_id'] = 'plan_GyHm8Pt4LHcID0';
    $stripe['default_tax_rates'] = 'txr_1GiDj6Gdx829KD99UdrsUpVE';
    // $stripe['current_coupon_code'] = '93YgiA4h';

    // Salesforce
    $salesforce['client_id'] = '3MVG9N6eDmZRVJOnbnGA3SU5MvC6Cw3r1x8nRb81EfTzuINhI3oD4QaRJwjETtD3g4IyIGjxTqN7iS5h73rC.';
    $salesforce['client_secret'] = '2790C31BBCFE87FE01C14EE44A3DA272EE2DDFAFE0938D814EC97B827B0016AC';
    $salesforce['login_uri'] = 'https://thebragmedia--tbmsandbox.my.salesforce.com/';
    $salesforce['username'] = 'sachin.patel@thebrag.media.tbmsandbox';
    $salesforce['password'] = 'sAchin456';

    $api_brag_user['api_url'] = 'https://the-brag.com/wp-json/tbm_users/v1/';
} else {
    // Stripe
    $stripe['publishable_key'] = 'pk_live_bwH5gFYGDrxdtEMOR4v0Gfhq00pQVjdvar';
    $stripe['secret_key'] = 'sk_live_r1QlJPmaGThmo01bYmFwymIZ00I1xqYfBZ';
    $stripe['plan_id'] = 'plan_GyjXgQrFMNXyVM';
    $stripe['default_tax_rates'] = 'txr_1GQjy9Gdx829KD99cIXBU42W';
    // $stripe['current_coupon_code'] = 'SubFirstIssue';

    // Salesforce
    $salesforce['client_id'] = '3MVG9G9pzCUSkzZskqIN3a4qW0R3A8M8wOR_tcBYKxXgBiCJXOVESBA399bvfHSMktJHlaHipQQhH.jwYJVt5';
    $salesforce['client_secret'] = '2D07B74620BC31B2A7CA3D14C7DD840F22189B3A9DF75B6BB9EFC59E8FD2F568';
    $salesforce['login_uri'] = 'https://thebragmedia.my.salesforce.com/';
    $salesforce['username'] = 'dev@thebrag.media';
    $salesforce['password'] = 'deTbm99!#';

    $api_brag_user['api_url'] = 'https://thebrag.com/wp-json/tbm_users/v1/';
}

$api_brag_user['rest_api_key'] = '1fc08f46-3537-43f6-b5c1-c68704acf3fa';

return [
    'stripe' => $stripe,
    'salesforce' => $salesforce,
    'magazine' => $magazine,
    'api_brag_user' => $api_brag_user,
];
