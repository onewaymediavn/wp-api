<?php

// Post class
class Post {
    
    private $_LIMIT = 15;
    
    private $_REQUIRED_SCHEMA = [
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
        'comment_count' => 'comment_count'
    ];
    
    
    
    /*
    *   METHODS
    */
    
    private function _meta( $id = null, $key = null ) {
        
        if($id === null || $key === null) {
            return null;
        }
        
        if( substr($key, 0, 3) === 'acf' ) {
            return get_field($key, $id, true);
        } else {
            return get_post_meta($id, $key, true);
        }
    }    
    
    private function _get_meta($posts = null) {
        $_CONFIG = unserialize(POST);
        
        if($posts === null || $posts === []) {
            return [];
        }
        
        // Meta
        foreach($posts as $post) {
            $temp = (array) $post;            
            foreach( $_CONFIG[ $temp['post_type'] ]['schema'] as $key => $original_key ) {
                if( substr($key, 0, 2) === '__') {              
                    $temp[$original_key] = $this->_meta($temp['ID'], $original_key);
                }
            }

            $output[] = $temp;
        }
        
        return $output;
    }
    
    private function _sanitize($posts = null) {
        $_CONFIG = unserialize(POST);
        
        if($posts === null) {
            return [];
        }

        // Convert $posts into array
        if( !is_array($posts) ) {
            $posts[] = $posts;
        }

        // Output data
        $output = [];
        foreach( $posts as $post ) {
            $temp = [];
            foreach( $_CONFIG[ $post['post_type'] ]['schema'] as $key => $original_key ) {
                $temp[$key] = $post[$original_key];
            }
            $output[] = $temp;            
        }        
        return $output;
    }
        
    private function _data_corrector($posts = null) {
        if($posts === null) {
            return [];
        }
        
        $output = [];
        
        foreach($posts as $post) {
            $tmp = [];
            foreach($post as $key => $value) {
                if( is_numeric($value) ) {
                    if( strpos($value, '.') !== false ) {
                        $tmp[$key] = floatval($value);
                    } else {
                        $tmp[$key] = intval($value);
                    }
                    
                } elseif( $key === 'content' ) {
                    $tmp[$key] = apply_filters( 'the_content', $value );
                } else {
                    $tmp[$key] = $value;
                }
            }
            $output[] = $tmp;
        }
        
        return $output;
    }
    
    
    
    
    
    // Get term(s)
    public function term($args = []) {
        $_CONFIG = unserialize(POST);
        
        $id = $args['id'];
        $slug = $args['slug'];
        $post_type = $args['post_type'];
        $taxonomy = $args['taxonomy'];
        
        
        if($post_type === null || $taxonomy === null) {
            return [
                'errors' => [[
                    'msg' => '(#) Not enough infomation!'
                ]]
            ];
        }
        
        if( $_CONFIG[$post_type] === null ) {
            return [
                'errors' => [[
                        'msg' => '(#) No post type \''.$post_type.'\' found!'
                ]]
            ];
        }
        
        if( ! in_array($taxonomy, $_CONFIG[$post_type]['taxonomies']) ) {
            return [    
                'errors' => [[
                        'msg' => '(#) No taxonomy \''.$taxonomy.'\' found in post type \''.$post_type.'\'!'
                ]]
            ];
        }
        
        $schema = [
            'id' => 'term_id',
            'slug' => 'slug',
            'title' => 'name',
            'description' =>' description',
            'count' => 'count'
        ];
        
        // All categories
        $terms = get_categories([
            'type' => $post_type,
            'taxonomy' => $taxonomy,
            'orderby' => 'id',
            'order' => 'ASC',
            // 'exclude'=>'83'
        ]);
        
		// Get all categories
        if($id === null && $slug === null) {
            return sanitize($terms, $schema);
        }
        
        // Get term by ID
        if($id !== null) {
            return sanitize(find($terms, 'term_id', $id), $schema); // By ID
        }
        
        // Get term by slug
        if($slug !== null) {
            return sanitize(find($terms, 'slug', $slug), $schema); // By slug
        }
        
        return $terms;
    }
    
