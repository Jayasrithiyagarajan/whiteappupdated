<?php
return [
    'name'       => strtoupper($assessment['client_name'] ?? ''),
    'logo'       => !empty($assessment['client_logo']) ? abs_path(__DIR__.'/../../'.$assessment['client_logo']) : abs_path(__DIR__.'/../../document/logo.png'),
    'leea_logo'  => abs_path(__DIR__.'/../../document/leea.png'), // Placeholder or fallback
    'seal'       => abs_path(__DIR__.'/../../document/gold_seal.png') ?: abs_path(__DIR__.'/../../document/trusted_partner_badge.png'),
    'website'    => 'www.cims.com.sa',
    'email'      => 'info@cims.com.sa',
    'phone'      => '+966 13 882 1735',
    'address'    => "Building No. 7036, Al Andalus Street\nBehind Hali Centre, Al Rakaah P.O. Box 74007,\nAl-Khobar, 31952, Kingdom of Saudi Arabia"
];
