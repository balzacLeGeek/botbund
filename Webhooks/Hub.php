<?php

namespace balzacLeGeek\BotbundBundle\Webhooks;

use Symfony\Component\HttpFoundation\Request;

class Hub
{
    const MODE = 'hub_mode';
    const VERIFY_TOKEN = 'hub_verify_token';
    const CHALLENGE = 'hub_challenge';

    const AVAILABLE_HUBS = [
        self::MODE, self::VERIFY_TOKEN, self::CHALLENGE
    ];

    /** @var string $mode */
    private $mode;

    /** @var string $verifyToken */
    private $verifyToken;

    /** @var string $challenge */
    private $challenge;

    /** @var array $queries */
    private $queries;

    public static function buildFromRequest(Request $request): self
    {
        $queries = $request->query->all();

        $hubObject = new self();

        foreach (self::AVAILABLE_HUBS as $hub) {
            if (isset($queries[$hub])) {
                $hubValue = $queries[$hub];

                switch ($hub) {
                    case self::MODE:
                        $hubObject->setMode($hubValue);
                        continue;

                    case self::VERIFY_TOKEN:
                        $hubObject->setVerifyToken($hubValue);
                        continue;

                    case self::CHALLENGE:
                        $hubObject->setChallenge($hubValue);
                        continue;
                }
            }
        }

        $hubObject->setQueries($queries);

        return $hubObject;
    }

    /**
     * @var string $mode
     * @return Hub
     */
    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return bool
     */
    public function hasMode(): bool
    {
        return $this->mode !== null && !empty($this->mode);
    }

    /**
     * @var string $verifyToken
     * @return Hub
     */
    public function setVerifyToken(string $verifyToken): self
    {
        $this->verifyToken = $verifyToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getVerifyToken(): string
    {
        return $this->verifyToken;
    }

    /**
     * @return bool
     */
    public function hasVerifyToken(): bool
    {
        return $this->verifyToken !== null && !empty($this->verifyToken);
    }

    /**
     * @var string $challenge
     * @return Hub
     */
    public function setChallenge(string $challenge): self
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * @return string
     */
    public function getChallenge(): string
    {
        return $this->challenge;
    }

    /**
     * @return bool
     */
    public function hasChallenge(): bool
    {
        return $this->challenge !== null && !empty($this->challenge);
    }

    /**
     * @var array $queries
     * @return Hub
     */
    public function setQueries(array $queries): self
    {
        $this->queries = $queries;

        return $this;
    }

    /**
     * @return array
     */
    public function getQueries(): array
    {
        return $this->queries;
    }
}
