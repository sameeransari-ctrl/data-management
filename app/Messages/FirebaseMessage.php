<?php

namespace App\Messages;

use App\Facades\FirebaseFacade;

class FirebaseMessage
{
    const PRIORITY_NORMAL = 'normal';

    protected $title;

    protected $body;

    protected $clickAction;

    protected $image;

    protected $icon;

    protected $sound;

    protected $additionalData;

    protected $priority = self::PRIORITY_NORMAL;

    protected $fromArray;
    
    /**
     * Method withTitle
     *
     * @param string $title 
     *
     * @return object
     */
    public function withTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }
    
    /**
     * Method withBody
     *
     * @param string $body
     *
     * @return object
     */
    public function withBody(string $body)
    {
        $this->body = $body;

        return $this;
    }
    
    /**
     * Method withClickAction
     *
     * @param string $clickAction
     *
     * @return object
     */
    public function withClickAction(string $clickAction)
    {
        $this->clickAction = $clickAction;

        return $this;
    }
    
    /**
     * Method withImage
     *
     * @param string $image
     *
     * @return object
     */
    public function withImage(string $image)
    {
        $this->image = $image;

        return $this;
    }
    
    /**
     * Method withIcon
     *
     * @param string $icon
     *
     * @return object
     */
    public function withIcon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }
    
    /**
     * Method withSound
     *
     * @param string $sound
     *
     * @return object
     */
    public function withSound(string $sound)
    {
        $this->sound = $sound;

        return $this;
    }
    
    /**
     * Method withAdditionalData
     *
     * @param array $additionalData
     *
     * @return object
     */
    public function withAdditionalData(array $additionalData)
    {
        $this->additionalData = $additionalData;

        return $this;
    }
    
    /**
     * Method withPriority
     *
     * @param string $priority [explicite description]
     *
     * @return object
     */
    public function withPriority(string $priority)
    {
        $this->priority = $priority;

        return $this;
    }
    
    /**
     * Method fromArray
     *
     * @param array $fromArray
     *
     * @return object
     */
    public function fromArray(array $fromArray)
    {
        $this->fromArray = $fromArray;

        return $this;
    }
    
    /**
     * Method asNotification
     *
     * @param array $deviceTokens
     *
     * @return mixed
     */
    public function asNotification(array $deviceTokens)
    {
        if ($this->fromArray) {
            return FirebaseFacade::fromArray($this->fromArray)->sendNotification($deviceTokens);
        }

        return FirebaseFacade::withTitle($this->title)
            ->withBody($this->body)
            ->withClickAction($this->clickAction)
            ->withImage($this->image)
            ->withIcon($this->icon)
            ->withSound($this->sound)
            ->withPriority($this->priority)
            ->withAdditionalData($this->additionalData)
            ->sendNotification($deviceTokens);
    }
    
    /**
     * Method asMessage
     *
     * @param array $deviceTokens [explicite description]
     *
     * @return mixed
     */
    public function asMessage(array $deviceTokens)
    {
        if ($this->fromArray) {
            return FirebaseFacade::fromArray($this->fromArray)->sendMessage($deviceTokens);
        }

        return FirebaseFacade::withTitle($this->title)
            ->withBody($this->body)
            ->withAdditionalData($this->additionalData)
            ->sendMessage($deviceTokens);
    }
}
