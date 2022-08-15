<?php
class Url
{
    const MALFORMED_LINK = 'Enlace malformado';
    const INSECURE_PROTOCOL = 'Protocolo inseguro';
    const ERROR_LINK = 'Not found';

    /**
     *
     * Through RegEx determines if it complies with the appropriate format
     *
     * @param  string $url
     * @return  int
     *
     */
    public static function is_valid_format($url)
    {
        return preg_match('/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i', $url);
    }
    /**
     *
     * Check if the protocol is HTTPS
     *
     * @param  string $url
     * @return  string
     *
     */
    public static function is_valid_protocol($url)
    {
        return esc_url_raw($url, ['https']);
    }
    /**
     *
     * Check if the link is available
     *
     * @param  string $url
     * @return  string
     *
     */
    public static function response($url)
    {
        global $wpdb;

        $ch = curl_init($url);
        curl_setopt( $ch, CURLOPT_POST, false );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT x.y; rv:10.0) Gecko/20100101 Firefox/10.0");
        curl_setopt( $ch, CURLOPT_HEADER, false );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $data = curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);
        
        return $headers['http_code'];
    }
    /**
     *
     * Store the error in the table
     *
     * @param  array $params
     * @return  boolean
     *
     */
    public static function save_error($params)
    {
        global $wpdb;
        return $wpdb->insert($wpdb->prefix."urls", $params, array('%s', '%s', '%d'));
    }
    /**
     *
     * Execute the task to verify each link of the posts
     *
     */
    public static function task()
    {
        global $wpdb;

        $posts = $wpdb->get_results("SELECT posts.ID, posts.post_content FROM ".$wpdb->prefix."posts as posts WHERE post_type = 'post'");
        foreach($posts as $post)
        {
            if(0 == $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."urls WHERE post_ID = ".$post->ID.""))
            {
                $urls = preg_match_all('/<a href="(.*?)"/s', $post->post_content, $match);
                if($urls != 0)
                {
                    foreach($match[1] as $url)
                    {
                        if(self::is_valid_format($url) == 1)
                        {
                            if(self::is_valid_protocol($url) != '')
                            {
                                if(self::response($url) != '200')
                                    self::save_error(['URL' => $url, 'status' => self::ERROR_LINK, 'post_ID' => $post->ID]);
                            } else {
                                self::save_error(['URL' => $url, 'status' => self::INSECURE_PROTOCOL, 'post_ID' => $post->ID]);
                            }
                        } else {
                            self::save_error(['URL' => $url, 'status' => self::MALFORMED_LINK, 'post_ID' => $post->ID]);
                        }
                    }
                }
            }
        }
    }
}
// Url::task(); // Cronjobs