<?php
foreach ($res as $line) {
    $action = $line->actionType;
    $nickname = get_the_author_meta('nickname', $line->userID);
    $time = human_time_diff(strtotime($line->actionTime), current_time('timestamp')) . ' ago';

    $postdata = get_post($line->postID, ARRAY_A);
    $authorID = $postdata['post_author'];

    // New upload
    $posterUri = get_the_post_thumbnail_url($line->postID, 'imagepress_feed');

	if ((string) $action === 'added' /*&& pwuf_is_following($user_ID, $authorID)*/) {
        // Check if post exists and is published
        if ('publish' === get_post_status($line->postID) && has_post_thumbnail($line->postID)) {
            echo '<div class="feed-item n' . $line->ID . '" data-id="' . $line->ID . '" id="item' . $line->ID . '">
                <div class="feed-meta-primary">
                    <div class="feed-avatar">' . get_avatar($line->userID, 48) . '</div>
                    <a href="' . get_author_posts_url($line->userID) . '" class="regular-link">' . $nickname . '</a> uploaded <a href="' . get_permalink($line->postID) . '" class="regular-link">' . get_the_title($line->postID) . '</a>
                </div>
                <div class="feed-meta-tertiary">
                    <small>
                        <time>' . $time . '</time> | <i class="fas fa-comment" aria-hidden="true"></i> ' . get_comments_number($line->postID) . ' | <i class="fas fa-heart" aria-hidden="true"></i> ' . imagepress_get_like_count($line->postID) . '
                    </small>
                </div>
                <div class="feed-meta-secondary">
                    <a href="' . get_permalink($line->postID) . '"><img src="' . $posterUri . '" alt="' . get_the_title($line->postID) . '" width="700"></a>
                </div>
            </div>';
        }
    }
}
