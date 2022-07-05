<?php

namespace PruebaBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use PruebaBundle\Repository\ExpedienteRepository;

use PruebaBundle\Entity\Expediente;
use PruebaBundle\Entity\servicio;

use PruebaBundle\Repository\servicioRepository;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;


class FacturaType extends AbstractType
{
    
   
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numFactura')
                ->add('fechaVencimiento', DateType::class, [
                    'label' => 'Fecha de Vencimiento'
                    ,'widget' => 'single_text'
                    ,'html5' => true
                ])
                ->add('pago')->add('periodo')->add('service')->add('expediente')
            ;
            $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
            $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
        }
    
    protected function addElements(FormInterface $form,Expediente $expediente=null,servicio $servicio=null) {
        //ordeno de menor a mayor los numeros de referencias al agregar una nueva factura
        $form->add('expediente', EntityType::class, array(
            'required' => true,
            'data' => $expediente,
            'placeholder' => 'Seleccionar n° expediente',
            'class' => 'PruebaBundle:Expediente',
            'query_builder' => function (ExpedienteRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'DESC');}
        
        ));

     
    
     }
 
     function onPreSubmit(FormEvent $event) {
         $form = $event->getForm();
         $data = $event->getData();
         
     
         
         $this->addElements($form);
     }
 
     function onPreSetData(FormEvent $event) {
         $factura = $event->getData();
         $form = $event->getForm();
 
         // Cuando creas una nueva persona, la ciudad siempre está vacía
   
        
         $this->addElements($form);
     }
    
    
    
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PruebaBundle\Entity\Factura'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pruebabundle_factura';
    }


}
