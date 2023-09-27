<?php

$magazine = [];
$stripe = [];
$salesforce = [];
$is_sandbox = isset($_ENV) && isset($_ENV['ENVIRONMENT']) && 'development' == $_ENV['ENVIRONMENT'];
//in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']); //false;

$magazine['number_of_issues'] = 4;
$magazine['base_price_printonly'] = 69.95;
$magazine['base_price_digitalonly'] = 39.95;
$magazine['base_price_printdigital'] = 79.95;
$magazine['base_price_legacy'] = 59.95;
$magazine['shipping_cost'] = 0.00;

if ($is_sandbox) {
    // Stripe
    $stripe['publishable_key'] = 'pk_test_51LavybCtuVZtvaO71VCyCi5zpEgutjPe1I2rSn5MJixsb5HVWLpne4gjXPXh2bJQYaIiAY9Z1T8KqvwUHU4homC100eGKCRsQ9';
    $stripe['secret_key'] = 'sk_test_51LavybCtuVZtvaO7nhSgDojwrfoPuQgrc90ec9tl6XLFHB3wSe4r7FSESUCKMauLOPiaNMcAFzcYxkkeNruJrS2m00tOkyKc4x';
    $stripe['plan_id'] = 'plan_GyHm8Pt4LHcID0';
    $stripe['default_tax_rates'] = 'txr_1NFWHTCtuVZtvaO7VomYnjDP';
    // $stripe['current_coupon_code'] = '93YgiA4h';

    // Salesforce
    $salesforce['client_id'] = '3MVG9qvlreg8bJAA05oteL_WJJiYgwC90P4Mtc2FwAx6Xtn8v9_pIop7ZVRPO1GqzuRuhLwLyoflsFBc4oJ0I';
    $salesforce['client_secret'] = '7797CE14DAE7115F01F5670ACE23E6BF63165B23A0CD344B4A14F84A6CD3CD76';
    $salesforce['login_uri'] = 'https://thebragmedia--tbmsandbox.sandbox.my.salesforce.com';
    $salesforce['username'] = 'dev@thebrag.media.tbmsandbox';
    $salesforce['password'] = 'deTbm99!#6GmJvWE3LIQ9uMDBDG7EZ7cv';

    $digital['api_url'] = 'https://dfapi.emagazines.com';
    $digital['api_key'] = '796EEE3C-C967-42C1-83B9-0D4A73A84DE2';
    $digital['print'] = 'rolling_stone_aunz_print_only_instant_start_';
    $digital['digital'] = 'rolling_stone_aunz_digital_forward_1';
    $digital['catalogue'] = 'rolling_stone_aunz_digital_back';

    $api_brag_user['api_url'] = 'https://thebrag.com/wp-json/tbm_users/v1/';
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
    $salesforce['login_uri'] = 'https://thebragmedia.my.salesforce.com';
    $salesforce['username'] = 'dev@thebrag.media';
    $salesforce['password'] = 'deTbm99!#';

    $api_brag_user['api_url'] = 'https://thebrag.com/wp-json/tbm_users/v1/';

    $digital['api_url'] = 'https://dfapi.emagazines.com';
    $digital['api_key'] = '796EEE3C-C967-42C1-83B9-0D4A73A84DE2';
    $digital['print'] = 'rolling_stone_aunz_print_only_instant_start_';
    $digital['digital'] = 'rolling_stone_aunz_digital_forward_1';
    $digital['catalogue'] = 'rolling_stone_aunz_digital_back';
}

$api_brag_user['rest_api_key'] = '1fc08f46-3537-43f6-b5c1-c68704acf3fa';

return [
    'stripe' => $stripe,
    'salesforce' => $salesforce,
    'magazine' => $magazine,
    'api_brag_user' => $api_brag_user,
    'digital' => $digital,
    'is_sandbox' => $is_sandbox,
];
