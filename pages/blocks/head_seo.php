<?php
// Toate lucrurile care is legate cu SEO si e nevoie sa fie in head tag, vor fi amplasate aici.

// Despre canonical si alternate
// cititi aici: https://www.portent.com/blog/seo/implement-hreflang-canonical-tags-correctly.htm
// Si aici: https://webmasters.googleblog.com/2011/12/new-markup-for-multilingual-content.html

if (!empty($page_data['page_id'])){
    $result = $CCpu->GetAlternateUrl($page_data['page_id'], $page_data['elem_id']);
    if ($result) {
        foreach ($result as $alternate){
            echo '<link rel="alternate" href="' . $alternate['cpu'] . '" hreflang="' . $alternate['lang'] . '" />' . "\n";
        }
    }

    $canonical_url = '';
    if (empty($page_data['canonical'])) {
        $canonical_url = $page_data['url'];
        echo '<link rel="canonical" href="' . $page_data['url'] . '" />';
    } else {
        $canonical_url = $page_data['canonical'];
        echo '<link rel="canonical" href="' . $page_data['canonical'] . '" />';
    }
}
?>


<?
// OpenGraph: http://ogp.me/
// E nevoie pentru Social Networks si nu numai, de a detecta correct datele saitului.
if (!empty($canonical_url)){
?>
<meta property="og:type" content="website">
<meta property="og:title" content="<?=$page_data['page_title']?>">
<meta property="og:description" content="<? echo (!empty($page_data['meta_d']) ? $page_data['meta_d'] : '')?>">
<meta property="og:url" content="<?=$canonical_url?>">
<meta property="fb:admins" content="314294162644745">
<meta property="og:site_name" content="<?=$GLOBALS['ar_define_settings']['NAME_SITE']?>">
<?
    if (!empty($result)) {
        foreach ($result as $alternate) {
            $lang = $alternate['lang'] . '_' . strtoupper($alternate['lang']);
            if ($alternate['lang'] == $CCpu->lang) {
                echo '<meta property="og:locale" content="' . $lang . '" />' . "\n";
            } else {
                echo '<meta property="og:locale:alternate" content="' . $lang . '" />' . "\n";
            }
        }
    }

    if ($page_data['page_type'] == 'catalog_elements'){
        if (!empty($Elem['image'])){
            $img_url = 'https://' . $GLOBALS['ar_define_settings']['DOMAIN'] . '/upload/cars/detail/' . $Elem['image'];
            $output = <<<HERE
<meta property="og:image" content="{$img_url}" />
<meta property="og:image:width" content="736" />
<meta property="og:image:height" content="487" />
HERE;
            echo $output;
        }
    }
    else{
        $img_url = 'https://' . $GLOBALS['ar_define_settings']['DOMAIN'] . '/images/other/biglogo.png';
        $output = <<<HERE
<meta property="og:image" content="{$img_url}" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
HERE;
        echo $output;
    }
}
?>

<meta name="geo.placename" content="Chisinau, Moldova" />
<meta name="geo.position" content="<?=$GLOBALS['ar_define_settings']['MAPS_LAT']?>,<?=$GLOBALS['ar_define_settings']['MAPS_LNG']?>" />
<meta name="geo.region" content="Chisinau" />
<meta name="ICBM" content="<?=$GLOBALS['ar_define_settings']['MAPS_LAT']?>,<?=$GLOBALS['ar_define_settings']['MAPS_LNG']?>" />
<meta property="place:location:latitude" content="<?=$GLOBALS['ar_define_settings']['MAPS_LAT']?>" />
<meta property="place:location:longitude" content="<?=$GLOBALS['ar_define_settings']['MAPS_LNG']?>" />
<meta property="business:contact_data:street_address" content="Bănulescu Bodoni 57/1" />
<meta property="business:contact_data:locality" content="Chisinau" />
<meta property="business:contact_data:postal_code" content="MD-2005" />
<meta property="business:contact_data:country_name" content="Moldova" />
<meta property="business:contact_data:email" content="<?=$GLOBALS['ar_define_settings']['EMAIL']?>" />
<?php
    $main_phone = str_replace(['(', ')', ' ', '-'], '', $GLOBALS['ar_define_settings']['EMAIL_PHONE']);
    if (strpos($GLOBALS['ar_define_settings']['PHONE_CNT'], '||') !== false){
        $numbers = explode('||', $GLOBALS['ar_define_settings']['PHONE_CNT']);
        foreach ($numbers as $number){
            $number = str_replace(['(', ')', ' ', '-'], '', $number);
            echo '<meta property="business:contact_data:phone_number" content="' . $number . '" />' . "\n";
        }
    }
    else {
        $number = str_replace(['(', ')', ' ', '-'], '', $GLOBALS['ar_define_settings']['PHONE_CNT']);
        echo '<meta property="business:contact_data:phone_number" content="' . $number . '" />' . "\n";
    }
