<?php

namespace Wdr\InowebBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Wdr\InowebBundle\Entity\Image;

class ServiceAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('text')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('title')
            ->add('text')
			->add('image', 'sonata_type_admin', array('delete' => false))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('text')
			->add('image', 'sonata_type_admin', array('delete' => false))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('text')
			->add('image', 'sonata_type_admin', array('delete' => false))
        ;
    }

	public function prePersist($page) {
		$this->manageEmbeddedImageAdmins($page);
	}
	public function preUpdate($page) {
		$this->manageEmbeddedImageAdmins($page);
	}
	private function manageEmbeddedImageAdmins($page) {
		// Cycle through each field
		foreach ($this->getFormFieldDescriptions() as $fieldName => $fieldDescription) {
			// detect embedded Admins that manage Images
			if ($fieldDescription->getType() === 'sonata_type_admin' &&
				($associationMapping = $fieldDescription->getAssociationMapping()) &&
				$associationMapping['targetEntity'] === 'Wdr\Bundle\Entity\Image'
			) {
				$getter = 'get' . $fieldName;
				$setter = 'set' . $fieldName;

				/** @var Image $image */
				$image = $page->$getter();
				if ($image) {
					if ($image->getFile()) {
						// update the Image to trigger file management
						$image->refreshUpdated();
					} elseif (!$image->getFile() && !$image->getFilename()) {
						// prevent Sf/Sonata trying to create and persist an empty Image
						$page->$setter(null);
					}
				}
			}
		}
	}
}
