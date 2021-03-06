<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ArticleRepository")
 * @ORM\Table
 */
class Article {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Site")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected $site;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $type = self::TYPE_POST;

    const TYPE_POST = 'post';
    const TYPE_PAGE = 'page';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @var text
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $state = self::STATE_DRAFT;

    const STATE_DRAFT = 'draft';
    const STATE_PUBLISHED = 'published';

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected $user;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $created;

    public function __construct() {
        $this->created = new \DateTime();
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isPublished() {
        return $this->getState() === self::STATE_PUBLISHED;
    }

    /**
     * @param string $state
     *
     * @return ContentBase
     */
    public function setState($state) {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getState() {
        return $this->state;
    }

    public function setType(string $type) : Article {
        $this->type = $type;

        return $this;
    }

    public function getType() : string {
        return $this->type;
    }

    /**
     * @param string $title
     *
     * @return ContentBase
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $url
     *
     * @return ContentBase
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $content
     *
     * @return ContentBase
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return ContentBase
     */
    public function setCreated($createdAt) {
        $this->created = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param User $user
     *
     * @return ContentBase
     */
    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param Site $site
     *
     * @return Post
     */
    public function setSite(Site $site) {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Site
     */
    public function getSite() {
        return $this->site;
    }

    private $transformedContent;

    public function setTransformedContent($content) {
        $this->transformedContent = $content;
        return $this;
    }

    public function getTransformedContent() {
        return $this->transformedContent;
    }
}
