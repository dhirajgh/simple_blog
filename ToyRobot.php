<?php

// Create an instance of ToyRobot with the name "Tom"
$tom = new ToyRobot("Tom");

// Have Tom introduce himself
$tom->writeName();

// Build Jim and have him introduce himself
$jim = new ToyRobot("Jim");
$jim->writeName();

//Another approach for displaying the power of the Classes and Objects
$tom = new ToyRobot("Tom");
$jim = new ToyRobot("Jim");
$tom->writeName();
$jim->writeName();


class ToyRobot
{
	// Stores the name of this instance of the robot
	private $_name;
	
	// Sets the name property upon class instantiation
	public function __construct($name)
	{
		$this->_name = $name;
	}
	
	// Writes the robot's name
	public function writeName()
	{
		echo 'My name is ', $this->_name, '.<br />';
	}
	
}



?>