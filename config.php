<?php

// WP Load
define('WP_LOAD', '../wordpress/wp-load.php');

// API home location
define('URI', '/ex/backend/api');

// Registered clients
define('CLIENTS', serialize(
    [
        [
            'id' => 'web',
            'secret' => 'b020d5effjd03k498759c0aaaec0dd25',
            'description' => 'Awesome Movie Club web ...'
        ], [
            'id' => 'app',
            'secret' => 'b020d5eff40b33498759c0aaaec0dd25',
            'description' => 'Awesome Movie Club mobile app ...'
        ]
    ]
));

// Post Config
define('POST', serialize(
    [
        // post_type = 'post'
        'post' => [
            'taxonomies' => ['category', 'post_tag'],
            'schema' => [
                
                // Basic fields
                'id' => 'ID',
                'title' => 'post_title',
                'slug' => 'post_name',
                'date' => 'post_date',
                'date_gmt' => 'post_date_gmt',
                'modified' => 'post_modified',
                'modified_gmt' => 'post_modified_gmt',
                'excerpt' => 'post_excerpt',
                'content' => 'post_content',
                'status' => 'post_status',
                'type' => 'post_type',
                'author' => 'post_author',
                'parent' => 'post_parent',
                'guid' => 'guid',
                'comment_count' => 'comment_count',
                
                // Meta fields ('__xxx' => 'original_key')
                '__view_count' => 'acf__all__view',
                '__like_count' => 'acf__all__like',
                '__share_count' => 'acf__all__share',
                '__test' => 'post__test'
            ],
            
            // Increment-able fields
            'increment' => [
                'acf__all__view',
                'acf__all__like',
                'acf__all__share'
            ]
        ],
        
        
        // post_type = 'movie'
        'movie' => [
            'taxonomies' => ['movie_genre'],
            'schema' => [
                // Basic fields
                'id' => 'ID',
                'title' => 'post_title',
                'slug' => 'post_name',
                'date' => 'post_date',
                'date_gmt' => 'post_date_gmt',
                'modified' => 'post_modified',
                'modified_gmt' => 'post_modified_gmt',
                'excerpt' => 'post_excerpt',
                'content' => 'post_content',
                'status' => 'post_status',
                'type' => 'post_type',
                'author' => 'post_author',
                'parent' => 'post_parent',
                'guid' => 'guid',
                'comment_count' => 'comment_count',

                // Meta fields ('__xxx' => 'original_key')
                '__release_date' => 'acf__movie__release_date',
                '__poster' => 'acf__movie__poster',
                '__trailer' => 'acf__movie__trailer',
                '__trailer_youtube' => 'acf__movie__trailer_youtube',
                '__view_count' => 'acf__all__view',
                '__like_count' => 'acf__all__like',
                '__share_count' => 'acf__all__share'
            ],
            
            // Increment-able fields
            'increment' => [
                'acf__all__view',
                'acf__all__like',
                'acf__all__share'
            ]
        ]
    ]
));

// Comment Config
define('COMMENT', serialize(
    [
        'schema' => [
            'id' => 'comment_ID',
            'post_id' => 'comment_post_ID',
            'user_id' => 'user_id',
            'email' => 'comment_author_email',
            'author' => 'comment_author',
            'date' => 'comment_date',
            'parent' => 'comment_parent',
            'content' => 'comment_content'            
        ]
    ]
));