    // Get post(s)
    public function post($args = []) {
        $_CONFIG = unserialize(POST);
        
        // Has 'slug' but no 'post_type'
        if( $args['slug'] !== null && $args['post_type'] === null ) {
            return [
                'errors' => [[
                    'msg' => '(#) Slug \''.$args['slug'].'\' need a post_type!'
                ]]
            ];
        }
        
        // Has no 'id' and no 'post_type'
        if( $args['id'] === null && $args['post_type'] === null ) {
            return [
                'errors' => [[
                    'msg' => '(#) Not enough infomation!'
                ]]
            ];
        }
        
        // Has 'taxonomy' but no 'post_type'
        if( $args['taxonomy'] !== null && $args['post_type'] === null ) {
            return [
                'errors' => [[
                    'msg' => '(#) Not enough infomation!'
                ]]
            ];
        }
        
        // Has 'post_type' but not in CONFIG
        if( $args['post_type'] !== null
           && $_CONFIG[ $args['post_type'] ] === null ) {
            return [
                'errors' => [[
                        'msg' => '(#) No post type \''.$args['post_type'].'\' found!'
                ]]
            ];
        }
        
        // Has 'post_type' and 'taxonomy' but not in CONFIG
        if( $args['post_type'] !== null
           && $args['taxonomy'] !== null
           && ! in_array($args['taxonomy'], $_CONFIG[ $args['post_type'] ]['taxonomies']) ) {
            return [
                'errors' => [[
                        'msg' => '(#) No taxonomy \''.$args['taxonomy'].'\' found in post type \''.$args['post_type'].'\'!'
                ]]
            ];
        }
        
        
        
        
        
        /*
        *   All things OK -->
        */
        $args['post_status'] = 'publish';
        
        if( $args['posts_per_page'] === null ) {
            $args['posts_per_page'] = $this->_LIMIT;
        }
        
        if( $args['posts_per_page'] !== null && intval($args['posts_per_page']) > 90 ) {
            $args['posts_per_page'] = $this->_LIMIT;
        }
        
        // ID
        if( $args['id'] !== null ) {
            
            $post = get_post($args['id']);
            
            if( $post !== null && $_CONFIG[$post->post_type] !== null ) {
                $posts[] = $post; // Main info            
                $posts_p_meta = $this->_get_meta( $posts ); // + meta
                $posts_p_sanitized = $this->_sanitize( $posts_p_meta ); // + sanitized
                return $this->_data_corrector( $posts_p_sanitized ); // + correct data
            } else {
                return [
                    'errors' => [[
                            'msg' => 'No post found!'
                    ]]
                ];
            }
            
            
            
        }
        
        // Slug
        if($args['slug'] !== null && $args['post_type'] !== null) {
            $args['name'] = $args['slug'];
            $args['slug'] = null;
            
            $posts = get_posts($args); // Main info
            $posts_p_meta = $this->_get_meta( $posts ); // + meta
            $posts_p_sanitized = $this->_sanitize( $posts_p_meta ); // + sanitized
            return $this->_data_corrector( $posts_p_sanitized ); // + correct data
        }
        
        
        $posts = get_posts($args); // Main info
        $posts_p_meta = $this->_get_meta( $posts ); // + meta
        $posts_p_sanitized = $this->_sanitize( $posts_p_meta ); // + sanitized
        return $this->_data_corrector( $posts_p_sanitized ); // + correct data
//        return $posts_p_meta; // + sanitized
    }
    
    // Add and update TERM
    public function term_update($args = []) {
        
    }
    
    // Add and update POST
    public function post_update($args = []) {
        $_CONFIG = unserialize(POST); // Post config        
        
        
        if( $args['post_type'] === null ) {
            return [
                'errors' => [[
                    'msg' => '(#) Not enough infomation!'
                ]]
            ];
        }
        
        if( $_CONFIG[ $args['post_type'] ] === null ) {
            return [
                'errors' => [[
                        'msg' => '(#) No post type \''.$args['post_type'].'\' found!'
                ]]
            ];
        }
        
        
        $meta = []; // Meta
        foreach( $_CONFIG[ $args['post_type'] ]['schema'] as $key => $original_key ) {
            if( substr($key, 0, 2) === '__') {
                $meta[$key] = $original_key;
            }
        }
        
        $schema = array_merge( $this->_REQUIRED_SCHEMA, $meta ); // Full schema for post type
        
        
        // UPDATE
        if( $args['id'] !== null ) {
            $main_input = [];
            $meta_input = [];
            
            foreach( $args as $key => $value ) {
                if( $key !== 'post_type' && $schema[ $key ] !== null ) {
                    if( substr($key, 0, 2) === '__') {
                        $meta_input[ $schema[$key] ] = $value;
                    } else {
                        $main_input[ $schema[$key] ] = $value;
                    }
                }
            }
            
            // Is post found
            $post = get_post( $args['id'] );
            if( $post === null ) {
                return [
                    'errors' => [[
                            'msg' => '(#) No post found!'
                    ]]
                ];
            }
            
            
            // Main infomation
            $tmp_post = (array) $post;
            foreach($main_input as $key => $value) {
                
                if( !in_array($key, $_CONFIG[ $args['post_type'] ]['increment']) ) {
                    $tmp_post[$key] = $value;
                } else {
                    
                    // Not increment ID field
                    if( $key !== 'ID' ) {
                        $old_value = $tmp_post[$key];

                        if( is_numeric($old_value) && is_numeric($value) ) {

                            if( ctype_digit($old_value) ) {
                                $old_value = intval($old_value);
                            } else {
                                $old_value = floatval($old_value);
                            }

                            if( ctype_digit($value) ) {
                                $value = intval($value);
                            } else {
                                $value = floatval($value);
                            }

                            if( ($old_value + $value) >= 0 ) {
                                $tmp_post[$key] = $old_value + $value;
                            }

                        }
                    }
                    
                }
            }
            
            wp_update_post( $tmp_post );
            
            
            // Meta
            foreach($meta_input as $key => $value) {
                
                if( !in_array($key, $_CONFIG[ $args['post_type'] ]['increment']) ) {
                    update_post_meta( $args['id'], $key, $value );
                } else {
                    $old_value = $this->_meta($args['id'], $key);
                    
                    if( is_numeric($old_value) && is_numeric($value) ) {

                        if( ctype_digit($old_value) ) {
                            $old_value = intval($old_value);
                        } else {
                            $old_value = floatval($old_value);
                        }

                        if( ctype_digit($value) ) {
                            $value = intval($value);
                        } else {
                            $value = floatval($value);
                        }
                        
                        if( ($old_value + $value) >= 0 ) {
                            update_post_meta( $args['id'], $key, $old_value + $value );
                        }

                    }
                    
                }
            }
            
//            // Test
//            return [
//                'main_input' => $main_input,
//                'meta_input' => $meta_input,
//                'post' => $post,
//                'tmp_post' => $tmp_post
//            ];
            
            return $args['id'];
        }
        
        
        
        
        // ADD NEW POST
        return ['No ID => Add new!'];
        
    }
    
    
    
    
    
    
    
}