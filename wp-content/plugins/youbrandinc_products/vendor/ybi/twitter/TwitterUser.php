<?php

/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 9/11/2015
 * Time: 7:46 PM
 */
class TwitterUser
{
    private $id, $twitter_id, $name, $screen_name, $description, $url, $followers_count, $friends_count, $listed_count,$statuses_count,
        $profile_image_url, $created_at, $following, $recomend_follow,$is_default_profile_image,$profile_banner_url;

    function GCD ($a, $b)
    {
        while ( $b != 0)
        {
            $remainder = $a % $b;
            $a = $b;
            $b = $remainder;
        }
        return abs ($a);
    }
    public function getFollowerToFollowingRatio()
    {
        return $this->getFollowersCount() / $this->getFriendsCount();
        //return $this->GCD($this->getFollowersCount(), $this->getFriendsCount());
    }
    public function getFollowingToFollowersRatio()
    {
        return $this->getFollowersCount() / $this->getFriendsCount();
    }

    /**
     * @return mixed
     */
    public function getProfileBannerUrl()
    {
        return $this->profile_banner_url;
    }

    /**
     * @param mixed $profile_banner_url
     */
    public function setProfileBannerUrl($profile_banner_url)
    {
        $this->profile_banner_url = $profile_banner_url;
    }

    /**
     * @return mixed
     */
    public function getIsDefaultProfileImage()
    {
        return $this->is_default_profile_image;
    }

    /**
     * @param mixed $is_default_profile_image
     */
    public function setIsDefaultProfileImage($is_default_profile_image)
    {
        $this->is_default_profile_image = $is_default_profile_image;
    }

    public function calculateRecommendFollow()
    {
        $recommend_to_follow = false;
        if($this->getFriendsCount() < 500) {
            $recommend_to_follow = true;
        }
        if(!$recommend_to_follow) {
            if($this->getFollowersCount() < 500) {
                $recommend_to_follow = true;
            }
        }
        $this->setRecomendFollow($recommend_to_follow);
    }

    /**
     * @return mixed
     */
    public function getRecomendFollow()
    {
        return $this->recomend_follow;
    }

    /**
     * @param mixed $recomend_follow
     */
    public function setRecomendFollow($recomend_follow)
    {
        $this->recomend_follow = $recomend_follow;
    }

    /**
     * @return mixed
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @param mixed $following
     */
    public function setFollowing($following)
    {
        $this->following = $following;
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getTwitterId()
    {
        return $this->twitter_id;
    }

    /**
     * @param mixed $twitter_id
     */
    public function setTwitterId($twitter_id)
    {
        $this->twitter_id = $twitter_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getScreenName()
    {
        return $this->screen_name;
    }

    /**
     * @param mixed $screen_name
     */
    public function setScreenName($screen_name)
    {
        $this->screen_name = $screen_name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getFollowersCount()
    {
        return $this->followers_count;
    }

    /**
     * @param mixed $followers_count
     */
    public function setFollowersCount($followers_count)
    {
        $this->followers_count = $followers_count;
    }

    /**
     * @return mixed
     */
    public function getFriendsCount()
    {
        return $this->friends_count;
    }

    /**
     * @param mixed $friends_count
     */
    public function setFriendsCount($friends_count)
    {
        $this->friends_count = $friends_count;
    }

    /**
     * @return mixed
     */
    public function getListedCount()
    {
        return $this->listed_count;
    }

    /**
     * @param mixed $listed_count
     */
    public function setListedCount($listed_count)
    {
        $this->listed_count = $listed_count;
    }

    /**
     * @return mixed
     */
    public function getStatusesCount()
    {
        return $this->statuses_count;
    }

    /**
     * @param mixed $statuses_count
     */
    public function setStatusesCount($statuses_count)
    {
        $this->statuses_count = $statuses_count;
    }

    /**
     * @return mixed
     */
    public function getProfileImageUrl()
    {
        return $this->profile_image_url;
    }

    /**
     * @param mixed $profile_image_url
     */
    public function setProfileImageUrl($profile_image_url)
    {
        $this->profile_image_url = $profile_image_url;
    }

}