<?php

// Comment class
class Comment {
    
    public function index() {
        return $this->_CONFIG;
    }
    
    public function get($args) {
        $comments = get_comments($args);
        return sanitize( $comments, $this->_CONFIG['schema'] );
    }
    
    public function post($args) {
        
        if($args['post_id'] === null
           || $args['author'] === null
           || $args['author_email'] === null
           || $args['content'] === null
           // || $args['parent'] === null
           //|| $args['user_id'] === null
          ) {
            return [
                'errors' => [[
                        'msg' => '(#) No enough infomation!'
                ]]
            ];
        }
        
        if($args['parent'] !== null) {
            $cmt = get_comment($args['parent']);
            if( $args['post_id'] != $cmt->comment_post_ID ) {
                return [
                    'errors' => [[
                            'msg' => '(#) Invalid infomation!'
                    ]]
                ];
            }
            
            
        }
        
        
        
        // Add to DB
        $comment_id = wp_insert_comment([
            'comment_post_ID' => $args['post_id'],
            'comment_author' => $args['author'],
            'comment_author_email' => $args['author_email'],
            'comment_author_url' => $args['author_url'] ,
            'comment_content' => $args['content'],
            'comment_type' => $args['type'],
            'comment_parent' => $args['parent'],
            'user_id' => $args['user_id'],
            'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
            'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
            'comment_date' => current_time('mysql'),
            'comment_approved' => 0
        ]);
        
        return [
            'comment_id' => $comment_id
        ];
        
    }
    
}