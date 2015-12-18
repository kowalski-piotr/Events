<?php

/*
 * The MIT License
 *
 * Copyright 2015 pchel.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Events\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Event
{
    const DAY_OFFSET = 7;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50) 
     */
    protected $name;

    /**
     * @ORM\Column(type="string") 
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=300) 
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=50) 
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime") 
     */
    protected $fromDate;

    /**
     * @ORM\Column(type="datetime") 
     */
    protected $toDate;

    /**
     * @ORM\Column(type="string", nullable=true) 
     */
    protected $coordinates;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFromDate()
    {
        return $this->fromDate;
    }

    public function getToDate()
    {
        return $this->toDate;
    }

    public function getCoordinates()
    {
        return $this->coordinates;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setFromDate($fromDate)
    {
        if (is_string($fromDate))
        {
            $fromDate = new \DateTime($fromDate);
        }
        $this->fromDate = $fromDate;
        return $this;
    }

    public function setToDate($toDate)
    {
        if (is_string($toDate))
        {
            $toDate = new \DateTime($toDate);
        }
        $this->toDate = $toDate;
        return $this;
    }

    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
        return $this;
    }

}
