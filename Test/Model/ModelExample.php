<?php
/**
 * This file is part of the Oxygen Bundle Package.
 *
 * (c) 2014 Oxyfony
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace O2\Bundle\ModelBundle\Test\Model;

/**
 * Example of model class for test
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class ModelExample {
	
	protected $id;
	protected $createdAt;
	protected $arg1;
	
	public function __construct($arg1 = null) {
		$this->arg1 = $arg1;
		$this->setCreatedAt(new \DateTime());
	}
	
	/**
	* @param string $arg1
	* @return ModelExample
	*/
	public function setArg1($arg1)
	{
	    $this->arg1 = $arg1;
	    return $this;
	}
	 
	/**
	* @return string
	*/
	public function getArg1()
	{
	    return $this->arg1;
	}
	
	/**
	* @return integer
	*/
	public function getId()
	{
	    return $this->id;
	}
	
	/**
	* @param \DateTime $createdAt
	* @return ModelExample
	*/
	public function setCreatedAt($createdAt)
	{
	    $this->createdAt = $createdAt;
	    return $this;
	}
	 
	/**
	* @return \DateTime
	*/
	public function getCreatedAt()
	{
	    return $this->createdAt;
	}
	
}