?>
<meta property="business:contact_data:website" content="https://<?=$GLOBALS['ar_define_settings']['DOMAIN']?>/" />

<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "WebSite",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "https://<?=$GLOBALS['ar_define_settings']['DOMAIN']?><?=$CCpu->writelink(51)?>?key={search_term}",
            "query-input": "required name=search_term"
        },
        "url": "https://<?=$GLOBALS['ar_define_settings']['DOMAIN']?>/"
    }
</script>
<script type="application/ld+json">
    { "@context" : "http://schema.org",
        "@type" : "Organization",
        "url" : "https://<?=$GLOBALS['ar_define_settings']['DOMAIN']?>/",
        "name" : "<?=$GLOBALS['ar_define_settings']['NAME_SITE']?>",
        "alternateName" : "Car4rent Moldova",
        "legalName" : "Ver Trans Auto S.R.L.",
        "foundingDate":"15.04.2011",
        "foundingLocation":"Moldova",
        "description": "<?=$CCpu->GetPageDescription(1)?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Bănulescu Bodoni 57/1",
            "addressLocality": "Chișinău",
            "addressRegion": "Chișinău",
            "postalCode": "MD-2005",
            "addressCountry": "MD" },
        "sameAs" : ["https://www.facebook.com/car4rentmoldova/", "https://www.instagram.com/car4rentmoldova/"],
        "email":"<?=$GLOBALS['ar_define_settings']['EMAIL']?>",
        "brand":"Car4rent Moldova",
        "logo": "https://<?=$GLOBALS['ar_define_settings']['DOMAIN']?>/images/other/logo.png",
        "contactPoint" : [{ "@type" : "ContactPoint", "telephone" : "<?=$main_phone?>", "contactType" : "customer service" }]
    }
</script>
<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "Place",
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "<?=$GLOBALS['ar_define_settings']['MAPS_LAT']?>",
            "longitude": "<?=$GLOBALS['ar_define_settings']['MAPS_LNG']?>"
        },
        "hasMap": {
            "@type": "Map",
            "url":  "https://goo.gl/maps/WeRErsJ7jxNNVohB7"
        },
        "name": "<?=$GLOBALS['ar_define_settings']['NAME_SITE']?>"
    }
</script>

<?
    if($page_data['page_type'] == 'catalog_elements') {
        $img_url_1 = 'https://' . $GLOBALS['ar_define_settings']['DOMAIN'] . '/upload/cars/detail/' . $Elem['image'];
        $img_url_2 = 'https://' . $GLOBALS['ar_define_settings']['DOMAIN'] . '/upload/cars/detail/' . $Elem['image_detail'];

        $rating = '';
        $DataRatin = rating_product($Elem['id']);

        if ($DataRatin['count']) {
            $rating = '"aggregateRating": {
            "@type": "AggregateRating",
                    "ratingValue": "' . $DataRatin['count_nf'] . '",
                    "reviewCount": "' . $DataRatin['count'] . '"
                },' . "\n";
        }
        ?>
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Product",
                <?=$rating?>
                "description": "<? echo (!empty($page_data['meta_d']) ? $page_data['meta_d'] : '')?>",
                "name": "<?=$page_data['page_title']?>",
                "image": [
                    "<?=$img_url_1?>",
                    "<?=$img_url_2?>"
                ],
                "offers": {
                    "@type": "AggregateOffer",
                    "availability": "http://schema.org/InStock",
                    "highPrice": "<?=$Elem['pd-1']?>",
                    "lowPrice": "<?=$Elem['pd-5']?>",
                    "priceCurrency": "EUR"
                }
            }
        </script>
        <?
    }
?>