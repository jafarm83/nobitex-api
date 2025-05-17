<?php
header('Content-Type: application/json');

$srcCurrencies = "btc,eth,usdt,ada,doge,xrp,ltc,bnb,dai,shib,sol,uni,link";
$dstCurrencies = "rls,usdt";

$url = "https://api.nobitex.ir/market/stats?srcCurrency=$srcCurrencies&dstCurrency=$dstCurrencies";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$responseJson = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $response = json_decode($responseJson, true);
    $currenciesArray = explode(',', $srcCurrencies);
    $result = [];

    foreach ($currenciesArray as $currency) {
        $rialKey = "$currency-rls";
        $usdtKey = "$currency-usdt";

        $priceToman = isset($response['stats'][$rialKey]['latest']) ? $response['stats'][$rialKey]['latest'] / 10 : null;
        $priceUsdt = isset($response['stats'][$usdtKey]['latest']) ? $response['stats'][$usdtKey]['latest'] : null;

        $result[$currency] = [
            'PriceToman' => $priceToman,
            'PriceUsdt' => $priceUsdt
        ];
    }

    echo json_encode($result);
} else {
    echo json_encode(['error' => 'API call failed']);
}
?>
