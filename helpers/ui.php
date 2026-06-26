<?php
// helpers/ui.php

function get_branch_badge_style($id_cabang) {
    // Simple implementation based on ID to generate a consistent color
    $hash = crc32((string)$id_cabang);
    $colors = ['#4361ee', '#3f37c9', '#4895ef', '#560bad', '#7209b7', '#b5179e', '#f72585'];
    $color = $colors[$hash % count($colors)];
    return "background-color: $color; color: #fff; padding: 0.25rem 0.5rem; border-radius: 0.25rem;";
}
?>
