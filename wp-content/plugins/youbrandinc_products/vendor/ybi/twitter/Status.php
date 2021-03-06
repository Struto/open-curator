<?php

/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 9/11/2015
 * Time: 7:47 PM
 */
class Status
{
    private $id, $id_str, $text, $source, $source_raw, $created_at, $TwitterUser, $hashtags, $attached_media, $user_mentions, $retweet_count, $favorite_count,
        $priority_all, $priority_retweet, $priority_follow, $priority_ignore, $recommend_retweet, $entity_urls_arr;


    /**
     * This will add a content_item_id (int) to the content_id_arr which stores the content_items by id to be blocked
     *
     * @access public
     * @param int $in_content_item_id an id that references a content_item
     *
     * @return void
     */
    public function addEntityURL($in_url)
    {
        if(!is_array($this->entity_urls_arr))
            $this->entity_urls_arr = array($in_url);
        else {
            if(!in_array($in_url,$this->entity_urls_arr))
                $this->entity_urls_arr[] = $in_url;
        }

    }

    public function setPriorities()
    {

    }

    /**
     * @return mixed
     */
    public function getRecommendRetweet()
    {
        return $this->recommend_retweet;
    }

    /**
     * @param mixed $recommend_retweet
     */
    public function setRecommendRetweet($recommend_retweet)
    {
        $this->recommend_retweet = $recommend_retweet;
    }



    /**
     * @return mixed
     */
    public function getPriorityAll()
    {
        return $this->priority_all;
    }

    /**
     * @param mixed $priority_all
     */
    public function setPriorityAll($priority_all)
    {
        $this->priority_all = $priority_all;
    }

    /**
     * @return mixed
     */
    public function getPriorityRetweet()
    {
        return $this->priority_retweet;
    }

    /**
     * @param mixed $priority_retweet
     */
    public function setPriorityRetweet($priority_retweet)
    {
        $this->priority_retweet = $priority_retweet;
    }

    /**
     * @return mixed
     */
    public function getPriorityFollow()
    {
        return $this->priority_follow;
    }

    /**
     * @param mixed $priority_follow
     */
    public function setPriorityFollow($priority_follow)
    {
        $this->priority_follow = $priority_follow;
    }

    /**
     * @return mixed
     */
    public function getPriorityIgnore()
    {
        return $this->priority_ignore;
    }

    /**
     * @param mixed $priority_ignore
     */
    public function setPriorityIgnore($priority_ignore)
    {
        $this->priority_ignore = $priority_ignore;
    }

    /**
     * @return mixed
     */
    public function getRetweetCount()
    {
        return $this->retweet_count;
    }

    /**
     * @param mixed $retweet_count
     */
    public function setRetweetCount($retweet_count)
    {
        $this->retweet_count = $retweet_count;
    }

    /**
     * @return mixed
     */
    public function getFavoriteCount()
    {
        return $this->favorite_count;
    }

    /**
     * @param mixed $favorite_count
     */
    public function setFavoriteCount($favorite_count)
    {
        $this->favorite_count = $favorite_count;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getHashtags()
    {
        return $this->hashtags;
    }

    /**
     * @param mixed $hashtags
     */
    public function setHashtags($hashtags)
    {
        $this->hashtags = $hashtags;
    }

    public function addHashtag($hashtag)
    {
        if(!is_array($this->hashtags))
            $this->hashtags = array();

        if(!array_key_exists($hashtag,$this->hashtags))
            $this->hashtags[] = $hashtag;
    }
    public function addMedia($Media)
    {
        if(!is_array($this->attached_media))
            $this->attached_media = array();

        $this->attached_media[] = $Media;
    }

    public function addUserMentions($TwitterUser)
    {
        if(!is_array($this->user_mentions))
            $this->user_mentions = array();

        $this->user_mentions[] = $TwitterUser;
    }

    /**
     * @return mixed
     */
    public function getEntityUrlsArr()
    {
        return $this->entity_urls_arr;
    }

    /**
     * @param mixed $entity_urls_arr
     */
    public function setEntityUrlsArr($entity_urls_arr)
    {
        $this->entity_urls_arr = $entity_urls_arr;
    }


    /**
     * @return mixed
     */
    public function getAttachedMedia()
    {
        return $this->attached_media;
    }

    /**
     * @param mixed $attached_media
     */
    public function setAttachedMedia($attached_media)
    {
        $this->attached_media = $attached_media;
    }

    /**
     * @return mixed
     */
    public function getUserMentions()
    {
        return $this->user_mentions;
    }

    /**
     * @param mixed $user_mentions
     */
    public function setUserMentions($user_mentions)
    {
        $this->user_mentions = $user_mentions;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIdStr()
    {
        return $this->id_str;
    }

    /**
     * @param mixed $id_str
     */
    public function setIdStr($id_str)
    {
        $this->id_str = $id_str;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getSourceRaw()
    {
        return $this->source_raw;
    }

    /**
     * @param mixed $source_raw
     */
    public function setSourceRaw($source_raw)
    {
        $this->source_raw = $source_raw;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function addTwitterUser($TwitterUser)
    {
        $this->TwitterUser = $TwitterUser;
    }
    public function getTwitterUser()
    {
        return $this->TwitterUser;
    }

}