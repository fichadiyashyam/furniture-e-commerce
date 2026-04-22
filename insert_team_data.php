<?php
include 'config/db_config.php';

$team_members = [
    [
        'name' => 'Lawson Arnold',
        'position' => 'CEO, Founder, Atty.',
        'description' => 'Separated they live in. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.',
        'image' => 'person_1.jpg'
    ],
    [
        'name' => 'Jeremy Walker',
        'position' => 'CEO, Founder, Atty.',
        'description' => 'Separated they live in. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.',
        'image' => 'person_2.jpg'
    ],
    [
        'name' => 'Patrik White',
        'position' => 'CEO, Founder, Atty.',
        'description' => 'Separated they live in. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.',
        'image' => 'person_3.jpg'
    ],
    [
        'name' => 'Kathryn Ryan',
        'position' => 'CEO, Founder, Atty.',
        'description' => 'Separated they live in. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.',
        'image' => 'person_4.jpg'
    ]
];

foreach ($team_members as $member) {
    $name = mysqli_real_escape_string($connection, $member['name']);
    $position = mysqli_real_escape_string($connection, $member['position']);
    $description = mysqli_real_escape_string($connection, $member['description']);
    $image = mysqli_real_escape_string($connection, $member['image']);

    $check_query = "SELECT * FROM team WHERE name = '$name'";
    $check_result = mysqli_query($connection, $check_query);

    if ($check_result && mysqli_num_rows($check_result) == 0) {
        $sql = "INSERT INTO team (name, position, description, image, status) VALUES ('$name', '$position', '$description', '$image', 'active')";
        if (mysqli_query($connection, $sql)) {
            echo "Inserted: " . $name . "<br>";
        } else {
            echo "Error inserting " . $name . ": " . mysqli_error($connection) . "<br>";
        }
    } else {
        echo "Member " . $name . " already exists.<br>";
    }
}

mysqli_close($connection);
?>
