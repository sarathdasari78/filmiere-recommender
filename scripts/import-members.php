<?php
// 1) Path to your CSV
$file = '/home/bitnami/members.csv';

// 2) Open the CSV
if (! file_exists($file)) {
  WP_CLI::error("CSV not found at $file");
}
if (($h = fopen($file, 'r')) === false) {
  WP_CLI::error("Could not open CSV");
}

// 3) Read header row
$header = fgetcsv($h);

// 4) Loop through each member
while ($row = fgetcsv($h)) {
  $data = array_combine($header, $row);

  // Skip existing users
  if ( username_exists($data['username']) || email_exists($data['email']) ) {
    WP_CLI::warning("Skipping existing {$data['username']}");
    continue;
  }

  // 5) Create the WP user
  $user_id = wp_create_user(
    $data['username'],
    $data['password'],
    $data['email']
  );
  if ( is_wp_error($user_id) ) {
    WP_CLI::warning("Failed to create {$data['username']}: " . $user_id->get_error_message());
    continue;
  }

  // 6) Set display name and role
  wp_update_user(['ID' => $user_id, 'display_name' => $data['display_name']]);
  $user = new WP_User($user_id);
  $user->set_role('subscriber');

  // 7) Populate PeepSo fields and custom meta
  update_user_meta($user_id, 'peepso_profile[field_266]', $data['country']);    // Country
  update_user_meta($user_id, 'peepso_profile[field_969]', $data['state']);      // State
  update_user_meta($user_id, 'cinema_designation',   $data['designation']);    // Designation
  update_user_meta($user_id, 'cinema_city',          $data['city']);           // City

  WP_CLI::success("Created {$data['username']} (#{$user_id})");
}

fclose($h);
WP_CLI::success("All members imported!");
