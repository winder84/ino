<?php

namespace Wdr\InowebBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Wdr\InowebBundle\Entity\File;

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
			->add('file', 'sonata_type_admin', array('delete' => false))
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
			->add('file', 'sonata_type_admin', array('delete' => false))
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
			->add('file', 'sonata_type_admin', array('delete' => false))
        ;
    }

	public function prePersist($page) {
		$this->manageEmbeddedFileAdmins($page);
	}
	public function preUpdate($page) {
		$this->manageEmbeddedFileAdmins($page);
	}
	private function manageEmbeddedFileAdmins($page) {
		// Cycle through each field
		foreach ($this->getFormFieldDescriptions() as $fieldName => $fieldDescription) {
			// detect embedded Admins that manage Files
			if ($fieldDescription->getType() === 'sonata_type_admin' &&
				($associationMapping = $fieldDescription->getAssociationMapping()) &&
				$associationMapping['targetEntity'] === 'Wdr\Bundle\Entity\File'
			) {
				$getter = 'get' . $fieldName;
				$setter = 'set' . $fieldName;

				/** @var File $file */
				$file = $page->$getter();
				if ($file) {
					if ($file->getFile()) {
						// update the File to trigger file management
						$file->refreshUpdated();
					} elseif (!$file->getFile() && !$file->getFilename()) {
						// prevent Sf/Sonata trying to create and persist an empty File
						$page->$setter(null);
					}
				}
			}
		}
	}
}
