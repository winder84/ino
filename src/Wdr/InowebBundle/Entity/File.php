<?php

namespace Wdr\InowebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * File
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class File
{

	/**
	 * Unmapped property to handle file uploads
	 */
	private $file;

	/**
	 * Unmapped property to handle file uploads
	 */
	private $filepath = '/uploads';

	/**
	 * Sets filepath.
	 *
	 * @param string $filepath
	 */
	public function setFilepath($filepath)
	{
		$this->filepath = $filepath;
	}

	/**
	 * Get filepath.
	 *
	 * @return string
	 */
	public function getFilepath()
	{
		return $this->filepath;
	}

	/**
	 * Sets file.
	 *
	 * @param UploadedFile $file
	 */
	public function setFile(UploadedFile $file = null)
	{
		$this->file = $file;
	}

	/**
	 * Get file.
	 *
	 * @return File
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * Manages the copying of the file to the relevant place on the server
	 */
	public function upload()
	{
		// the file property can be empty if the field is not required
		if (null === $this->getFile()) {
			return;
		}

		// we use the original file name here but you should
		// sanitize it at least to avoid any security issues

		// move takes the target directory and target filename as params
		$this->getFile()->move(
			$this->filePath,
			$this->getFile()->getClientOriginalName()
		);

		// set the path property to the filename where you've saved the file
		$this->name = $this->getFile()->getClientOriginalName();

		// clean up the file property as you won't need it anymore
		$this->setFile(null);
	}

	/**
	 * Lifecycle callback to upload the file to the server
	 */
	public function lifecycleFileUpload() {
		$this->upload();
	}

	/**
	 * Updates the hash value to force the preUpdate and postUpdate events to fire
	 */
	public function refreshUpdated() {
		$this->setUpdated(new \DateTime("now"));
	}

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
	 * File name
	 *
	 * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
