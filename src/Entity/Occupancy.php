<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OccupancyRepository")
 */
class Occupancy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $r_id;

    /**
     * @ORM\Column(type="string", length=63)
     */
    private $guest;

    /**
     * @ORM\Column(type="datetime")
     */
    private $in_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $days;

    /**
     * @ORM\Column(type="datetime")
     */
    private $out_time;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $created;

    public function getId()
    {
        return $this->id;
    }

    public function getRId(): ?int
    {
        return $this->r_id;
    }

    public function setRId(int $r_id): self
    {
        $this->r_id = $r_id;

        return $this;
    }

    public function getGuest(): ?string
    {
        return $this->guest;
    }

    public function setGuest(string $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getInDate(): ?\DateTimeInterface
    {
        return $this->in_date;
    }

    public function setInDate(\DateTimeInterface $in_date): self
    {
        $this->in_date = $in_date;

        return $this;
    }

    public function getDays(): ?int
    {
        return $this->days;
    }

    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOutTime(): ?\DateTimeInterface
    {
        return $this->out_time;
    }

    public function setOutTime(\DateTimeInterface $out_time): self
    {
        $this->out_time = $out_time;

        return $this;
    }
}
