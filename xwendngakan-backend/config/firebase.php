<?php

/*
 | Resolve the Firebase service-account credentials path.
 |
 | FIREBASE_CREDENTIALS_JSON may be empty, an absolute path, or a path
 | relative to the project root. A relative path must be resolved against
 | base_path() — otherwise file_exists() checks resolve against the web
 | server's working directory (e.g. public/) and the file is never found,
 | silently disabling Firebase.
 */
$credentials = env('FIREBASE_CREDENTIALS_JSON');

if (empty($credentials)) {
    $credentials = storage_path('app/firebase-credentials.json');
} elseif (! str_starts_with($credentials, DIRECTORY_SEPARATOR)) {
    $credentials = base_path($credentials);
}

return [
    'project_id' => env('FIREBASE_PROJECT_ID'),
    'credentials' => $credentials,
];
