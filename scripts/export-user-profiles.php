<?php
// Path inside WP where we know we can write:
$path = ABSPATH . 'user_profiles.csv';

// Open for writing (overwrites if existing)
$fh = fopen($path, 'w');
if ( ! $fh ) {
  WP_CLI::error("Cannot open {$path} for writing");
}

// Write header row
fputcsv($fh, ['user_id','username','country','state','designation','city']);

// Fetch all subscriber users
$users = get_users(['role'=>'subscriber']);

// Loop and write each profile
foreach ( $users as $u ) {
  $country     = get_user_meta($u->ID, 'peepso_profile[field_266]', true);
  $state       = get_user_meta($u->ID, 'peepso_profile[field_969]', true);
  $designation = get_user_meta($u->ID, 'cinema_designation',   true);
  $city        = get_user_meta($u->ID, 'cinema_city',          true);
  fputcsv($fh, [
    $u->ID,
    $u->user_login,
    $country,
    $state,
    $designation,
    $city
  ]);
}

fclose($fh);
WP_CLI::success("Exported " . count($users) . " users to {$path}");
