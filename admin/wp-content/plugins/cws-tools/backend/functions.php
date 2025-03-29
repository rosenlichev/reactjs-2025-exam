<?php

function allow_subscribers_as_authors() {
    $role = get_role('subscriber');

    if ($role && !$role->has_cap('edit_posts')) {
        $role->add_cap('edit_posts'); // Allow Subscribers to be authors
    }
}